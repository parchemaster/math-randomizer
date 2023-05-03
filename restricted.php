<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: auth/login.php");
    exit;
}
?>

<!doctype html>
<html>
<head>
    <title>restricted</title>
</head>
<body>

<h1>restricted page</h1>

</body>
</html>