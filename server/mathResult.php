<?php
 session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../vendor/autoload.php';
require_once ("../database/Database.php");
require_once('../config.php');

use Fubhy\Math\Calculator;
use Webit\Util\EvalMath\EvalMath;

try{
  $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  // echo 'Connected to database';
}
catch(PDOException $e){
  echo 'eaaarror';
}

if($_SERVER['REQUEST_METHOD']=='POST') {
  echo json_encode(isEqual($_POST['id'],$_POST['number'],$_POST['result'],$db,$_POST['rawResult']));
 die();
}

function isEqual($id,$number,$try,$db,$raw){
  //  echo $number."number";
  //  echo $id."id";
   $evaluator = new \Matex\Evaluator();
   $connection = (new Database())->getConnection();
   $answerInfo=getAnswer($number,$id,$db);
  $expression1 = $answerInfo[1];
    var_dump($expression1);
   $points= $answerInfo[0];
   //var_dump($points);
   $expression2 = $try;
    // $countLeft = substr_count($expression1, '(');
    // $countRight = substr_count($expression1, ')');
    //echo($countLeft);
    //echo($countRight);
    $expression1 = str_replace(array(" ", "\n", "\r","\s"), "", $expression1);
    $temp= $evaluator->execute($expression1);
    $temp2=$evaluator->execute($expression2);
    //var_dump(round($temp,4));
    //var_dump(round($temp2,4));
    $result=0;
    $pointi=0;
   if(round($temp,4)===round($temp2,4)){
    $result=1;
    $pointi=$points;
   }
   updatePoints($pointi,$_SESSION['id'],$connection);
   createAnswer($number,$raw,$result,$_SESSION['id'],$pointi,$connection,$answerInfo[2]);
   return $result;
}

function createAnswer($number, $answ, $result, $id,$point,$connection,$questId)
{
    $stmt = $connection->prepare('INSERT INTO student_succes (example_number, answer, result, student_id, points,question_id) VALUES (:example_number, :answer, :result, :student_id, :points, :question_id)');
    $stmt->bindParam(':example_number', $number);
    $stmt->bindParam(':answer', $answ);
    $stmt->bindParam(':result', $result);
    $stmt->bindParam(':student_id',$id);
    $stmt->bindParam(':points', $point);    
    $stmt->bindParam(':question_id', $questId);    
    $stmt->execute();   
    return (int)$connection->lastInsertId();
}

 function updatePoints($pointi,$id,$connection){
    $stmt = $connection->prepare('update students_info set tasks_sub = (tasks_sub + 1), points = (points + :points) where student_id = :id');
    $stmt->bindParam(':points', $pointi);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    unset($stmt);
}

 function getAnswer($number,$id,$db)
{        
    // $stmt = $connection->prepare('SELECT solution_clear FROM examples WHERE example_number = :number and question_id = :id');
    // $stmt->bindParam(':number', $number);
    // $stmt->bindParam(':id', $id);
    // $stmt->execute();
    // $answ = $stmt->fetch(PDO::FETCH_ASSOC);
    $sql = "SELECT points FROM questions WHERE id = ".$id;
    $stmt = $db->query($sql); 
    $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
    

    $sql = "SELECT solution_clear FROM examples WHERE example_number = ".$number." and question_id = ".$id;
    $stmt = $db->query($sql); 
    $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
    $resp=[$result1['points'],$result2['solution_clear'],$id];
    return $resp;
}


?>