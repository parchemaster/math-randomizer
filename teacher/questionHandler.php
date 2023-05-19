<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once '../QuestionsController.php';
require_once('../config.php');     
try{
    $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}

catch(PDOException $e){
    echo 'eaaarror';
}

$QC = new QuestionsController();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST['test_id'])){
    $array = json_decode($_POST['body']);
    $array2 = json_decode($_POST['name']);
    $id=$QC->createQuestion($array, $array2);
    latexParserToDatabase($array,$db,$id);
    }
    
} 

function latexParserToDatabase($file,$db,$id){
    //parse sections
    $sql = "INSERT INTO examples (example_number,task,solution,solution_clear,question_id) VALUES (?,?,?,?,?)";
    $pattern = '/section[*][{](.*?)(nd[{]solution){1}/s';
    preg_match_all($pattern, $file, $sections);
    $i=0;
    foreach ($sections[0] as $match) {
        
      //parse sections numbers
      $pattern = '/((?<=section[*][{])(.*?)(?=})){1}/';
      preg_match($pattern, $match, $temp);
      //var_dump($temp[0]);
      $sectionNumbers=$temp[0];
      //parse tasks
      $pattern = '/((?<=begin[{]task[}])(.*?)(?=\\\end[{]task)){1}/s';
      preg_match($pattern, $match, $temp);
      $tasks=$temp[0];
      //parse solutions
      $pattern = '/((?<=begin[{]solution[}])(.*?)(?=\\\end[{]solution)){1}/s';
      preg_match($pattern, $match, $temp);
      $solutions=$temp[0];
      $solutionsClear=latexMathToTxtMath($temp[0]);
      $stmt = $db->prepare($sql);
      if(gettype($solutionsClear)=='array'){
        $stmt->execute([$i,$tasks,$solutions,$solutionsClear[0],$id]);
      }
      else{
        $stmt->execute([$i,$tasks,$solutions,$solutionsClear,$id]);
      }
      // $stmt = $db->prepare($sql);
      // $stmt->execute([$sectionNumbers,$tasks,$solutions,$solutionsClear]);
      $i+=1;
    }
}

function latexMathToTxtMath($latex){
  $pattern = '/((?<=begin[{]equation[*][}])(.*?)(?=\\\end{equation\*})){1}/s';
  preg_match($pattern, $latex, $temp);
  $search = ['\s', '\\left', '\\right','[',']','{','}','"','\\','/',' ',];
  $replace = ['', '', '','(',')','(',')','','','',''];
  $str = str_replace($search, $replace, $temp);
  $pattern = '/((?<=[=])(.*)(?=[=])){1}/s';
  preg_match($pattern, $str[0], $temp);
  if($temp==null){
    $pattern = '/((?<=[=])(.*)){1}/s';
    preg_match($pattern, $str[0], $temp);
    if($temp==null){
       $temp=$str[0];
    }
  }
      $str = preg_replace('/dfrac\((.+)\)\((.+)\)/', '(($1)/($2))', $temp);
      $str = preg_replace('/frac\((.+)\)\((.+)\)/', '(($1)/($2))', $str);
      $str = preg_replace('/(\))(\()/', '$1*$2', $str);
      $str = preg_replace('/(\))(\w+)/', '$1*$2', $str);
      $str = preg_replace('/(\w+)(\()/', '$1*$2', $str);
      $str = preg_replace('/([a-zA-Z]+)(\d)/', '$1*$2', $str);
      $str = preg_replace('/(\d)([a-zA-Z]+)/', '$1*$2', $str);
      $exclude = array("pi", "squar","pi","sin","cos","log","tan","int","lim");
      $replaced = preg_replace_callback('/\b\w+\b/', function($match) use ($exclude) {
          $word = strtolower($match[0]);
          if (in_array($word, $exclude)) {
              return $word;
          } else {
              return preg_replace('/(\D+){1}/', '17.5', $match[0]);
          }
      }, $str);
      return $replaced;
    }

    function taskParse($latexTask){
      $pattern = '/((?<=begin[{]task[}])(.*?)\\\end{equation\*}){1}/s';
      preg_match($pattern, $latexTask, $temp);
      return $temp[0];
    }