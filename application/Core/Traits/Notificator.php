<?php

namespace Core\Traits;

use DateTime;

trait Notificator
{
    /**
     * Generates alert with message about creating appointment
     *
     * @param DateTime $startTime
     * @param DateTime $endTime
     * @param string $notes
     * @param string $redirect
     */
    public function appointmentCreationNotification($startTime, $endTime, $notes, $redirect)
    {
        $startTime = $startTime->format('h:i');
        $endTime = $endTime->format('h:i');

        echo "<script>
                alert(
                    'The event : {$startTime} - {$endTime} has been added. \\n ' +
                    'The text for this event is : {$notes}'
                );
                window.location = '{$redirect}'
              </script>";
    }

    /**
     * Generates alert with message about creating deleting
     *
     * @param array $request
     */
    public function appointmentDeletingNotification($request)
    {
        echo "<script>
                alert(
                    'The event : {$request['start-time']} - {$request['end-time']} has been deleted. \\n '
                );
                window.close();
              </script>";
    }

    public function employeeCreatingNotification()
    {

    }
}
