<?php
 session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
require_once '../vendor/autoload.php';
use MathPHP;
use Webit\Util\EvalMath\EvalMath;
if($_SERVER['REQUEST_METHOD']=='POST') {
  echo json_encode(isEqual($_POST['section'],$_POST['result']));
  die();
}

function isEqual($section,$try){
   $eval=new EvalMath;
   $QC = new AnswerController();
   $answerInfo=$QC->getAnswer($section);
   $expression1 = $answerInfo['solution_clear'];
   $points= $answerInfo['points'];
   $expression2 = $try;
   $temp=$eval->evaluate($expression1);
   $temp2=$eval->evaluate($expression2);
   $result=0;
   $points=0;
   if(round($temp,4)==round($temp2,4)){
    $result=1;
    $points= $answerInfo['points'];
   }
   $QC->createAnswer($section,$try,$result,$_SESSION['id'],$points);
   return $result;
}
?>