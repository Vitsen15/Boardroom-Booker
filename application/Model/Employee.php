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

    /**
     * @param $id
     * @return \stdClass | boolean
     */
    public function deleteEmployeeByID($id)
    {
        $sql = /** @lang MySQL */
            "delete from employee where employee.id = :id";
        $query = $this->db->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
    }

    /**
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     */
    public function createEmployee($firstName, $lastName, $email)
    {
        $sql = /** @lang MySQL */
            "insert into employee 
              set employee.first_name = :first_name,
                  employee.last_name = :last_name,
                  employee.email = :email";
        $query = $this->db->prepare($sql);
        $query->bindParam(':first_name', $firstName, PDO::PARAM_STR);
        $query->bindParam(':last_name', $lastName, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
    }
}