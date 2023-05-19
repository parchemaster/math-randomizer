<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

<<<<<<< HEAD
require_once 'config.php';
=======
$hostname = "localhost";
$username = "xriumin";
$password = "8rMZHTmnbYrrw7S";
$dbname = "math_randomizer";
>>>>>>> c25aaafbe7b1af0f287629b068bf5c532fea254e
class Database {

    private $conn;

    public function getConnection(): ?PDO
    {

        global $hostname, $dbname, $username, $password;

        $this->conn = null;
        try
        {
            $this->conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        catch(PDOException $exception)
        {
            echo "Database could not be connected: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
class Controller
{
    private $conn;

    public function __construct()
    {
        $this->conn = (new Database())->getConnection();
    }

    public function insertLatex($file): int
    {
        $stmt = $this->conn->prepare("INSERT INTO latex (file) VALUES (?)");
        $stmt->execute([$file]);
        return (int)$this->conn->lastInsertId();
    }

    public function getLatex(int $id): mixed {
<<<<<<< HEAD
        $stmt = $this->conn->prepare("SELECT * FROM questions WHERE test_id = :testId");
        $stmt->bindParam(':testId', $id, PDO::PARAM_INT);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetchAll();
    }
}


$CC = new Controller();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // $testId = $_GET['id'];
    // echo json_encode($CC->getLatex($_GET['id']));
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->prepare("SELECT * FROM questions WHERE test_id = :testId");
    $stmt->bindParam(':testId',$_GET['id'], PDO::PARAM_INT);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $test_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($test_results);
    
}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $testId = $_GET['id'];
    echo json_encode($_GET['id']);
}

=======
        // $stmt = $this->conn->prepare("SELECT file FROM quations");
        // $questions = "SELECT * FROM questions WHERE test_id = :testId";
        // $question_stmt = $this->$conn->prepare($questions);
        // $question_stmt->bindParam(':testId', $id);
        // $question_stmt->execute();
        // $question_results = $question_stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt = $this->conn->prepare("SELECT * FROM questions WHERE test_id = :testId");
        $stmt->bindParam(':testId', $id, PDO::PARAM_INT);
        $stmt->execute();

        // $stmt = $this->conn->prepare("SELECT * FROM questions WHERE test_id = :testId");
        // $stmt->execute([$id]);

        //$stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        return $stmt->fetch();
    }
}


$CC = new Controller();
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $testId = $_GET['id'];
    echo json_encode($CC->getLatex($_GET['id']));
}
else if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $testId = $_GET['id'];
    echo json_encode($_GET['id']);
}

>>>>>>> c25aaafbe7b1af0f287629b068bf5c532fea254e
