<?php 
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && isset($_SESSION["user_type"]) && $_SESSION["loggedin"] === true) {
  if ($_SESSION["user_type"] == "student") {
      header("Location: student/student_index.php");
      exit;
  } else if ($_SESSION["user_type"] == "teacher") {
      header("Location: teacher/teacher_index.php");
      exit;
  }
}
else {
  header("Location: auth/login.php");
  exit;
}
?>