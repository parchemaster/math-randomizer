window.onload=function(){
const form = document.getElementById("file-form");
form.addEventListener("submit", (event) => {
    event.preventDefault();
    const file = document.getElementById("latex-file").files[0];

    var fr = new FileReader();

    const name = document.getElementById("test-name").value;
    

    var body = {
        question: "",
        name: ""
    }
    fr.onload=function(){

        body.question = fr.result;
        body.name = name;
  
    $.post('questionHandler.php', {body: JSON.stringify(body.question), name:JSON.stringify(body.name)});

    alert("Question was created succesfully");
    window.location.reload();
}
fr.readAsText(file);
});


}