<?php

namespace Model;

use Core\Model;
use DateTime;
use PDO;

class Appointment extends Model
{
    public function getAppointmentsByDayAndBoardroom(DateTime $date, $boardroomID)
    {
        $date = $date->format("Y-m-d");

        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'sql/appointment/getAppointmentsByDay.sql');
        $query = $this->db->prepare($sql);

        $query->bindParam(':app_date', $date, PDO::PARAM_STR);
        $query->bindParam(':boardroom_id', $boardroomID, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }
}