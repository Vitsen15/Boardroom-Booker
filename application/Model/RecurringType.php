<?php

namespace Model;


use Core\Model;
use PDO;

class RecurringType extends Model
{
    /**
     * @param string $name
     * @return \stdClass | array
     */
    public function getRecurringTypeByName($name)
    {
        $sql = /** @lang MySQL */
            "select * from recurring_type where recurring_type.name = :type_name;";
        $query = $this->db->prepare($sql);
        $query->bindParam(':type_name', $name, PDO::PARAM_STR);
        $query->execute();

        return $query->fetch();
    }

    public function getRecurringTypeNames()
    {
        $sql = /** @lang MySQL */
            "select * from recurring_type";
        $query = $this->db->prepare($sql);
        $query->execute();

        $recurringTypes = $query->fetchAll();
        $recurringTypeNames = [];

        foreach ($recurringTypes as $recurringType) {
            $recurringTypeNames[] = $recurringType->name;
        }

        return $recurringTypeNames;
    }
}