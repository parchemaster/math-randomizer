<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION = array();
session_unset();

session_destroy();

header("location: login.php");
exit;