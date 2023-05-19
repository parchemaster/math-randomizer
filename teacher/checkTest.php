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
    $query = "SELECT * FROM students where id=?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);
    $query_students = "SELECT * FROM student_succes where student_id=?";

    $stmt = $db->prepare($query_students);
    $stmt->execute([$_GET['id']]);
    $info = $stmt->fetchAll(PDO::FETCH_ASSOC);
    /*$section = "OA423";
    $answer = "asdasd";
    $result = "1";
    $student_id = "2";
    $points = "50";
    $stmt = $db->prepare("INSERT INTO student_succes (section, answer, result, student_id, points) VALUES (:section, :answer, :result, :student_id, :points)");
        $stmt->bindParam(':section', $section, PDO::PARAM_STR);
        $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
        $stmt->bindParam(':result', $result);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':points', $points);
        $stmt->execute();*/

        
        
    //$query = "SELECT assigned_tests FROM students_info where student_id = 2";
    //$stmt = $db->query($query);
    //$gg = $stmt->fetch(PDO::FETCH_ASSOC);
    // $x = json_decode($gg['assigned_tests']);
    //$query = "SELECT name FROM tests where test_id = ?";
    //$stmt = $db->prepare($query);
    //$stmt->execute($x[0]);
    // $z = $stmt->fetch(PDO::FETCH_ASSOC);
    /*preg_match_all('/[0-9]+/', $x, $matches);
    foreach($matches as $m){
    foreach($m as $z){
    echo $z;
    }
    }*/

    // echo $x[2];
   
} catch (PDOException $e) {
    echo $e->getMessage();

}
?>
<!doctype html>
<html>

<head>
    <title>teacher</title>
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
                <!-- Bootstrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>

    <script src="assignTest.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
    <a class="navbar-brand" href="teacher_index.php">Hello, <?php echo $_SESSION["fullname"] ?></a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <a class="nav-link" href="assignTestToStudent.php">Assign test to a student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="theGuide.php">How to use Teacher page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../auth/logout.php">Logout</a>
                </li>
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
                <label id="student_id" style="display:none">
                    
                </label>
                <h1>Student: <?php echo $student["full_name"] ?></h1>
                <br>

                <br>

                <table class="table table-striped table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Answer</th>
                            <th>Result</th>
                            <th>Points</th>
                            <th>Example number</th>
                            <th>Question ID</th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php
                        foreach($info as $i)
                        {
                            if(!empty($i))
                            {
                            if ($student["id"] == $i["student_id"])
                            {
                        echo "<tr><td>" . $i["answer"] . "</td><td>" . $i["result"] . "</td><td>" . $i["points"] . "</td><td>" . $i["example_number"] ."</td><td>". $i["question_id"] . "</td></tr>";
                            }
                        }
                        }
                        ?>
                    </tbody>
                </table>
                <br>

                <br>
                

            </div>
        </div>
    </div>


</body>

</html>