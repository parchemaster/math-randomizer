<?php
// session_start();

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);


require_once '../vendor/autoload.php';

use MathPHP;
use Webit\Util\EvalMath\EvalMath;

$file_contents = file_get_contents('../latex/odozva01pr.tex');
//$file_contents=preg_quote($file_contents);
//echo $file_contents;

    //  $file_contents = "\section*{O23A7A}
    //  \begin{task}
    //      Vypočítajte prechodovú funkciu pre systém opísaný prenosovou funkciou
    //      \begin{equation*}
    //          F(s)=\dfrac{6}{(5s+2)^2}e^{-4s}
    //      \end{equation*}
    //  \end{task} 
     
    //  \begin{solution}
    //      \begin{equation*}
    //          y(t)=\left[ \frac{3}{2}-\frac{3}{2}e^{-\frac{2}{5}(t-4)}-\frac{3}{5}(t-4)e^{-\frac{2}{5}(t-4)} \right] \eta(t-4)
    //      \end{equation*}
    //  \end{solution}";
    $pattern = '/section[*][{](.*?)(nd[{]solution){1}/s';
    // $pattern = '/begin[{]task[}].{1,}end[{]equation/s';
     //echo $pattern;
     //echo $file_contents;
     preg_match_all($pattern, $file_contents, $matches);
     //var_dump($matches[0]);
     $tasks=[];
     $solutions=[];
     foreach ($matches[0] as $match) {
        $pattern = '/begin[{]task(.*?)(nd[{]task){1}/s';
        preg_match($pattern, $match, $temp);
        $tasks[]=$temp[0];
        $pattern = '/begin[{]solution(.*?)(nd[{]solution){1}/s';
        preg_match($pattern, $match, $temp);
        $solutions[]=equationParse($temp[0]);
     }
     $clearTask=[];
     foreach($tasks as $tsk){
      // $pattern = '/begin[{]task(.*?)(nd[{]task){1}/s';
      $clearTask[]=taskParse($tsk);
   }

    function equationParse($latexTask){
      $pattern = '/((?<=begin[{]solution[}])(.*?)\\\end{equation\*}){1}/s';
      // $pattern = '/((?<=begin[{]equation[*][}])(.*?)(?=\\\end{equation\*})){1}/s';
      preg_match($pattern, $latexTask, $temp);
      return($temp[0]);
    }
    function taskParse($latexTask){
      $pattern = '/((?<=begin[{]task[}])(.*?)\\\end{equation\*}){1}/s';
      preg_match($pattern, $latexTask, $temp);
      return $temp[0];
    }
    function latexFormula($latexTask){
        $pattern = '/((?<=begin[{]equation[*][}](\n))(.*?)(?=\\\end{equation\*})){1}/s';
        preg_match($pattern, $latexTask, $temp);
        $str = preg_replace('/\s+/', '', $temp[0]);
        $str = preg_replace('/\n+/', '', $str );
        return $str;
    }
  function isEqual($answ,$try){
   $temp= shell_exec("sudo maxima --batch-string='is(equal(x+2,x+4/2));'");
   $eval=new EvalMath;
   $expression1 = $answ;
   $expression2 = $try;
   $temp=$eval->evaluate($expression1);
   $temp2=$eval->evaluate($expression2);
   if(round($temp,4)==round($temp2,4))
    return("OK");
  }

 
if($_SERVER['REQUEST_METHOD']=='POST') {
    if($_POST['right']!='null'){
        echo json_encode(isEqual($_POST['right'],$_POST['result']));
    }
    else{
        echo json_encode(latexFormula($solutions[0]));
    }    
    die();
}


?>