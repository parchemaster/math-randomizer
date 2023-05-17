<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the user is logged in, if no then redirect him to login
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: ../auth/login.php");
    exit;
} else if (isset($_SESSION["user_type"]) && $_SESSION["user_type"] == "student") {
    header("Location: ../student/student_index.php");
    exit;
}

 require_once('config.php');     
 try{
     $db = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
     $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }
 
 catch(PDOException $e){
     echo 'eaaarror';
 }

  $file_contents = file_get_contents('latex/odozva01pr.tex');
  latexParserToDatabase($file_contents,$db);
  $file_contents = file_get_contents('latex/odozva02pr.tex');
  latexParserToDatabase($file_contents,$db);
  $file_contents = file_get_contents('latex/blokovka01pr.tex');
  latexParserToDatabase($file_contents,$db);
  $file_contents = file_get_contents('latex/blokovka02pr.tex');
  latexParserToDatabase($file_contents,$db);


  function latexParserToDatabase($file,$db){
    //parse sections
    $sql = "INSERT INTO examples (section,task,solution,solution_clear) VALUES (?,?,?,?)";
    $pattern = '/section[*][{](.*?)(nd[{]solution){1}/s';
    preg_match_all($pattern, $file, $sections);
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
        $stmt->execute([$sectionNumbers,$tasks,$solutions,$solutionsClear[0]]);
      }
      else{
        $stmt->execute([$sectionNumbers,$tasks,$solutions,$solutionsClear]);
      }
      // $stmt = $db->prepare($sql);
      // $stmt->execute([$sectionNumbers,$tasks,$solutions,$solutionsClear]);
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
      $exclude = array("pi", "e", "squar","pi","sin","cos","log","tan","int","lim");
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
?>
<!DOCTYPE html>
<!-- KaTeX requires the use of the HTML5 doctype. Without it, KaTeX may not render properly -->
<html>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.css" integrity="sha384-3UiQGuEI4TTMaFmGIZumfRPtfKQ3trwQE2JgosJxCnGmQpL/lJdjpcHkaaFwHlcI" crossorigin="anonymous">

    <!-- The loading of KaTeX is deferred to speed up page rendering -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.js" integrity="sha384-G0zcxDFp5LWZtDuRMnBkk3EphCK1lhEf4UEyEM693ka574TZGwo4IWwS6QLzM/2t" crossorigin="anonymous"></script>

    <!-- To automatically render math in text elements, include the auto-render extension: -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"
        onload="renderMathInElement(document.body);"></script>
  </head>
  <body>
    <div id="katex"></div>
    <script>
      



    </script>
  </body>
  ...
</html>