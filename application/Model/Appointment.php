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
        $config['appointmentID'] = $appointmentID;

        if ($config['recurring'] === 'true') {
            $recurringDates = $this->calculateAppointmentRecurring(
                $config['appointmentStartTime'],
                $config['appointmentEndTime'],
                $config['recurringType'],
                $config['recurringDuration']
            );

            foreach ($recurringDates as $recurringDate) {
                $this->createAppointmentDate(
                    $config,
                    $recurringDate['date'],
                    $recurringDate['startTime'],
                    $recurringDate['endTime']
                );
            }
        } elseif ($config['recurring'] === 'false') {
            $date = $config['appointmentDate']->format('Y-m-d G:i:s');
            $startTime = $config['appointmentStartTime']->format('Y-m-d G:i:s');
            $endTime = $config['appointmentEndTime']->format('Y-m-d G:i:s');

            $this->createAppointmentDate($config, $date, $startTime, $endTime);
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
     * Calculates appointment recurring based on recurring type
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
                    $recurringDates = $this->generateRecurringDates(
                        $startDate,
                        $endDate,
                        $recurringDuration,
                        'weeks',
                        1
                    );
                } else {
                    $recurringDates = false;
                }
                break;
            case 'bi-weekly':
                if (!$this->checkAppointmentTimeIntersection($startDate, $endDate)) {
                    $recurringDuration = $recurringDuration % 2 !== 0 ?
                        $recurringDuration - 1 :
                        $recurringDuration;

                    $recurringDates = $this->generateRecurringDates(
                        $startDate,
                        $endDate,
                        $recurringDuration,
                        'weeks',
                        2
                    );
                } else {
                    $recurringDates = false;
                }
                break;

            case 'monthly':
                if (!$this->checkAppointmentTimeIntersection($startDate, $endDate)) {
                    $recurringDates = $this->generateRecurringDates(
                        $startDate,
                        $endDate,
                        $recurringDuration,
                        'months',
                        1
                    );
                } else {
                    $recurringDates = false;
                }
                break;
        }

        return $recurringDates;
    }

    /**
     * Generates recurring dates
     *
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @param int $recurringCount
     * @param string $DatetimeOffset
     * @param int $countOffset
     * @return array
     */
    protected function generateRecurringDates($startDate, $endDate, $recurringCount, $DatetimeOffset, $countOffset = 1)
    {
        $recurringDates = [];

        for ($i = 0, $offset = 0; $i < $recurringCount; $i++, $offset += $countOffset) {
            $futureAppointmentStartDate = clone $startDate;
            $futureAppointmentStartDate->modify("+{$offset} {$DatetimeOffset}");

            $futureAppointmentEndDate = clone $endDate;
            $futureAppointmentEndDate->modify("+{$offset} {$DatetimeOffset}");

            $recurringDates[$i]['date'] = $futureAppointmentStartDate->format('Y-m-d');
            $recurringDates[$i]['startTime'] = $futureAppointmentStartDate->format('Y-m-d G:i:s');
            $recurringDates[$i]['endTime'] = $futureAppointmentEndDate->format('Y-m-d G:i:s');
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
     * Creates appointment by boardroom and recurring type (if exist) and returns created appointment id
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


    /**
     * @param array $bookingConfig
     * @param string $date
     * @param string $startTime
     * @param string $endTime
     */
    protected function createAppointmentDate($bookingConfig, $date, $startTime, $endTime)
    {
        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/createAppointmentDate.sql');
        $query = $this->db->prepare($sql);

        $query->bindParam(':appointment_id', $bookingConfig['appointmentID'], PDO::PARAM_INT);
        $query->bindParam(':employee_id', $bookingConfig['employeeID'], PDO::PARAM_INT);
        $query->bindParam(':notes', $bookingConfig['notes'], PDO::PARAM_STR);
        $query->bindParam(':appointment_date', $date, PDO::PARAM_STR);
        $query->bindParam(':appointment_start_time', $startTime, PDO::PARAM_STR);
        $query->bindParam(':appointment_end_time', $endTime, PDO::PARAM_STR);

        $query->execute();
    }
}