

<<<<<<< HEAD

function checkResult(sectionId, id) {
=======
function checkResult(sectionId,id) {
    console.log(mathDiv);
    console.log(sectionId);
>>>>>>> c25aaafbe7b1af0f287629b068bf5c532fea254e
    $.ajax({
        type: 'POST',
        url: "../server/mathResult.php",
        data: {
            number: randomNumber,
            result: parseLatex(mathDiv.textContent),
<<<<<<< HEAD
            id: id,
            rawResult:mathDiv.textContent
=======
            id:id
>>>>>>> c25aaafbe7b1af0f287629b068bf5c532fea254e
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