<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("database/Database.php");

class Controller
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function insertLatex($file): int
    {
        $stmt = $this->conn->prepare("INSERT INTO latex (file) VALUES (?)");
        $stmt->execute([$file]);
        return (int)$this->conn->lastInsertId();
    }

    public function getLatex(int $id): mixed {
        $stmt = $this->conn->prepare("SELECT file
                                           FROM latex
                                           WHERE id=:id");

        $stmt->bindParam(":id", $id, PDO::PARAM_STR);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }
}