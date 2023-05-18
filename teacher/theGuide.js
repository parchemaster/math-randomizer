function save_pdf(){
    const element = document.getElementById('container');
    // Choose the element and save the PDF for your user.
    html2pdf().from(element).save();
}