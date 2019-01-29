<?php

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 4:59 PM
 */
class GLOBAL_CLASS_Database
{
    private $host = 'localhost';
    private $database = 'facultyassocnew';
    private $username = 'root';
    private $password = '1234';
    public $connection;

    public function __construct()
    {
        if (!isset($this->connection)) {
            $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);
            if (!$this->connection) {
                echo 'Cannot connect to database server';
                exit;
            }
        }
        return $this->connection;
    }
}

?>