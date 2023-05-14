<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../auth/login.php");
    exit;
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "student") {
    header("Location: ../student/student_index.php");
    exit;
}
try
{
    require_once '../config.php';
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query_students = "SELECT * FROM students";
    $stmt = $db->query($query_students);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($students as $student){
        $stmt = $db->prepare("SELECT student_id FROM students_info WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $student['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result){
        $stmt = $db->prepare("INSERT INTO students_info (student_id) VALUES (:student_id)");
        $stmt->bindParam(':student_id', $student['id'], PDO::PARAM_STR);
        $stmt->execute();
        }
    }
    $query_students_info = "SELECT * FROM students_info";
    $stmt = $db->query($query_students_info);
    $students_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $query_tests = "SELECT * FROM tests";
    $stmt = $db->query($query_tests);
    $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);

}
catch (PDOException $e){
    echo $e->getMessage();
    
}
?>

<!doctype html>
<html>
<head>
    <title>teacher</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Hello, <?php echo $_SESSION["fullname"]?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item">
                <a class="nav-link" href="teacher_index.php">Home</a>
            </li>
        <li class="nav-item">
                <a class="nav-link" href="createQuestion.php">Create Question</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createTest.php">Create Test</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="assignTestToStudent.php">Assign test to a student(TBA)</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="d-flex justify-content-center">
    <div class="d-flex justify-content-center" style="width: 700px; margin-top: 50px;">
        <div class="container">
            <h1>Created Tests</h1>
            <table class="table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Date Opened</th>
                    <th>Date Closed</th>
                    <th>Total Points</th>
                    
                </tr>
                </thead>
                <tbody>
                <?php 
                foreach($tests as $test)
                {
                    
                    echo "<tr><td>" . $test["test_id"] . "</td><td>" . $test["name"] . "</td><td>" . $test["time_opened"]
                     . "</td><td>" . $test["time_closed"] . "</td><td>" . $test["total_points"] . "</td></tr>";
                    
                    
                    
                }
                
                ?>
                </tbody>
            </table>

            <br>

            <br>

            <h1>Students info</h1>

            <table class="table">
                <thead>
                <tr>
                    <th>ID of student</th>
                    <th>Name</th>
                    <th>Generated Tasks</th>
                    <th>Submitted Tasks</th>
                    <th>Points</th>
                </tr>
               
                </thead>
                <tbody>
                <?php 
                foreach($students as $student)
                {
                    
                    echo "<tr><td>" . $student["id"] . "</td><td>" . $student["full_name"] . "</td>";
                    foreach($students_info as $info)
                    {
                        if ($info["student_id"] == $student["id"])
                        {
                            echo "<td>" . $info["tasks_gen"] . "</td><td>" . $info["tasks_sub"] . "</td><td>" . $info["points"] . "</td></tr>";
                        }
                    }
                }
                
                ?>
                </tbody>
            </table>
            <button onclick="exportToCSV()">Export to CSV</button>
        </div>
    </div>
</div>

<script src="teacher.js"></script>
</body>
</html>