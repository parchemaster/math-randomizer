const form = document.getElementById("file-form");
form.addEventListener("submit", (event) => {
    // event.preventDefault();
    // const file = document.getElementById("latex-file").files[0];
    // const formData = new FormData();
    // formData.append("file", file);

    // fetch("/api/upload-latex-file", {
    //     method: "POST",
    //     body: formData
    // }).then(response => {
    //     if (response.ok) {
    //         alert("File uploaded successfully");
    //     } else {
    //         alert("Error uploading file");
    //     }
    // }).catch(error => {
    //     console.error(error);
    //     alert("Error uploading file");
    // });
});


function exportToCSV() {
    var table = document.getElementsByTagName("table")[0];
    var rows = table.rows;
    var csv = [];

    // Iterate through the table rows and cells to build the CSV data
    for (var i = 0; i < rows.length; i++) {
        var row = [];
        for (var j = 0; j < rows[i].cells.length; j++) {
            row.push(rows[i].cells[j].innerText);
        }
        csv.push(row.join(","));
    }

    // Create a download link for the CSV file
    var link = document.createElement("a");
    link.setAttribute("href", "data:text/csv;charset=utf-8," + encodeURIComponent(csv.join("\n")));
    link.setAttribute("download", "table.csv");
    document.body.appendChild(link);

    // Trigger the download by simulating a click on the link
    link.click();
    document.body.removeChild(link);
}