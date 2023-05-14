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
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="student_styles.css">

    <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/pricing/">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
    crossorigin="anonymous"></script>

    <!-- MathJax -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
    </script>


<script src="../script/main.js" async></script>

<meta name="theme-color" content="#712cf9">
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

            <div class="col">
            <div class="card mb-4 rounded-3 shadow-sm text-center">
                <div class="card-header py-3">
                    <h4 class="my-0 fw-normal">Topic name or something else</h4>
                </div>
                <div class="card-body">
                    <div id="tex">
                    <p id="randomLatex">
                    </p>
                    </div>

                    <button type="button" onclick="getRandomLatexFile()" class="w-100 btn btn-lg btn-primary">Get
                    started</button>
                    <div class="iframe-wrapper">
                    <iframe src="../equation-editor/equation-editor.html"></iframe>
                    </div>
                </div>
                </div>
            </div>
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

<style>
      .iframe-wrapper {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%;
      }

      .iframe-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
      }
    </style>

<iframe id="nr-ext-rsicon"
    style="position: absolute; display: none; width: 50px; height: 50px; z-index: 2147483647; border-style: none; background: transparent;"></iframe>
</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

<script src="student.js"></script>
</body>
</html>