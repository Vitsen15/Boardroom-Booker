<?php

namespace Controller;

use Core\Application;
use Core\Controller;
use Core\Exceptions\InvalidDateException;
use Core\Traits\DateValidation;
use Core\Traits\Notificator;
use DateTime;
use Exception;
use Model\Appointment;
use Model\Employee;

class AppointmentController extends Controller
{
    use DateValidation, Notificator;

    const MY_SQL_TEXT_FIELD_SIZE = 65535;

    /**
     * @param int $appointmentDateID
     */
    public function index($appointmentDateID)
    {
        Application::getInstance()->redirectUnauthorized();

        $this->startSession();

        $_SESSION['appointmentDateID'] = $appointmentDateID;
        session_write_close();

        $view = VIEWS_PATH . 'appointment.php';
        $data = $this->initChangingFormRenderData();

        $this->view($view, $data, false);
    }

    public function change()
    {
        switch ($_POST['action']) {
            case 'update':
                $this->updateAppointment($_POST);
                echo "<script>window.close();</script>";
                break;
            case 'delete':
                $this->deleteAppointment($_POST);
                $this->appointmentDeletingNotification($_POST);
                break;
        }
    }

    protected function deleteAppointment($request)
    {
        $this->startSession();

        $appointmentDateID = $_SESSION['appointmentDateID'];
        $this->model = new Appointment();

        if (isset($request['apply-for-all'])) {
            $this->model->softDeleteAppointmentDatesCoincidingByAppointmentID($appointmentDateID);
        } else {
            $this->model->softDeleteAppointmentDate($appointmentDateID);
        }
    }

    protected function updateAppointment($request)
    {
        $this->startSession();
        $this->validateUpdating($request);

        $appointmentDateID = $_SESSION['appointmentDateID'];
        $this->model = new Appointment();
        $appointmentDate = $this->model->getAppointmentDateByID($appointmentDateID);

        $newStartTime = new DateTime("{$appointmentDate->date} {$request['start-time']}");
        $newEndTime = new DateTime("{$appointmentDate->date} {$request['end-time']}");

        if (isset($request['apply-for-all'])) {
            $this->model->updateAllAppointmentDatesOfAppointment(
                $appointmentDate->appointment_id,
                $newStartTime,
                $newEndTime,
                $request['notes'],
                (int)$request['employee-id']
            );
        } else {
            $this->model->updateSingleAppointmentDate(
                $appointmentDateID,
                $newStartTime,
                $newEndTime,
                $request['notes'],
                (int)$request['employee-id']
            );
        }
    }

    protected function startSession()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param array $request
     */
    protected function validateUpdating($request)
    {
        if (!$this->validateUpdatingParameters($request)) {
            header('Location: ' . URL . '/error');
        }

        $this->validateUpdatingDates($request);
    }

    /**
     * @param array $request
     */
    protected function validateUpdatingDates($request)
    {
        $this->startSession();
        $this->model = new Appointment();

        $view = VIEWS_PATH . 'appointment.php';
        $viewData = $this->initChangingFormRenderData();

        $appointmentDateID = $_SESSION['appointmentDateID'];
        $appointmentDate = $this->model->getAppointmentDateByID($appointmentDateID);
        try {
            $newStartTime = new DateTime("{$appointmentDate->date} {$request['start-time']}");
            $newEndTime = new DateTime("{$appointmentDate->date} {$request['end-time']}");

            try {
                $this->checkTimeSequence($newStartTime, $newEndTime);
                $this->checkAppointmentDuration($newStartTime, $newEndTime);
                $this->checkAppointmentTimeIntersectionExceptItself(
                    $newStartTime,
                    $newEndTime,
                    $appointmentDate->appointment_id
                );

            } catch (InvalidDateException $exception) {
                $viewData['error'] = $exception->getMessage();

                $this->view($view, $viewData, false);
            }
        } catch (Exception $exception) {
            $data['error'] = 'Time is not valid, please enter a valid time.';

            $this->view($view, $viewData, false);
        }
    }

    /**
     * @param array $request
     * @return boolean
     */
    protected function validateUpdatingParameters($request)
    {
        return (
            (new Employee())->findEmployeeByID((int)$request['employee-id']) ||
            $request['notes'] < self::MY_SQL_TEXT_FIELD_SIZE
        );
    }

    protected function initChangingFormRenderData()
    {
        $this->startSession();
        $this->model = new Appointment();

        $appointmentDateID = $_SESSION['appointmentDateID'];
        $appointmentDate = $this->model->getAppointmentDateByID($appointmentDateID);
        $appointment = $this->model->getAppointmentByID($appointmentDate->appointment_id);

        $data['startTime'] = (new DateTime($appointmentDate->start_time))->format('g:i A');
        $data['endTime'] = (new DateTime($appointmentDate->end_time))->format('g:i A');
        $data['creationTime'] = $appointment->created_at;
        $data['recurring'] = isset($appointment->recurring_type_id) ? $appointment->recurring_type_id : null;

        $data['employees'] = (new Employee())->getAllEmployees();
        $data['notes'] = $appointmentDate->notes;

        return $data;
    }
}