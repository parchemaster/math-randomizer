window.onload = function () {
    var checkboxes = document.getElementsByName('test');
    const form = document.getElementById("file-form");

    form.addEventListener("submit", (event) => {
        event.preventDefault();
        var student_id = document.getElementById('student_id').innerText;

        var values = [];
        for (var i = 0; i < checkboxes.length; i++) {
            if (checkboxes[i].checked == true) {
                values.push(checkboxes[i].value);

            }
        }
        $.post('assignTestHandler.php', {student_id: JSON.stringify(student_id), values: JSON.stringify(values)});

        alert("Test assigned succesfully");
        window.location.reload();
    });


}