window.onload = function(){
var checkboxes = document.getElementsByName('question');
const form = document.getElementById("file-form");
var start = document.getElementById("start");
start.min = new Date().toISOString().split("T")[0];

var end = document.getElementById("end");
start.onchange = function(){
    end.min = this.value;
}

form.addEventListener("submit", (event) => {
    event.preventDefault();
    var name = document.getElementById('test-name').value;
    var start = document.getElementById('start').value;
    var end = document.getElementById('end').value;
    var points = document.getElementById('test-points').value;
    var teacher_id = document.getElementById('teacher_id').innerText;
    
    var values = [];
    for (var i = 0; i < checkboxes.length; i++)
    {
        if (checkboxes[i].checked == true){
            values.push(checkboxes[i].value);
            
        }
    }
    $.post('testHandler.php', {teacher_id: JSON.stringify(teacher_id), name: JSON.stringify(name), start: JSON.stringify(start), 
        end: JSON.stringify(end), points: JSON.stringify(points),
        values: JSON.stringify(values)});
        

});


}