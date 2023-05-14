<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../auth/login.php");
    exit;
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "teacher") {
    header("Location: ../teacher/teacher_index.php");
    exit;
}


?>

<!doctype html>
<html>
<head>
    <title>student</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="student_styles.css">
</head>
<body>
<div id="modal" class="modal">
    <div class="modal-content">
        <div class="close">&times;</div>
        <div id="task"></div>
        <button onclick="">submit task</button>
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Hello, <?php echo $_SESSION["fullname"]?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<div class="d-flex justify-content-center">
    <div class="d-flex justify-content-center" style="width: 700px; margin-top: 50px;">
        <div class="container">
            <h1>students page</h1>

            <h3>LaTeX tasks</h3>
            <table>
                <thead>
                <tr>
                    <th>File Name</th>
                    <th>Points</th>
                    <th>Date Opened</th>
                    <th>Date Closed</th>
                    <th>Generate</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>file1.pdf</td>
                    <td>10</td>
                    <td>2023-04-01</td>
                    <td>2023-04-07</td>
                    <td><input type="checkbox" checked></td>
                </tr>
                <tr>
                    <td>file2.docx</td>
                    <td>5</td>
                    <td>2023-04-08</td>
                    <td>2023-04-15</td>
                    <td><input type="checkbox" checked></td>
                </tr>
                <tr>
                    <td>file3.tex</td>
                    <td>15</td>
                    <td>2023-04-16</td>
                    <td>2023-04-23</td>
                    <td><input type="checkbox"></td>
                </tr>
                </tbody>
            </table>
            <button onclick="">Generate</button>
            <br>
            <br>
            <br>

            // after generate //
            <br>
            <br>



            <table>
                <thead>
                <tr>
                    <th>task name</th>
                    <th>Points</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="no-active-task">file1</td>
                    <td>80</td>
                </tr>
                <tr>
                    <td class="active-task">file2 (<- click)</td>
                    <td></td>
                </tr>
                </tbody>
        </div>
    </div>
</div>

<script src="student.js"></script>
</body>
</html>