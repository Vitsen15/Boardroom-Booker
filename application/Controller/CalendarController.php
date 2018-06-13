<?php

namespace Controller;

use Core\Controller;
use DateTime;
use Model\Appointment;
use Model\Boardroom;
use PDOException;

class CalendarController extends Controller
{
    /**
     * @return string
     */
    public function index()
    {
        parent::checkAuth();

        $view = VIEWS_PATH . 'index.php';
        $data = $this->initCalendarData();

        return $this->view($view, $data);
    }

    /**
     * Changes current month.
     *
     * @param $year
     * @param $month
     * @param $boardroomID
     * @param $direction
     *
     * @return string
     */
    public function changeCalendarMonth($year, $month, $boardroomID, $direction)
    {
        parent::checkAuth();
        $this->validateCalendarMonthChanging($year, $month, $direction);

        /** @var DateTime $date */
        $date = new DateTime($year . '-' . $month);

        switch ($direction) {
            case 'back':
                $date->modify('-1 month');
                break;
            case 'forward':
                $date->modify('+1 month');
                break;
        }

        $parameters['year'] = $date->format('Y');
        $parameters['month'] = $date->format('F');
        $parameters['boardroomID'] = $boardroomID;

        $data = $this->initCalendarData($parameters);

        $view = VIEWS_PATH . 'index.php';

        return $this->view($view, $data);
    }

    /**
     * Changes selected boardroom.
     *
     * @param $boardroomID
     * @param $year
     * @param $month
     * @return string
     */
    public function changeBoardroom($boardroomID, $year, $month)
    {
        parent::checkAuth();

        $view = VIEWS_PATH . 'index.php';
        $parameters['boardroomID'] = $boardroomID;
        $parameters['year'] = $year;
        $parameters['month'] = $month;
        $data = $this->initCalendarData($parameters);

        return $this->view($view, $data);
    }

    /**
     * @param array $parameters
     * @return array
     */
    private function initCalendarData($parameters = null)
    {
        $data['boardrooms'] = $this->getCroppedBoardroomsList();

        $currentBoardroomID = isset($parameters['boardroomID']) ?
            (new Boardroom())->getBoardroomByID($parameters['boardroomID'])->id :
            $data['boardrooms'][0]->id;

        $data['currentBoardroomID'] = $currentBoardroomID;

        $data['boardroomName'] = isset($parameters['boardroomID']) ?
            (new Boardroom())->getBoardroomByID($parameters['boardroomID'])->name :
            $data['boardrooms'][0]->name;

        $data['year'] = isset($parameters['year']) ?
            $parameters['year'] :
            (new DateTime())->format('Y');

        $data['month'] = isset($parameters['month']) ?
            $parameters['month'] :
            (new DateTime())->format('F');

        $data['weekDayNames'] = $this->getWeekDaysNames();

        $data['weeks'] = $this->generateWeeksArrayWithAppointmentsData($data['year'], $data['month'], $currentBoardroomID);

//        var_dump($currentBoardroomID);die;

        return $data;
    }

    /**
     * Crops a boardrooms to count set up in config.
     *
     * @return array
     */
    private function getCroppedBoardroomsList()
    {
        $this->model = new Boardroom();

        return array_slice($this->model->getBoardrooms(), 0, BOARDROOMS_COUNT);
    }

    /**
     * Creates an array of week days names that begin from specified in config day.
     *
     * @return array
     */
    private function getWeekDaysNames()
    {
        $daysOfWeek = [];

        switch (FIRST_DAY_OF_WEEK) {
            case 'monday':
                $daysOfWeek = [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday'
                ];
                break;
            case 'sunday':
                $daysOfWeek = [
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                ];
                break;
        }

        return $daysOfWeek;
    }

    /**
     * @param string $year
     * @param string $month
     * @param string $direction
     */
    private function validateCalendarMonthChanging($year, $month, $direction)
    {
        if (!ctype_digit((int)$year)) {
            header('Location: ' . URL . '/error');
        } elseif ($direction != 'back' && $direction != 'forward') {
            header('Location: ' . URL . '/error');
        }

        try {
            (new DateTime($month))->format('F');
        } catch (PDOException $exception) {
            header('Location: ' . URL . '/error');
        }
    }

    /**
     * Generate array that contains weeks that contains arrays with days
     * that contains array of appointments for each day
     *
     * @param string $year
     * @param string $month
     * @return array
     */
    private function generateWeeksArrayWithAppointmentsData($year, $month, $boardroomID)
    {
        $date = new DateTime($year . '-' . $month);
        $monthDayCount = (int)$date->format('t');
        $dayOfWeek = (int)$date->format('w');

        $offset = $this->getFirstWeekOffset($dayOfWeek);
        $weekDayIndex = $this->calculateDayIndex($dayOfWeek);

        $weeks = [];
        $week = [];

        if ($offset > 0) {
            // Add empty cells at beginning of the month
            for ($i = 0; $i < $offset; $i++) $week[$i] = null;
        }

        $this->model = new Appointment();

        for ($day = 1, $arrayIndex = $offset; $day <= $monthDayCount; $day++, $weekDayIndex++, $arrayIndex++) {

            $week[$arrayIndex]['monthDay'] = $day;

            $currentDayDate = DateTime::createFromFormat('Y-F-d', "{$year}-{$month}-{$day}");
            $week[$arrayIndex]['appointments'] = $this->model->getAppointmentsByDayAndBoardroom($currentDayDate, $boardroomID);

            if ($weekDayIndex % 7 === 0 || $day === $monthDayCount) {

                if ($day === $monthDayCount) {

                    // Add empty cells at ending of the month
                    $offset = 7 - ($weekDayIndex % 7);
                    if ($weekDayIndex !== 7) {
                        for ($i = 0; $i < $offset; $i++) array_push($week, null);
                    }
                }

                $weeks[] = $week;

                $weekDayIndex = 0;
                $week = [];
            }
        }

        return $weeks;
    }

    /**
     * Calculates day index depending on FIRST_DAY_OF_WEEK constant defined in config
     *
     * @param $indexOfWeekDay
     * @return int
     */
    private function calculateDayIndex($indexOfWeekDay)
    {
        switch (FIRST_DAY_OF_WEEK) {
            case 'monday':
                if ($indexOfWeekDay === 0) return 7;
                return $indexOfWeekDay;
            case 'sunday':
                if ($indexOfWeekDay === 7) return 1;
                return $indexOfWeekDay + 1;
        }
    }

    private function getFirstWeekOffset($indexOfWeekDay)
    {
        return $this->calculateDayIndex($indexOfWeekDay) - 1;
    }
}
