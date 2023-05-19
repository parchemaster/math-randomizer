
 

function checkResult(sectionId,id) {
    console.log(mathDiv);
    console.log(sectionId);
    $.ajax({
        type: 'POST',
        url: "../server/mathResult.php",
        data: {
            number: randomNumber,
            result: parseLatex(mathDiv.textContent),
            id:id
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