var myFrame = document.getElementById('nr-ext-rsicon');
var mathDiv;
// Ожидаем загрузку iframe
myFrame.onload = function() {
  var frameContent = myFrame.contentDocument || myFrame.contentWindow.document;
  mathDiv = frameContent.getElementById('LatexOutput');
};

function checkResult(sectionId){
    $.ajax({
         type: 'POST',
         url: "https://site203.webte.fei.stuba.sk/project/server/mathResult.php",
         data: {section: sectionId,
                result:parseLatex(mathDiv.textContent)
              },
         success: function(msg){
                //rightAnswer=parseLatex(msg);
                //console.log(rightAnswer);
                console.log(rightAnswer);
            },
            error: function(jqXHR, textStatus, errorThrown) {
         console.log("error: " + textStatus + " " + errorThrown);
        }
        });
}