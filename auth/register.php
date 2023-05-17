<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && isset($_SESSION["user_type"]) && $_SESSION["loggedin"] === true) {
    if ($_SESSION["user_type"] == "student") {
        header("Location: ../student/student_index.php");
        exit;
    } else if ($_SESSION["user_type"] == "teacher") {
        header("Location: ../teacher/teacher_index.php");
        exit;
    }
}

require_once '../config.php';

$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

function checkEmpty($field)
{
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkLength($field, $min, $max)
{
    $string = trim($field);     // Odstranenie whitespaces.
    $length = strlen($string);      // Zistenie dlzky retazca.
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkName($name)
{
    if (!preg_match('/^[a-zA-Z]+$/', trim($name))) {
        return false;
    }
    return true;
}

function checkEmail($email)
{
    if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+")){3,}@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/', trim($email))) {
        return false;
    }
    return true;
}

function userExist($pdo, $email)
{
    $exist = false;

    $param_email = trim($email);

    $sql = "SELECT email FROM teachers WHERE email = :email UNION SELECT email FROM students WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $exist = true;
    }

    unset($stmt);

    return $exist;
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    if (checkEmpty($_POST['firstname']) === true) {
        $errmsg .= "<span data-i18n='name_error_empty'></span><br>";
    } elseif (checkName($_POST['firstname']) === false) {
        $errmsg .= "<span data-i18n='name_error_format'></span><br>";
    }

    if (checkEmpty($_POST['lastname']) === true) {
        $errmsg .= "<span data-i18n='last_name_error_empty'></span><br>";
    } elseif (checkName($_POST['lastname']) === false) {
        $errmsg .= "<span data-i18n='last_name_error_format'></span><br>";
    }

    if (checkEmail($_POST['email']) === false) {
        $errmsg .= "<span data-i18n='email_error_invalid'></span><br>";
    }

    if (userExist($pdo, $_POST['email']) === true) {
        $errmsg .= "<span data-i18n='email_error_duplicate'></span><br>";
    }

    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<span data-i18n='password_error_empty'></span><br>";
    } elseif (checkLength($_POST['password'], 8, 256) === false) {
        $errmsg .= "<span data-i18n='password_error_short'></span><br>";
    }

    if (empty($errmsg)) {
        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        if ($_POST['role'] == "student") {
            $teacher_id = $_POST['teacher'];

            $sql = "INSERT INTO students (full_name, email, password, teacher_id) VALUES (:fullname, :email, :password, :teacher_id)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $errmsg .= "<span data-i18n='registration_success' class='success'></span><br>";
            } else {
                $errmsg .= "<span data-i18n='unknown_error'></span><br>";
            }
        } else if ($_POST['role'] == "teacher") {
            $sql = "INSERT INTO teachers (full_name, email, password) VALUES (:fullname, :email, :password)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $errmsg .= "<span data-i18n='registration_success' class='success'></span><br>";
            } else {
                $errmsg .= "<span data-i18n='unknown_error'></span><br>";
            }
        }

        unset($stmt);
    }
}

?>

<!doctype html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-http-backend@1.3.2/i18nextHttpBackend.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

    <script src="../lang/i18n.js"></script>
    <style>.success{color: green}</style>
</head>
<body>
<div class="d-flex justify-content-center">
    <div class="mx-auto">
        <br>
        <select name="language" id="languageSwitcher"></select>
        <br>
        <br>
        <h1 data-i18n='signup_heading'></h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <br>
            <label for="role"><span data-i18n='select_role'></span>:</label>
            <select id="role" name="role" class="form-control" onchange="showTeacherSelect()" required>
                <option value="student" selected>Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <br>
            <div id="teacher-div">
                <label for="teacher"><span data-i18n='select_teacher_label'></span>:</label>
                <select id="teacher" name="teacher" class="form-control" required>
                    <?php
                    $sql = "SELECT * FROM teachers";
                    $result_teachers = $pdo->prepare($sql);
                    $result_teachers->execute();
                    if ($result_teachers->rowCount() > 0) {
                        while ($row = $result_teachers->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['full_name'] . "</option>";
                        }
                    }
                    unset($result_teachers);
                    ?>
                </select>
            </div>
            <br>
            <label for="firstname">
                <span data-i18n='name_label'></span>:
                <input type="text" class="form-control" name="firstname" value="" id="firstname"
                       maxlength="63" required>
            </label>

            <br>

            <label for="lastname">
                <span data-i18n='last_name_label'></span>:
                <input type="text" class="form-control" name="lastname" value="" id="lastname"
                       maxlength="64" required>
            </label>

            <br>

            <label for="email">
                <span data-i18n='email_label'></span>:
                <input type="email" class="form-control" name="email" value="" id="email" placeholder="user@mail.com"
                       maxlength="128" required>
            </label>

            <br>

            <label for="password">
                <span data-i18n='password_label'></span>:
                <input type="password" class="form-control" name="password" value="" id="password" maxlength="256"
                       required>
            </label>

            <br>
            <?php
            if (!empty($errmsg)) {
                echo "<br>" . $errmsg . "<br>";
            }
            ?>
            <button type="submit" class="btn btn-primary"><span data-i18n='signup_button'></span></button>
        </form>
        <br>
        <p><a href="login.php"><span data-i18n='have_account_text'></span></a></p>
    </div>
</div>
</body>
<script>
    function showTeacherSelect() {
        let roleSelect = document.getElementById("role");
        let teacherDiv = document.getElementById("teacher-div");
        let teacherSelect = document.getElementById("teacher");
        if (roleSelect.value === "student") {
            teacherDiv.style.display = "block";
            teacherSelect.required = true;
        } else {
            teacherDiv.style.display = "none";
            teacherSelect.required = false;
        }
    }
</script>
</html>