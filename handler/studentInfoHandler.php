<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once '../controller/StudentInfoController.php';


$controller = new StudentInfoController();
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $testId = $_GET['testId'];
    $controller->updateTestPass($testId);
}
?>