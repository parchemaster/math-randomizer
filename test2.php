<?php
$file_contents = file_get_contents('latex/odozva01pr.tex');
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
        $solutions[]=$temp[0];
     }
     foreach($solutions as $sol){
        // $pattern = '/begin[{]task(.*?)(nd[{]task){1}/s';
        //equationFind($sol);
     }
     foreach($tasks as $tsk){
      // $pattern = '/begin[{]task(.*?)(nd[{]task){1}/s';
      taskParse($tsk);
   }

    function equationFind($latexTask){
      $pattern = '/((?<=begin[{]equation[*][}])(.*?)(?=\\\end{equation\*})){1}/s';
      preg_match($pattern, $latexTask, $temp);
      var_dump($temp[0]);
    }
    function taskParse($latexTask){
      $pattern = '/((?<=begin[{]task[}])(.*?)\\\end{equation\*}){1}/s';
      preg_match($pattern, $latexTask, $temp);
      var_dump($temp[0]);
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