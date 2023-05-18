
 

function checkResult(sectionId) {
    console.log(mathDiv);
    $.ajax({
        type: 'POST',
        url: "../server/mathResult.php",
        data: {
            section: sectionId[1],
            result: parseLatex(mathDiv.textContent)
        },
        success: function (msg) {
            //rightAnswer=parseLatex(msg);
            //console.log(rightAnswer);
            console.log(msg);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + textStatus + " " + errorThrown);
        }
    });
}