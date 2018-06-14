<?php

namespace Controller;

use Core\Controller;
use DateTime;
use Exception;
use Model\Appointment;
use Model\Employee;
use Model\RecurringType;

class BookingController extends Controller
{
    const YEARS_PERIOD = 30;
    const MY_SQL_TEXT_FIELD_SIZE = 65535;

    /**
     * Default controller route.
     *
     * @param null $boardroomID
     */
    public function index($boardroomID)
    {
        parent::checkAuth();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
            $_SESSION['boardroomID'] = $boardroomID;
            session_write_close();
        }

        $view = VIEWS_PATH . 'booking.php';
        $data = $this->initBookingFormRenderData();

        $this->view($view, $data);
    }

    public function bookAppointment()
    {
        parent::checkAuth();
        $this->validateBooking($_POST);
        $this->model = new Appointment();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $config['boardroomID'] = $_SESSION['boardroomID'];
        $config['recurringTypeID'] = $_POST['recurring'] === 'true' ?
            (new RecurringType())->getRecurringTypeByName($_POST['recurring-type'])->id :
            null;
        $config['employeeID'] = (int)$_POST['employee'];

        $this->model->bookAppointment($config);
    }

    /**
     * @param $request
     */
    private function validateBooking($request)
    {
        if ($this->validateBookingParameters($request)) {
            header('Location: ' . URL . '/error');
        }

        try {
            (new DateTime("{$request['month']}-{$request['day']}-{$request['year']}"))->format('n-j-Y');
        } catch (Exception $exception) {
            $view = VIEWS_PATH . 'booking.php';

            $data = $this->initBookingFormRenderData();
            $data['error'] = 'Chosen date is not exist';

            $this->view($view, $data);
        }
    }

    private function validateBookingParameters($parameters)
    {
        return (
            !ctype_digit($parameters['employee']) ||

            !ctype_digit($parameters['start-hour']) ||
            !ctype_digit($parameters['end-hour']) ||
            !ctype_digit($parameters['start-minute']) ||
            !ctype_digit($parameters['end-minute']) ||

            strlen($parameters['notes']) >= self::MY_SQL_TEXT_FIELD_SIZE ||

            $parameters['start-time-format'] !== 'AM' &&
            $parameters['start-time-format'] !== 'PM' ||
            $parameters['end-time-format'] !== 'AM' &&
            $parameters['end-time-format'] !== 'PM' ||

            $parameters['recurring'] !== 'true' && $parameters['recurring'] !== 'false' ||

            $parameters['recurring'] === 'true' &&
            in_array($parameters['recurring-type'], (new RecurringType())->getRecurringTypeNames())
        );
    }

    private function initBookingFormRenderData()
    {
        $data['months'] = $this->getMonths();
        $data['days'] = $this->generateDays();
        $data['years'] = $this->generateYears();
        $data['time'] = $this->generateTime();
        $data['employees'] = (new Employee())->getAllEmployees();

        return $data;
    }

    private function generateYears()
    {
        $currentYear = (int)(new DateTime())->format('Y');
        $years = [];

        for ($year = $currentYear; $year <= $currentYear + self::YEARS_PERIOD; $year++) {
            $years[] = $year;
        }

        return $years;
    }

    private function generateDays()
    {
        $days = [];

        for ($day = 1; $day <= 31; $day++) {
            $days[] = $day;
        }

        return $days;
    }

    private function getMonths()
    {
        return [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
    }

    private function generateTime()
    {
        $time = [];

        for ($hour = 0; $hour <= 24; $hour++) {
            $time['hours'][] = $hour;
        }

        for ($minute = 0; $minute <= 60; $minute++) {
            $time['minutes'][] = $minute;
        }

        return $time;
    }
}
