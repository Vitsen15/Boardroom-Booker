<?php

namespace Model;

use Core\Model;
use DateTime;
use PDO;

class Appointment extends Model
{
    /**
     * @param array $config
     */
    public function bookAppointment($config)
    {
        $appointmentID = $this->createAppointment($config['boardroomID'], $config['recurringTypeID']);

        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/createAppointmentDate.sql');
        $query = $this->db->prepare($sql);

        if ($config['recurring'] === 'true') {
            $recurringDates = $this->calculateAppointmentRecurring(
                $config['appointmentStartTime'],
                $config['appointmentEndTime'],
                $config['recurringType'],
                $config['recurringDuration']
            );

            foreach ($recurringDates as $recurringDate) {
                $query->bindParam(':appointment_id', $appointmentID, PDO::PARAM_INT);
                $query->bindParam(':employee_id', $config['employeeID'], PDO::PARAM_INT);
                $query->bindParam(':notes', $config['notes'], PDO::PARAM_STR);
                $query->bindParam(':appointment_date', $recurringDate['date'], PDO::PARAM_STR);
                $query->bindParam(':appointment_start_time', $recurringDate['startTime'], PDO::PARAM_STR);
                $query->bindParam(':appointment_end_time', $recurringDate['endTime'], PDO::PARAM_STR);

                $query->execute();
            }
        } elseif ($config['recurring'] === 'false') {
            $date = $config['appointmentDate']->format('Y-m-d G:i:s');
            $startTime = $config['appointmentStartTime']->format('Y-m-d G:i:s');
            $endTime = $config['appointmentEndTime']->format('Y-m-d G:i:s');

            $query->bindParam(':appointment_id', $appointmentID, PDO::PARAM_INT);
            $query->bindParam(':employee_id', $config['employeeID'], PDO::PARAM_INT);
            $query->bindParam(':notes', $config['notes'], PDO::PARAM_STR);
            $query->bindParam(':appointment_date', $date, PDO::PARAM_STR);
            $query->bindParam(':appointment_start_time', $startTime, PDO::PARAM_STR);
            $query->bindParam(':appointment_end_time', $endTime, PDO::PARAM_STR);

            $query->execute();
        }
    }

    /**
     * @param DateTime $date
     * @param int $boardroomID
     * @return array
     */
    public function getAppointmentsByDayAndBoardroom(DateTime $date, $boardroomID)
    {
        $date = $date->format("Y-m-d");

        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/getAppointmentsByDayAndBoardroom.sql');
        $query = $this->db->prepare($sql);

        $query->bindParam(':app_date', $date, PDO::PARAM_STR);
        $query->bindParam(':boardroom_id', $boardroomID, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * @param DateTime $date
     * @return array | bool
     */
    public function findAppointmentsByDate($date)
    {
        $date = $date->format('Y-m-d');

        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/findAppointmentsByDate.sql');
        $query = $this->db->prepare($sql);

        $query->bindParam(':app_date', $date, PDO::PARAM_STR);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * Calculates appointment recurring dates and generates array of
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param string $recurringType
     * @param int $recurringDuration
     * @return array
     */
    public function calculateAppointmentRecurring($startDate, $endDate, $recurringType, $recurringDuration)
    {
        $recurringDates = [];
        switch ($recurringType) {
            case 'weekly':
                if (!$this->checkAppointmentTimeIntersection($startDate, $endDate)) {
                    for ($i = 0; $i < $recurringDuration; $i++) {
                        $futureAppointmentStartDate = clone $startDate;
                        $futureAppointmentStartDate->modify("+{$i} weeks");
                        $futureAppointmentEndDate = clone $endDate;
                        $futureAppointmentEndDate->modify("+{$i} weeks");

                        $recurringDates[$i]['date'] = $futureAppointmentStartDate->format('Y-m-d');
                        $recurringDates[$i]['startTime'] = $futureAppointmentStartDate->format('Y-m-d G:i:s');
                        $recurringDates[$i]['endTime'] = $futureAppointmentEndDate->format('Y-m-d G:i:s');
                    }
                } else {
                    $recurringDates = false;
                }
                break;
            case 'bi-weekly':
                if ($recurringDuration % 2 !== 0) {
                    $recurringDuration = $recurringDuration - 1;
                }

                if (!$this->checkAppointmentTimeIntersection($startDate, $endDate)) {
                    for ($i = 0, $week = 0; $i < $recurringDuration; $i++, $week += 2) {
                        $futureAppointmentStartDate = clone $startDate;
                        $futureAppointmentStartDate->modify("+{$week} weeks");
                        $futureAppointmentEndDate = clone $endDate;
                        $futureAppointmentEndDate->modify("+{$week} weeks");

                        $recurringDates[$i]['date'] = $futureAppointmentStartDate->format('Y-m-d');
                        $recurringDates[$i]['startTime'] = $futureAppointmentStartDate->format('Y-m-d G:i:s');
                        $recurringDates[$i]['endTime'] = $futureAppointmentEndDate->format('Y-m-d G:i:s');
                    }
                } else {
                    $recurringDates = false;
                }
                break;

            case 'monthly':
                if (!$this->checkAppointmentTimeIntersection($startDate, $endDate)) {
                    for ($i = 0; $i < $recurringDuration; $i++) {
                        $futureAppointmentStartDate = clone $startDate;
                        $futureAppointmentStartDate->modify("+{$i} months");
                        $futureAppointmentEndDate = clone $endDate;
                        $futureAppointmentEndDate->modify("+{$i} months");

                        $recurringDates[$i]['date'] = $futureAppointmentStartDate->format('Y-m-d');
                        $recurringDates[$i]['startTime'] = $futureAppointmentStartDate->format('Y-m-d G:i:s');
                        $recurringDates[$i]['endTime'] = $futureAppointmentEndDate->format('Y-m-d G:i:s');
                    }
                } else {
                    $recurringDates = false;
                }
                break;
        }

        return $recurringDates;
    }

    /**
     * Checks if given appointment intersect with other appointments
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     *
     * @return array - of intersected appointment dates | bool
     */
    public function checkAppointmentTimeIntersection($startDate, $endDate)
    {
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/checkAppointmentTimeIntersection.sql');
        $query = $this->db->prepare($sql);

        $startDate = $startDate->format('Y-m-d G:i:s');
        $endDate = $endDate->format('Y-m-d G:i:s');

        $query->bindParam(':start_time', $startDate, PDO::PARAM_STR);
        $query->bindParam(':end_time', $endDate, PDO::PARAM_STR);
        $query->execute();

        return $query->fetch();
    }

    /**
     *
     *
     * @param $boardroomID
     * @param $recurringTypeID
     * @return int Created appointment id
     */
    protected function createAppointment($boardroomID, $recurringTypeID)
    {
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/createAppointment.sql');
        $query = $this->db->prepare($sql);
        $query->bindParam('boardroom_id', $boardroomID, PDO::PARAM_INT);
        $query->bindParam('recurring_type_id', $recurringTypeID, PDO::PARAM_INT);

        $query->execute();
        $query->nextRowset();

        return (int)$query->fetch()->appointment_id;
    }
}