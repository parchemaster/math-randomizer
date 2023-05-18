
<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// $latex_formula_1= '\frac{1}{2}';
// $latex_formula_2='\frac{3}{4}';
// $cmd = "octave -qf --eval 'printf (\"%f\", sqrt (3^2 + 4^2));'";
// $result =exec("sudo octave -qf --eval 'pkg load symbolic; syms x; expr1 = parse_latex(".$latex_formula_1."); expr2 = parse_latex(".$latex_formula_2.");printf('%d',isequal(expr1, expr2))'");
// var_dump($result);
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
?>
<!DOCTYPE html>
<html lang="en" data-lt-installed="true">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="./script/main.js" async></script>
   <script src="./script/latexParser.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@scicave/math-latex-parser/lib/bundle.js">
</script>
<script src="https://code.jquery.com/jquery-3.5.1.js" ></script>
<script src="https://unpkg.com/evaluatex@2.2.0/dist/evaluatex.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@scicave/math-latex-parser/lib/bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/tesseract.js@4/dist/tesseract.min.js'></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/mathjs/11.8.0/math.min.js" integrity="sha512-DmQnZdEjFh4R/040JxDSg3j9cS8D0oC5u2noG9Az18F11leiDFwrTOkm9PP+jdBMiH66QBa73O+54kZr5FyU7w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="canonical" href="https://getbootstrap.com/docs/5.2/examples/pricing/">
<script src="https://polyfill.io/v3/polyfill.min.js?features=es6"></script>
<script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
  integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
  crossorigin="anonymous"></script>
   
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.css" integrity="sha384-3UiQGuEI4TTMaFmGIZumfRPtfKQ3trwQE2JgosJxCnGmQpL/lJdjpcHkaaFwHlcI" crossorigin="anonymous">

    <!-- The loading of KaTeX is deferred to speed up page rendering -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/katex.min.js" integrity="sha384-G0zcxDFp5LWZtDuRMnBkk3EphCK1lhEf4UEyEM693ka574TZGwo4IWwS6QLzM/2t" crossorigin="anonymous"></script>

    <!-- To automatically render math in text elements, include the auto-render extension: -->
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.7/dist/contrib/auto-render.min.js" integrity="sha384-+VBxd3r6XgURycqtZ117nYw44OOcIax56Z4dCRWbxyPt0Koah1uHoK0o4+/RRE05" crossorigin="anonymous"
        onload="renderMathInElement(document.body);"></script>

<meta name="theme-color" content="#712cf9">

  </head>

<body data-new-gr-c-s-check-loaded="14.1107.0" data-gr-ext-installed="">
<main>
<h2 class="text-center">
    <?php
        echo $clearTask[0];
    ?>
</h2>
<p class="text-center">
           answer <br>
           <?php
               echo $solutions[0];
           ?>
      </p>
      <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm text-center">
          <div class="card-header py-3">
            <h4 class="my-0 fw-normal">Topic name or something else</h4>
          </div>
          <div class="card-body">
            <div id="tex">
              <p id="randomLatex">
              </p>
            </div>

              <button id="btnAnswer" type="button"  class="w-100 btn btn-lg btn-primary">Answer</button>
            <div class="iframe-wrapper">
              <iframe id="latexFrame" src="equation-editor/equation-editor.html"></iframe>
            </div>
          
          </div>
        </div>
      </div>
     
    </main>

    <style>
      .iframe-wrapper {
        position: relative;
        width: 100%;
        height: 0;
        padding-bottom: 56.25%;
      }

      .iframe-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
      }
    </style>

  </div>
  <iframe id="nr-ext-rsicon"
    style="position: absolute; display: none; width: 50px; height: 50px; z-index: 2147483647; border-style: none; background: transparent;"></iframe>


<script >
  
// Получаем iframe по его ID
var myFrame = document.getElementById('latexFrame');
var mathDiv;
var rightAnswer;
// Ожидаем загрузку iframe
myFrame.onload = function() {
  var frameContent = myFrame.contentDocument || myFrame.contentWindow.document;
  mathDiv = frameContent.getElementById('LatexOutput');
};
$('#btnAnswer').on('click',function(){
  checkResult(mathDiv.textContent);
});

function checkResult(elem){
    mathFormath=parseLatex(elem);
    $.ajax({
         type: 'POST',
         url: "https://site203.webte.fei.stuba.sk/project/server/mathResult.php",
         data: {section: section,
                result:parseLatex
              },
         success: function(msg){
                rightAnswer=parseLatex(msg);
                //console.log(rightAnswer);
                console.log(rightAnswer);
                request();
            },
            error: function(jqXHR, textStatus, errorThrown) {
            // Обработка ошибки
         console.log("Ошибка: " + textStatus + " " + errorThrown);
        }
        });
}
function request(){
  $.ajax({
         type: 'POST',
         url: "https://site203.webte.fei.stuba.sk/project/server/mathResult.php",
         data: {example: "1",
                result:mathFormath,
                right:rightAnswer
              },
         success: function(msg){
                console.log(msg);
            },
            error: function(jqXHR, textStatus, errorThrown) {
            // Обработка ошибки
         console.log("Ошибка: " + textStatus + " " + errorThrown);
        }
        });
}
</script>
</body><grammarly-desktop-integration data-grammarly-shadow-root="true"></grammarly-desktop-integration>
</html>
