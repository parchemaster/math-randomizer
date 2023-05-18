<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require ("config.php");
//require_once 'controller/Controller.php';

//$CC = new Controller();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * FROM questions WHERE test_id = :testId");
    $stmt->bindParam(':testId',$_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $test_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//    $testId = $_GET['id'];
    echo json_encode($test_results);
}


