<?php

namespace Core\Traits;

use Core\Exceptions\InvalidDateException;
use DateTime;
use Model\Appointment;

trait DateValidation
{
    /**
     * Checks if given date has passed
     *
     * @param DateTime $date
     * @return void
     * @throws InvalidDateException
     */
    public function checkIfDatePassed($date)
    {
        $currentDate = new DateTime();

        if ($date < $currentDate) {
            throw new InvalidDateException('Chosen date is already passed');
        }
    }

    /**
     * Checks right time sequence
     *
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @return void
     * @throws InvalidDateException
     */
    public function checkTimeSequence($startTime, $endTime)
    {
        if ($startTime > $endTime) {
            throw new InvalidDateException('Start time can\'t be later end time');
        }
    }

    /**
     * Checks appointment duration
     *
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @throws InvalidDateException
     */
    public function checkAppointmentDuration($startTime, $endTime)
    {
        $appointmentDuration = $startTime->diff($endTime)->h;

        if ($appointmentDuration === 0 || $appointmentDuration > MAX_APPOINTMENT_DURATION) {
            throw new InvalidDateException('Appointment duration cant be 0 or more than ' . MAX_APPOINTMENT_DURATION . ' hours (duration defined in app config)');
        }
    }

    /**
     * Check if appointment intersect with other appointments
     *
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @throws InvalidDateException
     */
    public function checkAppointmentTimeIntersection($startTime, $endTime)
    {
        $appointmentModel = new Appointment();
        $intersection = $appointmentModel->checkAppointmentTimeIntersection($startTime, $endTime);

        if ($intersection) {
            throw new InvalidDateException('Chosen time is intersected with other appointments time');
        }
    }

    /**
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @param int $appointmentID
     * @throws InvalidDateException
     */
    public function checkAppointmentTimeIntersectionExceptItself($startTime, $endTime, $appointmentID)
    {
        $appointmentModel = new Appointment();
        $intersection = $appointmentModel->checkAppointmentTimeIntersectionExceptItself(
            $startTime,
            $endTime,
            $appointmentID
        );

        if ($intersection) {
            throw new InvalidDateException('Chosen time is intersected with other appointments time');
        }
    }
}