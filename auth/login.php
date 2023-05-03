<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: ../restricted.php");
    exit;
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
        $errmsg .= "<br>Enter email.<br>";
    }
    if (checkEmail($_POST['email']) === false) {
        $errmsg .= "<br>Incorrect email format.<br>";
    }


    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<br>Enter password.<br>";
    } elseif (checkLength($_POST['password'], 8, 256) === false) {
        $errmsg .= "<br>The password must have a minimum of 8 characters.<br>";
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
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["fullname"] = $row['fullname'];

                    header("Location: ../restricted.php");
                } else {
                    $errmsg .= "<br>Wrong email or password.<br>";
                }
            } else if ($stmt_s->rowCount() == 1) {
                $row = $stmt_s->fetch();
                $hashed_password = $row["password"];
                if (password_verify($_POST['password'], $hashed_password)) {
                    $_SESSION["loggedin"] = true;
                    $_SESSION["email"] = $row['email'];
                    $_SESSION["fullname"] = $row['fullname'];
                    $_SESSION["teacher_id"] = $row['teacher_id'];

                    header("Location: ../restricted.php");
                } else {
                    $errmsg .= "<br>Wrong email or password.<br>";
                }
            } else {
                $errmsg .= "<br>Wrong email or password.<br>";
            }
        } else {
            $errmsg .= "<br>Something get wrong.<br>";
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
</head>
<body>
<div class="d-flex justify-content-center">
    <div class="mx-auto">
        <h1>Sign in</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <br>

            <label for="email">
                Email:
                <input type="text" class="form-control" name="email" value="" id="email" required>
            </label>
            <br>
            <label for="password">
                Password:
                <input type="password" class="form-control" name="password" value="" id="password" required>
            </label>
            <br>
            <button type="submit" class="btn btn-primary">Log in</button>
            <br>
            <?php
            if (!empty($errmsg)) {
                echo $errmsg;
            }
            ?>
        </form>
        <br>
        <p>Don't have an account yet? <a href="register.php">Sign up.</a></p>
    </div>
</div>
</body>
</html>