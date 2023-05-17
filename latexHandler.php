<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'controller/Controller.php';


$CC = new Controller();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $testId = $_GET['id'];


    echo json_encode($CC->getLatex($_GET['id']));
}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $testId = $_GET['id'];
    echo json_encode($_GET['id']);
}

