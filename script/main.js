

window.MathJax = {
    tex: {
        inlineMath: [['$', '$'], ['\\(', '\\)']]
    },
    svg: {
        fontCache: 'global'
    }
};


function getRandomLatexFile() {
    try {
        // create an AJAX object
        var xhttp = new XMLHttpRequest();

        // set up the callback function for when the request is complete
        xhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // console.log(this.responseText);
                parseSections(this.responseText);
            }
        };

        // send the AJAX request to the PHP file
        xhttp.open("GET", "https://site212.webte.fei.stuba.sk/final/latexHandler.php", true);
        xhttp.send();
    }
    catch (e) {
        console.log(e);
    }

}

function parseSections(latexCode) {
    latexCode = JSON.parse(latexCode).file
    const taskRegex = /\\begin{task}([\s\S]*?)\\end{task}/g;
    const solutionRegex = /\\begin{solution}([\s\S]*?)\\end{solution}/g;
    const taskMatches = Array.from(latexCode.matchAll(taskRegex));
    const solutionMatches = Array.from(latexCode.matchAll(solutionRegex));

    // Select a random task match
    const randomTaskMatch = taskMatches[Math.floor(Math.random() * taskMatches.length)];
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
        xhttp.open("GET", "https://site212.webte.fei.stuba.sk/final/latexHandler.php", true);
        xhttp.send();
    }
    catch (e) {
        console.log(e);
    }

}