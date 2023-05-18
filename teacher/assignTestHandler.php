<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../QuestionsController.php';


$QC = new QuestionsController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $student_id = json_decode($_POST['student_id']);
 
    $array1 = json_encode($_POST['values']);
    $QC->assignTest($student_id, $array1);
    
    
   
} 
