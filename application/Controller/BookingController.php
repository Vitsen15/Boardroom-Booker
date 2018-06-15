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
        }

        $_SESSION['boardroomID'] = $boardroomID;
        session_write_close();

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

        $config = $this->createBookingConfig($_POST);

        $this->model->bookAppointment($config);
    }

    /**
     * @param $request
     */
    protected function validateBooking($request)
    {
        if (!$this->validateBookingParameters($request)) {
            header('Location: ' . URL . '/error');
        }

        $this->validateBookingDate($request);
    }

    /**
     * @param array $parameters - $_POST
     * @return bool
     */
    protected function validateBookingParameters($parameters)
    {
        return (
            ctype_digit($parameters['employee']) ||

            ctype_digit($parameters['start-hour']) ||
            ctype_digit($parameters['end-hour']) ||
            ctype_digit($parameters['start-minute']) ||
            ctype_digit($parameters['end-minute']) ||

            strlen($parameters['notes']) <= self::MY_SQL_TEXT_FIELD_SIZE ||

            $parameters['start-time-format'] === 'AM' &&
            $parameters['start-time-format'] === 'PM' ||
            $parameters['end-time-format'] === 'AM' &&
            $parameters['end-time-format'] === 'PM' ||

            $parameters['recurring'] === 'true' && $parameters['recurring'] === 'false' ||

            $parameters['recurring'] === 'true' &&
            in_array($parameters['recurring-type'], (new RecurringType())->getRecurringTypeNames()) ||

            $parameters['recurring-duration'] < RECURRING_LIMIT
        );
    }

    /**
     * Validates booking date, generates errors text and renders it in view
     *
     * @param array $request - $_POST
     */
    protected function validateBookingDate($request)
    {
        $view = VIEWS_PATH . 'booking.php';
        $viewData = $this->initBookingFormRenderData();

        $this->model = new Appointment();

        try {
            $currentDate = new DateTime();
            $bookingDate = new DateTime("{$request['year']}-{$request['month']}-{$request['day']}");

            $appointmentStartTime = DateTime::createFromFormat(
                'Y-n-d g:i A',
                "{$request['year']}-{$request['month']}-{$request['day']} {$request['start-hour']}:{$request['start-minute']} {$request['start-time-format']}"
            );
            $appointmentEndTime = DateTime::createFromFormat(
                'Y-n-d g:i A',
                "{$request['year']}-{$request['month']}-{$request['day']} {$request['end-hour']}:{$request['end-minute']} {$request['end-time-format']}"
            );

            if ($bookingDate < $currentDate) {
                $viewData['error'] = 'Chosen date is already passed';

                $this->view($view, $viewData);
            }

            $appointmentDuration = $appointmentStartTime->diff($appointmentEndTime)->h;

            if ($appointmentDuration === 0 || $appointmentDuration > MAX_APPOINTMENT_DURATION) {
                $viewData['error'] = 'Appointment duration cant be 0 or more than ' . MAX_APPOINTMENT_DURATION . ' hours (duration defined in app config)';

                $this->view($view, $viewData);
            }

            $intersection = $this->model->checkAppointmentTimeIntersection($appointmentStartTime, $appointmentEndTime);

            if ($intersection) {
                $viewData['error'] = 'Chosen time is intersected with other appointments time';

                $this->view($view, $viewData);
            }
        } catch (Exception $exception) {
            $viewData['error'] = 'Chosen date not exist';

            $this->view($view, $viewData);
        }
    }

    protected function createBookingConfig($request)
    {
        $config['boardroomID'] = $_SESSION['boardroomID'];

        $config['recurringTypeID'] = $request['recurring'] === 'true' ?
            (new RecurringType())->getRecurringTypeByName($request['recurring-type'])->id :
            null;
        $config['recurring'] = $request['recurring'];
        $config['recurringType'] = $request['recurring-type'];
        $config['recurringDuration'] = $request['recurring-duration'];

        $config['employeeID'] = (int)$request['employee'];
        $config['notes'] = $request['notes'];

        $config['appointmentDate'] = new DateTime("{$request['year']}-{$request['month']}-{$request['day']}");
        $config['appointmentStartTime'] = DateTime::createFromFormat(
            'Y-n-d g:i A',
            "{$request['year']}-{$request['month']}-{$request['day']} {$request['start-hour']}:{$request['start-minute']} {$request['start-time-format']}"
        );

        $config['appointmentEndTime'] = DateTime::createFromFormat(
            'Y-n-d g:i A',
            "{$request['year']}-{$request['month']}-{$request['day']} {$request['end-hour']}:{$request['end-minute']} {$request['end-time-format']}"
        );

        return $config;
    }

    protected function initBookingFormRenderData()
    {
        $data['months'] = $this->getMonths();
        $data['days'] = $this->generateDays();
        $data['years'] = $this->generateYears();
        $data['time'] = $this->generateTime();
        $data['employees'] = (new Employee())->getAllEmployees();

        return $data;
    }

    protected function generateYears()
    {
        $currentYear = (int)(new DateTime())->format('Y');
        $years = [];

        for ($year = $currentYear; $year <= $currentYear + self::YEARS_PERIOD; $year++) {
            $years[] = $year;
        }

        return $years;
    }

    protected function generateDays()
    {
        $days = [];

        for ($day = 1; $day <= 31; $day++) {
            $days[] = $day;
        }

        return $days;
    }

    protected function getMonths()
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

    protected function generateTime()
    {
        $time = [];

        for ($hour = 1; $hour <= 12; $hour++) {
            $time['hours'][] = $hour;
        }

        for ($minute = 0; $minute <= 60; $minute++) {
            $time['minutes'][] = str_pad($minute, 2, '0', STR_PAD_LEFT);
        }

        return $time;
    }
}
