<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("Location: ../auth/login.php");
    exit;
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "student"){
    header("Location: ../student/student_index.php");
    exit;
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
    <a class="navbar-brand" href="#">Hello </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="../index.html">index</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="">Teacher index</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>

<br>

<h1>LaTeX files</h1>
<table>
    <thead>
    <tr>
        <th>File Name</th>
        <th>Points</th>
        <th>Date Opened</th>
        <th>Date Closed</th>
        <th>Allow Generation</th>
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
        <td><input type="checkbox"></td>
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

<br>

<form id="file-form">
    <div class="form-group">
        <label for="latex-file">Upload LaTeX file:</label>
        <input type="file" class="form-control-file" id="latex-file" accept=".tex">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>

<br>

<h1>Students info</h1>

<table>
    <thead>
    <tr>
        <th>Name</th>
        <th>ID</th>
        <th>Generated Tasks</th>
        <th>Submitted Tasks</th>
        <th>Points</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>John Smith</td>
        <td>12345</td>
        <td>10</td>
        <td>8</td>
        <td>80</td>
    </tr>
    <tr>
        <td>Jane Doe</td>
        <td>67890</td>
        <td>5</td>
        <td>5</td>
        <td>50</td>
    </tr>
    <tr>
        <td>Bob Johnson</td>
        <td>24680</td>
        <td>12</td>
        <td>10</td>
        <td>100</td>
    </tr>
    </tbody>
</table>
<button onclick="exportToCSV()">Export to CSV</button>


<script src="teacher.js"></script>
</body>
</html>