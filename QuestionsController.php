<?php
require_once ("database/Database.php");

class QuestionsController
{

    private $connection;

    
    public function __construct()
    {
        
        $this->connection = (new Database())->getConnection();
    }


    public function createTest($teacher_id, $name, $start, $end, $total_points)
    {
            $stmt = $this->connection->prepare('INSERT INTO tests (teacher_id, name, time_opened, time_closed, total_points) VALUES (:teacher_id, :name, :time_opened, :time_closed, :total_points)');
            $stmt->bindParam(':teacher_id', $teacher_id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':time_opened', $start);
            $stmt->bindParam(':time_closed', $end);
            $stmt->bindParam(':total_points', $total_points);
            
            $stmt->execute();
           

            return (int)$this->connection->lastInsertId();
            
        
    }
    public function getTeacherId($teacher_email){
        
            $stmt = $this->connection->prepare('SELECT id FROM teachers WHERE email = :email');
            $stmt->bindParam(':email', $teacher_email);
            $stmt->execute();
            $teacher_id = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$teacher_id['id'];
        
    }
    public function updateQuestionWithTestId($array_questions_id, $test_id){
        foreach ($array_questions_id as $question_id){
            $stmt = $this->connection->prepare('UPDATE questions SET test_id = :test_id WHERE id = :id');
            $x = (int)$question_id;
            $stmt->bindParam(':test_id', $test_id);
            $stmt->bindParam(':id', $x);
            $stmt->execute();

        }
    }
    public function createQuestion($question, $name)
    {
        $stmt = $this->connection->prepare("INSERT INTO questions (question, name) VALUES (:question, :name)");
        $stmt->bindParam(':question', $question, PDO::PARAM_STR);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();

        
        return (int)$this->connection->lastInsertId();
        
    }


    public function getAllTests()
    {
        $res = $this->connection->query('select * from tests');
        $tests = array();
        $a = $res->fetchAll();
        array_push($tests, $a);
        unset($stmt);
        return $tests;
    }

    public function getQuestionById($question_id)
    {
        $stmt = $this->connection->prepare('select * from questions where id = ?');
        $stmt->bindParam(':id', $question_id);
        $stmt->execute();
        $test = $stmt->fetch();
        unset($stmt);
        return $test;
    }

    public function getTestById($test_id)
    {
        $stmt = $this->connection->prepare('select * from tests where test_id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->execute();
        $test = $stmt->fetch();
        unset($stmt);
        return $test;
    }

    public function getTestQuestions($test_id)
    {
        $stmt = $this->connection->prepare('select * from questions where test_id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->execute();
        $res = $stmt->fetch();
        $questions = array();
        $a = $res->fetchAll();
        array_push($questions, $a);
        unset($stmt);
        return $questions;
    }

    private function updateTotalTestPoints($test_id, $points)
    {
        $stmt = $this->connection->prepare('update tests set total_points = (total_points + ?) where tests.id = ?');
        $stmt->bindParam(':test_id', $test_id);
        $stmt->bindParam(':total_points', $points);
        $stmt->execute();
        unset($stmt);
    }


    private function createAnswer($question_id, $answer)
    {
        $stmt = $this->connection->prepare('insert into questions (question_id, answer) value (?, ?)');
        $stmt->bindParam(':question_id', $question_id);
        $stmt->bindParam(':answer', $answer);
        $stmt->execute();
        unset($stmt);
        return $this->connection->lastInsertId();
    }



   
}