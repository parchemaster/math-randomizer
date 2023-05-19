<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// foreach ($_SESSION as $key => $value) {
//     echo $key . ' => ' . $value . '<br>';
// }

// Check if the user is logged in, if no then redirect him to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../auth/login.php");
    exit;
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "teacher") {
    header("Location: ../teacher/teacher_index.php");
    exit;
}
require_once('../config.php');

try {
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $currentDateTime = date('Y-m-d H:i:s');
    $tests = "SELECT * FROM tests WHERE time_opened <= :currentDateTime AND time_closed >= :currentDateTime";
    $test_stmt = $db->prepare($tests);
    $test_stmt->bindParam(':currentDateTime', $currentDateTime);
    $test_stmt->execute();
    $test_results = $test_stmt->fetchAll(PDO::FETCH_ASSOC);

    // $tests = "SELECT * FROM tests";
    // $test_stmt = $db->query($tests); 
    // $test_results = $test_stmt->fetchAll(PDO::FETCH_ASSOC);

    $studentInfo = "SELECT * FROM students_info WHERE student_id = :studentID";
    $studentInfo_stmt = $db->prepare($studentInfo);
    $studentInfo_stmt->bindParam(':studentID', $_SESSION["student_id"]);
    $studentInfo_stmt->execute();
    // $studentInfo_results = $studentInfo_stmt->fetchAll(PDO::FETCH_ASSOC);
    $studentInfo_results = $studentInfo_stmt->fetch(PDO::FETCH_ASSOC);
    $passed_testsIds = [];
    $assigned_testsIds = [];
    if ($studentInfo_results !== false && $studentInfo_results !== null && $studentInfo_results !== []) {

        if ($studentInfo_results["passed_tests"] !== NULL) {
            $passed_testsId = explode(",", $studentInfo_results["passed_tests"]);
            foreach ($passed_testsId as $number) {
                $passed_testsIds[] = $number;
            }
        }


        if ($studentInfo_results["assigned_tests"] !== NULL) {
            $assigned_tests = json_decode($studentInfo_results["assigned_tests"], true);
            $assigned_tests = json_decode($assigned_tests, true);
            $assigned_testsIds = array();

            foreach ($assigned_tests as $number) {
                $assigned_testsIds[] = $number;
            }
        }
    }


} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<!doctype html>
<html>
<head>
    <title>student</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="student_styles.css">

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/pricing/"> -->

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <!-- MathJax -->
    <script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-http-backend@1.3.2/i18nextHttpBackend.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

    <script src="../lang/i18n.js"></script>
    <script src="../script/main.js" async></script>

    <meta name="theme-color" content="#712cf9">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><span
                    data-i18n="hello_label"></span><?php echo $_SESSION["fullname"] ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="theGuide.php" data-i18n="Guide"></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../auth/logout.php" data-i18n="Logout_label"></a>
                </li>
                <li class="nav-item">
                    <select name="language" id="languageSwitcher"></select>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="d-flex justify-content-center">
    <div class="d-flex justify-content-center" style="width: 700px; margin-top: 50px;">
        <div class="container">
            <h1 data-i18n="Students_page"></h1>

            <div class="table-responsive">
                <table id="example">
                    <thead>
                    <tr>
                        <th scope="col" data-i18n="Tests"></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <?php foreach ($test_results

                        as $result):
                        // var_dump($assigned_testsIds);
                        if (in_array($result['test_id'], $assigned_testsIds) && !in_array($result['test_id'], $passed_testsIds)) {
                            echo '<td><a style="text-decoration: none;" href="../test/test.php?id=' . $result['test_id'] . '">' . $result['name'] . '</a></td>';
                        }
                        ?>

                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>


            <!-- <div class="card-body">
                <div id="tex">
                <p id="randomLatex">
                </p>
                </div>

                <button type="button" onclick="getRandomLatexFile()" class="w-100 btn btn-lg btn-primary">Get
                started</button>
                <div class="iframe-wrapper">
                <iframe src="../equation-editor/equation-editor.html"></iframe>
                </div>
            </div> -->
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
</body>
<grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>

<script src="student.js"></script>
</body>
</html>