<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once 'controller/Controller.php';


$CC = new Controller();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // TODO create a logic for sending index
    echo json_encode($CC->getLatex('8'));
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //insearting new latex file 
    $CC -> insertLatex("aaaa");
}

