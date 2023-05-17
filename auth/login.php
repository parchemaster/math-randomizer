<?php
session_start();

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

require_once "../config.php";
$pdo = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);

function checkLength($field, $min, $max)
{
    $string = trim($field);
    $length = strlen($string);
    if ($length < $min || $length > $max) {
        return false;
    }
    return true;
}

function checkEmpty($field)
{
    if (empty(trim($field))) {
        return true;
    }
    return false;
}

function checkEmail($email)
{
    if (!preg_match('/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+")){3,}@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,4}))$/', trim($email))) {
        return false;
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errmsg = "";

    if (checkEmpty($_POST['email']) === true) {
        $errmsg .= "<span data-i18n='email_error_empty'></span><br>";
    }
    if (checkEmail($_POST['email']) === false) {
        $errmsg .= "<span data-i18n='email_error_invalid'></span><br>";
    }


    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<span data-i18n='password_error_empty'></span><br>";
    } elseif (checkLength($_POST['password'], 8, 256) === false) {
        $errmsg .= "<span data-i18n='password_error_short'></span><br>";
    }

    if (empty($errmsg)) {
        $sql_t = "SELECT * FROM teachers WHERE email = :email";
        $stmt_t = $pdo->prepare($sql_t);
        $stmt_t->bindParam(":email", $_POST["email"], PDO::PARAM_STR);

        $sql_s = "SELECT * FROM students WHERE email = :email";
        $stmt_s = $pdo->prepare($sql_s);
        $stmt_s->bindParam(":email", $_POST["email"], PDO::PARAM_STR);

        if ($stmt_t->execute() && $stmt_s->execute()) {
            if ($stmt_t->rowCount() == 1) {
                $row = $stmt_t->fetch();
                $hashed_password = $row["password"];
                if (password_verify($_POST['password'], $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_type"] = "teacher";
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["fullname"] = $row['full_name'];
                    header("Location: ../teacher/teacher_index.php");
                } else {
                    $errmsg .= "<span data-i18n='login_error'></span>";
                }
            } else if ($stmt_s->rowCount() == 1) {
                $row = $stmt_s->fetch();

                $hashed_password = $row["password"];
                if (password_verify($_POST['password'], $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["user_type"] = "student";
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["student_id"] = $row['id'];
                    $_SESSION["fullname"] = $row['full_name'];
                    $_SESSION["teacher_id"] = $row['teacher_id'];

                    header("Location: ../student/student_index.php");
                } else {
                    $errmsg .= "<span data-i18n='login_error'></span><br>";
                }
            } else {
                $errmsg .= "<span data-i18n='login_error'></span><br>";
            }
        } else {
            $errmsg .= "<span data-i18n='unknown_error'></span><br>";
        }

        unset($stmt);
    }
    unset($pdo);
}
?>
<!doctype html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next@21.6.10/i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-http-backend@1.3.2/i18nextHttpBackend.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-i18next@1.2.1/jquery-i18next.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/i18next-browser-languagedetector@6.1.3/i18nextBrowserLanguageDetector.min.js"></script>

    <script src="../lang/i18n.js"></script>
</head>
<body>
<select name="language" id="languageSwitcher"></select>
<div class="d-flex justify-content-center">
    <div class="mx-auto">
        <h1 data-i18n="signin_heading"></h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <br>

            <label for="email">
                <span data-i18n="email_label"></span>:
                <input type="text" class="form-control" name="email" value="" id="email" required>
            </label>
            <br>
            <label for="password">
                <span data-i18n="password_label"></span>:
                <input type="password" class="form-control" name="password" value="" id="password" required>
            </label>
            <br>
            <?php
            if (!empty($errmsg)) {
                echo "<br>" . $errmsg . "<br>";
            }
            ?>
            <button type="submit" class="btn btn-primary"><span data-i18n="login_button"></span></button>
        </form>
        <br>
        <p><a href="register.php"><span data-i18n="no_account_text"></span></a></p>
    </div>
</div>
</body>
</html>