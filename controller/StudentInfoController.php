<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once ("../database/Database.php");

class StudentInfoController

{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    // public function updateTestPass(int $testId): mixed {
    //     $studentName = $_SESSION["fullname"];
    //     //update table "student_info" where student_name == $_SESSION["fullname"] add string testId 
    // }

    public function updateTestPass(int $testId): mixed {
        $studentName = $_SESSION["fullname"];
        
        // Assuming you have a database connection established and stored in the $connection variable
        
        // Prepare the update query
        $query = "UPDATE students_info SET passed_tests = CONCAT(passed_tests, :testId, ',') WHERE student_name = :studentName";
        $stmt = $this->conn->prepare($query);
        
        // Bind the parameters
        $stmt->bindValue(':testId', $testId, PDO::PARAM_INT);
        $stmt->bindValue(':studentName', $studentName, PDO::PARAM_STR);
        
        // Execute the query
        $result = $stmt->execute();
        
        return $result;
    }
    

    
    
    
}