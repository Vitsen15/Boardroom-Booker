<?php
/**
 * Created by PhpStorm.
 * User: vitsen
 * Date: 6/13/18
 * Time: 11:23 PM
 */

namespace Model;

use Core\Model;

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
}