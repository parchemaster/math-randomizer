

window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
    },
    svg: {
        fontCache: 'global'
    }
};



// function getRandomLatexFile() {
//     try {
//         // create an AJAX object
//         var xhttp = new XMLHttpRequest();

//         // set up the callback function for when the request is complete
//         xhttp.onreadystatechange = function () {
//             if (this.readyState == 4 && this.status == 200) {
//                 console.log(this.responseText);
//                 // parseSections(this.responseText);
//             }
//         };

//         // send the AJAX request to the PHP file
//         xhttp.open("GET", "https://site212.webte.fei.stuba.sk/final/latexHandler.php", true);
//         xhttp.send();
//     }
//     catch (e) {
//         console.log(e);
//     }

// }
var quations = []
var section;
var startTest=false;
function getRandomLatexFile() {
    try {
        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('id');
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText)
                quations = quations.concat(this.responseText)
                var button = document.querySelector('button[type="button"][onclick="getRandomLatexFile()"]');
                activateButton(quations, button)
                if (button) {
                    button.onclick = function () {
                        activateButton(quations, button)
                    };
                    button.innerText = 'Next quation';
                    button.className = "w-100 btn btn-lg btn btn-primary"
                }
            }
        };     
        var url = "../latexHandler.php?id=" + id;
        xhttp.open("GET", url, true);
        xhttp.send();
    } catch (e) {
        console.log(e);
    }
}



function finishTestPHP() {
    try {
        var urlParams = new URLSearchParams(window.location.search);
        var id = urlParams.get('id');
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText)
                window.location.href = "../student/student_index.php";
            }
        };          
        var url = "../handler/studentInfoHandler.php?testId=" + id;
        xhttp.open("PUT", url, true);
        xhttp.send();
    } catch (e) {
        console.log(e);
    }
}

function activateButton(quations, button) {
    if(startTest){
        console.log("tut");
        checkResult(section);
    }
    if (quations.length > 0) {
        quation = quations.pop()
        parseSections(quation)
    }
    else {
        button.innerText = 'Finish test';
        button.className = "w-100 btn btn-lg btn btn-warning"
        button.onclick = function () {
            console.log("test was finished")
            startTest=false;
            finishTestPHP()
        };
    }
    startTest=true;
}

var questionIndex = 0

function parseSections(latexCode2) {
    var object = JSON.parse(latexCode2);
    console.log(object)
    latexCode = object[questionIndex]["question"]
    if( questionIndex < object.length) {
        questionIndex ++
    }
    const taskRegex = /\\begin{task}([\s\S]*?)\\end{task}/g;
    const solutionRegex = /\\begin{solution}([\s\S]*?)\\end{solution}/g;
    const regexSection = /\\section\*{(\w+)}/g;
    const sectionMatches = Array.from(latexCode.matchAll(regexSection));
    const taskMatches = Array.from(latexCode.matchAll(taskRegex));
    const solutionMatches = Array.from(latexCode.matchAll(solutionRegex));


    // Select a random task match
    const randomNumber = Math.floor(Math.random() * taskMatches.length)
    const randomTaskMatch = taskMatches[randomNumber];
    section = sectionMatches[randomNumber];
    // Display the random task match
    const pLatex = document.querySelector("#randomLatex");

    // Extract the image path
    const imagePathRegex = /\\includegraphics{(.*?)}/;
    const imagePathMatch = randomTaskMatch[1].match(imagePathRegex);


    pLatex.innerHTML = randomTaskMatch[1].trim().replace("");
    MathJax.typeset();
    if (imagePathMatch) {
        const imagePath = imagePathMatch[1];
        console.log("Image path:", imagePath);

        // Create an img tag and set its src and style attributes
        const img = document.createElement("img");
        img.src = "latex/" + imagePath;
        img.style.maxWidth = "100%";
        img.style.height = "auto";

        // Replace the \\includegraphics tag with the img tag in the HTML and add it to the DOM
        const pLatexNew = document.querySelector("#randomLatex");
        pLatexNew.innerHTML = pLatexNew.innerHTML.replace("\\includegraphics{" + imagePath + "}", "");
        pLatexNew.appendChild(img);
    } else {
        console.log("No image path found.");
    }

}

const sentDataFromIpToDB = () => {
    try {
        // create an AJAX object
        var xhttp = new XMLHttpRequest();

        // set up the callback function for when the request is complete
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        };

        // send the AJAX request to the PHP file
        xhttp.open("GET", "../latexHandler.php", true);
        xhttp.send();
    }
    catch (e) {
        console.log(e);
    }

}