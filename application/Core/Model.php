<?php

namespace Core;

use PDO;
use PDOException;

class Model
{
    /**
     * @var PDO Database Connection
     */
    protected $db;

    /**
     * Whenever model is created, open a database connection.
     */
    function __construct()
    {
        try {
            self::connect();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * Open the database connection with the credentials from application/Config/db.php
     */
    private function connect()
    {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        $databaseEncoding = "; charset=" . DB_CHARSET;

        $this->db = new PDO(
            DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . $databaseEncoding,
            DB_USER, DB_PASS, $options
        );
    }
}