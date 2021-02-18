    <!DOCTYPE html>
<!-- 
	This is an example page for usage of the file-resizer library and the files-uploader library.
	Author	: Mark van Rooten
	Date	: 17-08-2020
	Goal	: To have a pages that tests and shows the functionality of both libraries.
	Content	: This pages allows you to:
				*	Upload images trough the files uploader library.
				*	Resize images trough the image resizer library.
-->

<?php include_once "../includes/config.php"; ?>

<html>
	<head>
		<title>Exmaple page for zws-image-uploader.js and zws-file-resizer.js</title>
		
		<!-- Includes all js files -->
		<script src="../js/docready.js"></script>
		<script src="../vendor/file-uploader/js/file-uploader.js"></script>
		<!--<script src="../vendor/images-resizer/images-resizer.js"></script>-->
		
		<!-- Include all CSS files -->
		<link rel="stylesheet" type="text/css" href="../vendor/file-uploader/css/file-uploader.css">
	</head>
	
	<body>
		<center>			
			<form id="form-image-uploader" method="post" enctype="multipart/form-data">
				<input class="input-file" type="file" name="input-files[]" id = "files" multiple>
				<label for="files">Choose a file<span class="dragdrop"> or drag it here</span>.</label>
			</form>
            <a href="javascript:resizeFolder()">Resize folder</a>
			<table width="100%">
				<tr>
					<td width="50%" align="center" valign="top">
						<p>Folders and Files:</p>
                        <div id="folderDiv">
                        </div>
                        <div id="fileDiv">
                        </div>
					</td>
					<td width="50%" align="center" valign="top">
						<p>Errors:</p>
						<div id="errorDiv"></div>
					</td>
				</tr>
			</table>
		</center>
	</body>
	
	<script>
		var newConfigFileUploader = <?php echo json_encode($CONFIG['file_uploader']); ?>; // Create a var newconfig to pass too the file uploader init function
		var newConfigImageResizer = <?php echo json_encode($CONFIG['file_resizer']); ?>; // Create a var newconfig to pass too the file uploader init function
		var errorDiv = document.getElementById('errorDiv');
		var folderDiv = document.getElementById('folderDiv');
		var fileDiv = document.getElementById('fileDiv');
		var currentDir = "";
        
		function handleImageErrors(errors) {
			errorDiv.innerHTML = "";
			for (item in errors){
				var itemParagraph = document.createElement("p");
				var itemText = document.createTextNode(item + ":");
				itemParagraph.style.color = "red";
				itemParagraph.appendChild(itemText);
				errorDiv.appendChild(itemParagraph);
				for (error in errors[item]) {
					var errorParagraph = document.createElement("p");
					var errorTxt = document.createTextNode(errors[item][error]['time'] + " - error_nr: " + errors[item][error]['error']);				
					errorParagraph.appendChild(errorTxt);
					errorDiv.appendChild(errorParagraph);					
				}
			}
		}
		
        function handleImageSuccess(image)
        {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "../vendor/file-resizer/php/file-resizer.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("action=file&fileName="+image);            
        }
        
        function resizeFolder(image)
        {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "../vendor/file-resizer/php/file-resizer.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("action=folder&folder="+newConfigImageResizer['source_folder']);            
        }
        
        function getFiles(dir) {
            var xhttp = new XMLHttpRequest();
            xhttp.open("POST", "../php/getFiles.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            
            xhttp.onreadystatechange = function(e) { // If we're done with the server script 				
				if (xhttp.readyState === 4) { // We finished the process and the resopnse is ready
                   	folderDiv.innerHTML = "";
                   	fileDiv.innerHTML = "";
                    files = JSON.parse(xhttp.responseText);
                    if (dir != "../images/") {
                        var a = document.createElement('a');
                        var itemParagraph = document.createElement("p");
                        var itemText = document.createTextNode("parent directory");                        
                        a.href = "javascript:changeDir('..')";
                        itemParagraph.appendChild(itemText);
                        a.appendChild(itemParagraph);
                        folderDiv.appendChild(a);                        
                    }
                    for (file in files) {
                        if (files[file]['type'] == "d") {
                            var a = document.createElement('a');
                            var itemParagraph = document.createElement("p");
                            var itemText = document.createTextNode(files[file]['name']);                        
                            a.href = "javascript:changeDir('" + files[file]['name'] + "')";
                            itemParagraph.appendChild(itemText);
                            a.appendChild(itemParagraph);
                            folderDiv.appendChild(a);                            
                        } else {
                            var a = document.createElement('a');
                            a.href = dir + '/' + files[file]['name'];
                            var img = document.createElement('img');
                            img.src = dir + '/' + files[file]['name'];
                            img.setAttribute('width', '400');
                            img.setAttribute('border', '1px');
                            a.appendChild(img)
                            fileDiv.appendChild(a);    
                        }
                    }                
				}				
			}   
            
            xhttp.send("dir=" + dir);
        }
        
        function changeDir(dir) {
            if (dir == '..') {
                var splitDir = currentDir.split('/');
                currentDir = splitDir.slice(0, splitDir.length - 1).join("/");              
            } else {
                currentDir += '/' + dir;
            }
            getFiles("../images/" + currentDir);
        }        
        
		docReady(function() { // When the document is loaded and ready
			fileUploader.init("form-image-uploader", newConfigFileUploader); // Initialize the file uploader		
			fileUploader.setErrorHandleFunct("handleImageErrors"); // Set the error handle function of the file uploader
			fileUploader.setSuccessHandleFunct("handleImageSuccess"); // Set the succes handle function of the file uploader for further process
            getFiles("../images/" + currentDir);
		})	
        
        
         
		
		
	</script>
</html> 