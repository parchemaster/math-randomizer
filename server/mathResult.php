<?php
 session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';
require_once ("../database/Database.php");
use MathPHP;
use Webit\Util\EvalMath\EvalMath;
if($_SERVER['REQUEST_METHOD']=='POST') {
   
  echo json_encode(isEqual($_POST['section'],$_POST['result']));
  die();
}

function isEqual($section,$try){
   var_dump($section);
   echo $try;
   $eval=new EvalMath;
   $connection = (new Database())->getConnection();
   $answerInfo=getAnswer($section,$connection);
   $expression1 = $answerInfo['solution_clear'];
   $points= $answerInfo['points'];
   $expression2 = $try;
   echo($expression1);
   $temp=$eval->evaluate($expression1);
   $temp2=$eval->evaluate($expression2);
   $result=0;
   $points=0;
   if(round($temp,4)==round($temp2,4)){
    $result=1;
    $points= $answerInfo['points'];
    updatePoints($points,$_SESSION['id'],$connection);
   }
   createAnswer($section,$try,$result,$_SESSION['id'],$points,$connection);
   return $result;
}

function createAnswer($section, $answ, $result, $id,$point,$connection)
{
    $stmt = $connection->prepare('INSERT INTO student_succes (section, answer, result, student_id, points) VALUES (:section, :answer, :result, :student_id, :points)');
    $stmt->bindParam(':section', $section);
    $stmt->bindParam(':answer', $answ);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':student_id',$id);
    $stmt->bindParam(':points', $point);    
    $stmt->execute();   
    return (int)$connection->lastInsertId();
}

 function updatePoints($points,$id,$connection){
    $stmt = $connection->prepare('update students_info set tasks_sub = (tasks_sub + 1), points = (points + :points) where student_id = :id');
    $stmt->bindParam(':points', $points);
    $stmt->bindParam(':student_id', $id);
    $stmt->execute();
    unset($stmt);
}

 function getAnswer($section,$connection)
{        
    $stmt = $connection->prepare('SELECT solution_clear,questions.points as points FROM examples JOIN questions on examples.question_id=questions.id WHERE section = :section');
    $stmt->bindParam(':section', $section);
    $stmt->execute();
    $answ = $stmt->fetch(PDO::FETCH_ASSOC);
    return $answ;
}


?>