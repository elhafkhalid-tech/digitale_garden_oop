<?php

class Database {

    private $host = "localhost";
    private $dbName = "digital_garden";
    private $username = "root";
    private $password = "";

    private $conn;

    public function __construct()
    {
        $this->conn = new PDO("mysql:host=$this->host;dbname=$this->dbName;", $this->username, $this->password);
    }

    public function getConnection(){
        return $this->conn;
    }

}

