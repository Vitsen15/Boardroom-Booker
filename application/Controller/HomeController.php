<?php

namespace Controller;

use Core\Controller;
use DateTime;
use Model\Boardroom;
use PDOException;

class HomeController extends Controller
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
     * @param $direction
     *
     * @return string
     */
    public function changeCalendarMonth($year, $month, $direction)
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

        $data = $this->initCalendarData($parameters);

        $view = VIEWS_PATH . 'index.php';

        return $this->view($view, $data);
    }

    /**
     * Changes selected boardroom.
     *
     * @param $boardroomID
     * @return string
     */
    public function changeBoardroom($boardroomID)
    {
        parent::checkAuth();

        $view = VIEWS_PATH . 'index.php';
        $parameters['boardroomID'] = $boardroomID;
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

        $data['weeks'] = $this->renderWeeksOfCurrentMonth($data['year'], $data['month']);

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

    private function renderWeeksOfCurrentMonth($month, $year)
    {
        $date = new DateTime($year . '-' . $month);
        $monthDayCount = (int)$date->format('t');
        $dayOfWeek = (int)$date->format('w');

        $offset = $this->getFirstWeekOffset($dayOfWeek);
        $weekDayIndex = $this->calculateDayIndex($dayOfWeek);

        $weeks = [];

        // Add empty cells
        $week = '';
        $week .= str_repeat("<td></td>", $offset);

        for ($day = 1; $day < $monthDayCount; $day++, $weekDayIndex++) {

            $week .= "<td>$day</td>";

            if ($weekDayIndex % 7 === 6 || $day === $monthDayCount) {
                if ($day === $monthDayCount) {
                    $week .= str_repeat("<td></td>", 6 - ($weekDayIndex % 7));
                }

                $weeks[] = $week;

                $week = '';
            }
        }

        return $weeks;
    }

    private function calculateDayIndex($indexOfWeekDay)
    {
        if (FIRST_DAY_OF_WEEK === 'monday') return $indexOfWeekDay;
        if ($indexOfWeekDay === 7) return 1;
        return $indexOfWeekDay + 1;
    }

    private function getFirstWeekOffset($indexOfWeekDay)
    {
        return $this->calculateDayIndex($indexOfWeekDay) - 1;
    }
}
