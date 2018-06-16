<?php
/**
 * Created by PhpStorm.
 * User: vitsen
 * Date: 6/13/18
 * Time: 11:23 PM
 */

namespace Model;

use Core\Model;
use PDO;

class Employee extends Model
{
    public function getAllEmployees()
    {
        $sql = /** @lang MySQL */
            "select * from employee";
        $query = $this->db->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }


    /**
     * @param $id
     * @return \stdClass | boolean
     */
    public function findEmployeeByID($id)
    {
        $sql = /** @lang MySQL */
            "select * from employee where employee.id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();

        return $query->fetch();
    }
}