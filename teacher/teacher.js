$(document).ready(function () {
    $('#table').DataTable({
  
        "lengthMenu": [[5, 10, 20, -1], [5, 10, 20, "All"]],
        "columns": [
            { "orderable": true },
            { "orderable": true },
            { "orderable": true },
            { "orderable": true },
            { "orderable": true },
        ],
        columnDefs: [
            { targets: [2,3,4], orderData: [1,2,3,4] }
        ]
      });
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
