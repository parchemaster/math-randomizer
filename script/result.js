

function checkResult(sectionId) {
    $.ajax({
        type: 'POST',
        url: "../server/mathResult.php",
        data: {
            section: sectionId,
            result: parseLatex(mathDiv.textContent)
        },
        success: function (msg) {
            //rightAnswer=parseLatex(msg);
            //console.log(rightAnswer);
            console.log(rightAnswer);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + textStatus + " " + errorThrown);
        }
    });
}