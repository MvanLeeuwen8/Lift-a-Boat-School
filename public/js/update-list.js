//show file-names while uploading
function updateList() {
    let input = document.getElementById('files');
    let output = document.getElementById('fileList');
    let children = "";
    for (let i = 0; i < input.files.length; ++i) {
        children += '<li>' + input.files.item(i).name + '</li>';
    }
    output.innerHTML = '<p style="margin-block-end:0;">geselecteerde afbeeldingen:</p><ul style="margin-block-start: 0.3em;">'+children+'</ul>';
}