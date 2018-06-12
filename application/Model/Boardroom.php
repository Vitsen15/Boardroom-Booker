<?php

namespace Model;

use Core\Model;
use PDO;

class Boardroom extends Model
{
    public function getBoardrooms()
    {
        $sql = /** @lang MySQL */
            "select * from boardroom";

        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }

    public function getBoardroomByID($id)
    {
        $sql = /** @lang MySQL */
            "select * from boardroom where id = :id";

        $query = $this->db->prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_STR);
        $query->execute();

        return $query->fetch();
    }
}