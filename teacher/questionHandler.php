<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../QuestionsController.php';


$QC = new QuestionsController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    if(empty($_POST['test_id'])){
    $array = json_decode($_POST['body']);
    $array2 = json_decode($_POST['name']);
    $id=$QC->createQuestion($array, $array2);
    $QC->latexParserToDatabase($array,$id);
    }
    
} 
