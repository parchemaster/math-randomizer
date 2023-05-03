<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    header("Location: ../restricted.php");
    exit;
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

    // Validacia mena
    if (checkEmpty($_POST['firstname']) === true) {
        $errmsg .= "<br>Enter name.<br>";
    } elseif (checkName($_POST['firstname']) === false) {
        $errmsg .= "<br>The name's field can only contain uppercase letters and lowercase letters<br>";
    }

    // Validacia priezviska
    if (checkEmpty($_POST['lastname']) === true) {
        $errmsg .= "<br>Enter last name.</br>";
    } elseif (checkName($_POST['lastname']) === false) {
        $errmsg .= "<br>The last name's field can only contain uppercase letters and lowercase letters<br>";
    }

    // Validacia mailu
    if (checkEmail($_POST['email']) === false) {
        $errmsg .= "Incorrect email format.";
    }

    // Kontrola pouzivatela
    if (userExist($pdo, $_POST['email']) === true) {
        $errmsg .= "<br>A user with this email already exists.<br>";
    }

    // Validacia hesla
    if (checkEmpty($_POST['password']) === true) {
        $errmsg .= "<br>Enter password.</br>";
    } elseif (checkLength($_POST['password'], 8, 256) === false) {
        $errmsg .= "<br>The password must have a minimum of 8 characters.<br>";
    }

    if (empty($errmsg)) {
        $fullname = $_POST['firstname'] . ' ' . $_POST['lastname'];
        $email = $_POST['email'];
        $hashed_password = password_hash($_POST['password'], PASSWORD_ARGON2ID);

        if ($_POST['role'] == "student"){
            $teacher_id = $_POST['teacher'];

            $sql = "INSERT INTO students (full_name, email, password, teacher_id) VALUES (:fullname, :email, :password, :teacher_id)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);
            $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $errmsg .= "<br>Registration was successful!<br>";
            } else {
                $errmsg .= "<br>Something get wrong.<br>";
            }
        } else if ($_POST['role'] == "teacher"){
            $sql = "INSERT INTO teachers (full_name, email, password) VALUES (:fullname, :email, :password)";
            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(":fullname", $fullname, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            if ($stmt->execute()) {
                $errmsg .= "<br>Registration was successful!<br>";
            } else {
                $errmsg .= "<br>Something get wrong.<br>";
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
</head>
<body>
<div class="d-flex justify-content-center">
    <div class="mx-auto">
        <h1>Sign up</h1>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <br>
            <label for="role">Select your role:</label>
            <select id="role" name="role" class="form-control" onchange="showTeacherSelect()" required>
                <option value="student" selected>Student</option>
                <option value="teacher">Teacher</option>
            </select>

            <br>
            <div id="teacher-div">
                <label for="teacher">Select your teacher:</label>
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
                Name:
                <input type="text" class="form-control" name="firstname" value="" id="firstname" placeholder="Name"
                       maxlength="63" required>
            </label>

            <br>

            <label for="lastname">
                Last name:
                <input type="text" class="form-control" name="lastname" value="" id="lastname" placeholder="Last name"
                       maxlength="64" required>
            </label>

            <br>

            <label for="email">
                E-mail:
                <input type="email" class="form-control" name="email" value="" id="email" placeholder="user@mail.com"
                       maxlength="128" required>
            </label>

            <br>

            <label for="password">
                Password:
                <input type="password" class="form-control" name="password" value="" id="password" maxlength="256"
                       required>
            </label>

            <br>
            <?php
            if (!empty($errmsg)) {
                // Tu vypis chybne vyplnene polia formulara.
                echo $errmsg . "<br>";
            }
            ?>
            <button type="submit" class="btn btn-primary">Sign up</button>
        </form>
        <br>
        <p>Already have an account? <a href="login.php">Sign in.</a></p>
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