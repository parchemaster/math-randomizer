<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


class Database {

    private $conn;

    public function getConnection(): ?PDO
    {

        $hostname = "localhost";
        $username = "username";
        $password = "password";
        $dbname = "random_math";


        $this->conn = null;
        try
        {
            
            $this->conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        catch(PDOException $exception)
        {
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

