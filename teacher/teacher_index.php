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
try {
    require_once '../config.php';
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query_students = "SELECT * FROM students";
    $stmt = $db->query($query_students);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $student) {
        $stmt = $db->prepare("SELECT student_id FROM students_info WHERE student_id = :student_id");
        $stmt->bindParam(':student_id', $student['id']);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
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

    require_once '../QuestionsController.php';


    $QC = new QuestionsController();

    $teacher_id = $QC->getTeacherId($_SESSION["email"]);


} catch (PDOException $e) {
    echo $e->getMessage();

}
?>

<!doctype html>
<html>

<head>
    <title>teacher</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src= "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-http-backend@1.3.2/i18nextHttpBackend.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>
    <script src="../lang/i18n.js"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><span data-i18n="hello_label"></span><?php echo $_SESSION["fullname"] ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link active" href="teacher_index.php" data-i18n="home_label"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createQuestion.php" data-i18n="Create_Question"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createTest.php" data-i18n="Create_Test"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="assignTestToStudent.php" data-i18n="Assign_test">Assign test to a student</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="theGuide.php" data-i18n="How_to_use_Teacher">How to use Teacher page</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../auth/logout.php" data-i18n="Logout_label">Logout</a>
            </li>
            <li class="nav-item">
                <select name="language" id="languageSwitcher"></select>
            </li>
        </ul>
    </div>
</nav>

<div class="d-flex justify-content-center">
    <div class="d-flex justify-content-center" style="width: 700px; margin-top: 50px;">
        <div class="container">
            <h1 data-i18n="Created_Tests">Created Tests</h1>
            <table class="table table-striped table-bordered table-sm">
                <thead>
                <tr>
                    <th data-i18n="id_label"></th>
                    <th data-i18n="name_label"></th>
                    <th data-i18n="opened_label"></th>
                    <th data-i18n="closed_label"></th>
                    <th data-i18n="total_points"></th>

                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($tests as $test) {
                    if ($test["teacher_id"] == $teacher_id) {

                        echo "<tr><td>" . $test["test_id"] . "</td><td>" . $test["name"] . "</td><td>" . $test["time_opened"]
                            . "</td><td>" . $test["time_closed"] . "</td><td>" . $test["total_points"] . "</td></tr>";

                    }

                }

                ?>
                </tbody>
            </table>

            <br>

            <br>

            <h1 data-i18n="Students_info"></h1>

            <table id="table" class="table table-striped table-bordered table-sm">
                <thead>
                <tr>
                    <th data-i18n="ID_of_student"></th>
                    <th data-i18n="name_label"></th>
                    <th data-i18n="Generated_Tasks"></th>
                    <th data-i18n="Submitted_Tasks"></th>
                    <th data-i18n="Points_label"></th>
                </tr>

                </thead>
                <tbody>
                <?php
                foreach ($students as $student) {

                    echo "<tr><td>" . $student["id"] . "</td><td>" . $student["full_name"] . "</td>";
                    foreach ($students_info as $info) {
                        if ($info["student_id"] == $student["id"]) {
                            echo "<td>" . $info["tasks_gen"] . "</td><td>" . $info["tasks_sub"] . "</td><td>" . $info["points"] . "</td></tr>";
                        }
                    }
                }

                ?>
                </tbody>
            </table>
            <button onclick="exportToCSV()" data-i18n="ExportCSV_button">Export to CSV</button>
        </div>
    </div>
</div>

<script src="teacher.js"></script>
</body>

</html>