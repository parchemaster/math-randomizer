


function checkResult(sectionId, id) {
    $.ajax({
        type: 'POST',
        url: "../server/mathResult.php",
        data: {
            number: randomNumber,
            result: parseLatex(mathDiv.textContent),
            id: id,
            rawResult:mathDiv.textContent
        },
        success: function (msg) {
            //rightAnswer=parseLatex(msg);
            // console.log(rightAnswer);
           // console.log(parseLatex(mathDiv.textContent) + "\n")
            // var response = JSON.parse(msg);
            // console.log(response.solution_clear);
            console.log(msg);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + textStatus + " " + errorThrown);
        }
    });
}