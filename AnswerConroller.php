<?php
require_once ("database/Database.php");

class AnswerController
{

    private $connection;

    
    public function __construct()
    {
        $this->connection = (new Database())->getConnection();
    }//$section,$try,$result,$_SESSION['id']
    public function createAnswer($section, $answ, $result, $id,$point)
    {
        $stmt = $this->connection->prepare('INSERT INTO student_succes (section, answer, result, student_id, points) VALUES (:section, :answer, :result, :student_id, :points)');
        $stmt->bindParam(':section', $section);
        $stmt->bindParam(':answer', $answ);
        $stmt->bindParam(':result', $result);
        $stmt->bindParam(':student_id',$id);
        $stmt->bindParam(':points', $point);    
        $stmt->execute();   
        return (int)$this->connection->lastInsertId();
            
        
    }
    public function updatePoints($points,$id){
        $stmt = $this->connection->prepare('update students_info set tasks_sub = (tasks_sub + 1), points = (points + :points) where student_id = :id');
        $stmt->bindParam(':points', $points);
        $stmt->bindParam(':student_id', $id);
        $stmt->execute();
        unset($stmt);
    }

    public function getAnswer($section)
    {        
        $stmt = $this->connection->prepare('SELECT solution_clear,questions.points as points FROM examples JOIN questions on examples.question_id=questions.id WHERE section = :section');
        $stmt->bindParam(':section', $section);
        $stmt->execute();
        $answ = $stmt->fetch(PDO::FETCH_ASSOC);
        return $answ;
    }
}