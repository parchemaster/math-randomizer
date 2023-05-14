window.onload=function(){
const form = document.getElementById("file-form");
form.addEventListener("submit", (event) => {
    event.preventDefault();
    const file = document.getElementById("latex-file").files[0];
    //const formData = new FormData();
    var fr = new FileReader();
    //fr.result = formData;         
    //fr.readAsText(file);
    const name = document.getElementById("test-name").value;
    
    //formData.append("file", file);
    var body = {
        question: "",
        name: ""
    }
    fr.onload=function(){
        //console.log(fr.result);
        body.question = fr.result;
        body.name = name;
  
    $.post('questionHandler.php', {body: JSON.stringify(body.question), name:JSON.stringify(body.name)});

   
}
fr.readAsText(file);
});


}