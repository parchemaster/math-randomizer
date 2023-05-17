<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../QuestionsController.php';


$QC = new QuestionsController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $teacher_email = json_decode($_POST['teacher_id']);
    $teacher_id = $QC->getTeacherId($teacher_email);
    $array = json_decode($_POST['name']);
    $array2 = json_decode($_POST['start']);
    $array3 = json_decode($_POST['end']);
    $array4 = json_decode($_POST['points']);
    $array5 = json_decode($_POST['values']);
    $test_id = $QC->createTest($teacher_id, $array, $array2, $array3, $array4);
    $QC->updateQuestionWithTestId($array5, $test_id);
    
    
   
} 
