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
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> -->
           <!-- Bootstrap -->
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
            crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/i18next-http-backend@1.3.2/i18nextHttpBackend.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>
        <script src="../lang/i18n.js"></script>
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
                <li class="nav-item">
                <select name="language" id="languageSwitcher"></select>
                </li>
            </ul>
      </div>
    </div>
  </nav>

    <div class="d-flex justify-content-center">
        <div class="d-flex" style="width: 50%; margin-top: 20px;">
            <div class="container" id="container">
<!--                <form method="post">-->

                    <h3 data-i18n="Available_features"></h3>
                    <br>
                    <ol>
                        <li>
                            <strong data-i18n="Create_a_question"></strong>
                            <p data-i18n="feature1"></p>
                        </li>
                        <li>
                            <strong data-i18n="Create_a_test"></strong>
                            <p data-i18n="feature2"></p>
                        </li>
                        <li>
                            <strong data-i18n="Assign_test_to_a_student"></strong>
                            <p data-i18n="feature3"></p>
                        </li>
                        <li>
                            <strong data-i18n="Filter_and_export_to_CSV"></strong>
                            <p data-i18n="feature4"></p>
                            <p data-i18n="To_export_table"></p>
                        </li>
                    </ol>

                    <input type="text" name="name" class="form-control" id="InputName" style="display:none">
                    <button type="submit" class="btn btn-primary" data-i18n="Export_to_PDF" onclick="save_pdf()"></button>
<!--                </form>-->


            </div>
        </div>
    </div>


    </body>
    <script src="theGuide.js"></script>

    </html>
<?php
//require_once('../pdf/pdf.php');
//
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    if (!empty($_POST)) {
//        $pdf = new Pdf();
//
//        $file_name = 'Test_Results.pdf';
//
//        $html = file_get_contents("theGuide.php");
//        $pdf->loadHtml($html);
//
//        $pdf->render();
//        ob_end_clean();
//        $pdf->stream($file_name, array("Attachment" => false));
//    }
//}
//exit(0);
//?>