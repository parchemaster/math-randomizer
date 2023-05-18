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

    $query_questions = "SELECT * FROM questions";
    $stmt = $db->query($query_questions);
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    unset($stmt);


} catch (PDOException $e) {
    echo $e->getMessage();

}

?>
<!doctype html>
<html lang="sk">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create a test</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="test_teacher.js"></script>

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
                <a class="nav-link" href="teacher_index.php" data-i18n="home_label"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="createQuestion.php" data-i18n="Create_Question"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="createTest.php" data-i18n="Create_Test"></a>
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

<div class="container py-5">
    <h1 data-i18n="Create_a_new_test"></h1>
    <form id="file-form">
        <div class="form-group">
            <div class="row">
                <label id="teacher_id" style="display:none">
                    <?php echo $_SESSION["email"] ?>
                </label>
                <div class="col">
                    <div class="form-group">
                        <label for="start" data-i18n="Start_date"></label>
                        <input type="date" class="form-control" id="start" name="start" required>
                    </div>
                    <div class="form-group">
                        <label for="end" data-i18n="Deadline"></label>
                        <input type="date" class="form-control" id="end" name="end" required>
                    </div>
                    <div class="form-group">
                        <label for="test-name" data-i18n="Name_of_Test"></label>
                        <input type="text" class="form-control" id="test-name" name="test-name" required>
                    </div>
                    <div class="form-group">
                        <label for="test-points" data-i18n="Total_Points"></label>
                        <input type="number" class="form-control" id="test-points" name="test-points" min="0"
                               max="100" required>
                    </div>
                    <h1 data-i18n="List_of_available_questions"></h1>

                    <table class="table table-striped table-bordered table-sm">
                        <thead>
                        <tr>
                            <th data-i18n="ID_of_question"></th>
                            <th data-i18n="name_label"></th>
                            <th data-i18n="Include_to_the_test"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($questions as $question) {
                            if (empty($question["test_id"]))
                                echo "<tr><td>" . $question["id"] . "</td><td>" . $question["name"] . "</td><td><input type='checkbox' name = 'question' value = " . $question["id"] . "></td></tr>";

                        }

                        ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" data-i18n="Submit_button"></button>
    </form>


</div>

</body>

</html>