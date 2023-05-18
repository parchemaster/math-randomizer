<?php
session_start();
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../auth/login.php");
    exit();
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "student") {
    header("Location: ../student/student_index.php");
    exit();
}

?>
<!doctype html>
<html>

<head>
    <title>teacher</title>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js">
    </script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js">

    </script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Hello,
            <?php echo $_SESSION["fullname"] ?>
        </a>
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
                    <a class="nav-link" href="assignTestToStudent.php">Assign test to a student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="theGuide.php">How to use Teacher page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../auth/logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="d-flex justify-content-center">
        <div class="d-flex" style="width: 50%; margin-top: 20px;">
            <div class="container">
            <form method="post">
                
                <h3>Available features and how to use them:</h3>
                <br>
                <ul>
                    <ol>
                        <li><b>Create a question</b></li>
                        <br>
                        <p><b>How</b>: open page "Create Question", set name of the question, load LaTeX file, click on
                            "Submit". Question will be created</p>
                        <li><b>Create a test</b></li>
                        <br>
                        <p><b>How</b>: open page "Create Test", set start date of test, set deadline for this test, set
                            name of the test,
                            set total points for this test, choose all questions you want to assign for this test. click
                            on "Submit". Test will be created</p>
                        <li><b>Assign test to a student</b></li>
                        <br>
                        <p><b>How</b>: open page "Assign test to a student", click on name of the student you want to
                            assign a test,
                            on the next page select tests you want to assign,
                            click on "Submit". Now student will see assigned tests for them.</p>
                        <li><b>Filter and export to CSV table with students.</b></li>
                        <br>
                        <p><b>How</b>: open teacher home page, then you can filter students by all available categories.
                        <p>To export table in a CSV format: click on "Export to CSV" and you will download table in a
                            CSV format.</p>
                        </p>


                    </ol>
                </ul>
                <input type="text" name="name" class="form-control" id="InputName" style="display:none">
                    <button type="submit" class="btn btn-primary">Export to Pdf</button>
                </form>


            </div>
        </div>
    </div>


</body>

</html>
<?php
require_once('../pdf/pdf.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (!empty($_POST)) {
    $pdf = new Pdf();

    $file_name = 'Test_Results.pdf';
    
    $html = file_get_contents("theGuide.php");
    $pdf->loadHtml($html);

    $pdf->render();
    ob_end_clean(); 
    $pdf->stream($file_name, array("Attachment" => false));
}
}
exit(0);
?>