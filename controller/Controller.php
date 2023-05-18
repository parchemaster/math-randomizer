<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once z("../database/Database.php");


//public function getLatex(int $id): mixed {
    // $stmt = $this->conn->prepare("SELECT file FROM quations");
    // $questions = "SELECT * FROM questions WHERE test_id = :testId";
    // $question_stmt = $this->$conn->prepare($questions);
    // $question_stmt->bindParam(':testId', $id);
    // $question_stmt->execute();
    // $question_results = $question_stmt->fetchAll(PDO::FETCH_ASSOC);

//    $stmt = $this->conn->prepare("SELECT * FROM questions WHERE test_id = :testId");
//    $stmt->bindParam(':testId', $id, PDO::PARAM_INT);
//    $stmt->execute();
//    $stmt->setFetchMode(PDO::FETCH_ASSOC);
//    return $stmt->fetch();
//}