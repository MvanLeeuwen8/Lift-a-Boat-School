/* File Uploader

    Author 			: 	Mark van Rooten
    Version 		:	1.0.0
    Date 			: 	17-08-2010

    File Uploader is native JavaScript written program to upload files.
 
    Read the README.md for usage and explanations.
*/


(function(window) { // Begin of library

	function fileUploaderLib() { // A function with the code for the fileUploader
		
		var _fileUploaderObject = {}; // A object to contain all our functions
		var form; // The form element which will be used will stored in here
		var filesEl; // The element that represents the files in the form
		var errorHandleFunct; // To handle the errors, this var represents the function in the main app to be triggered when errors in this library occur
		var successHandleFunct; // To handle the successful uploaded images, this var represents the function in the main app to be triggered when the file is uploaded without errors
		var errors = []; // Keep track of all the errors
		var config = {  "target_folder" : "upload", // The folder relative to program_root
                        "max_file_size" : "4768371", // Maximum file upload size (5 MB)
                        "overwrite" : 1, // Overwrite the files when uploading or not
						"extensions_allowed" : new Array("jpeg", "jpg", "png", "gif")}; // Allowed extensions, keep in mind to use jpeg also if jpg is included
        var amountOfFiles = 0;
        var uploadedCount = 0;
		/*
            To initialize  this file uploader this function is called
            It will return nothing but needs two parameters.
            formId      : the form id which is used for uploading
            settings    : a settings provided, from for example the config file, to overwrite the settings of this library
		*/
		_fileUploaderObject.init = function(formId, settings = null) {
		
			for(var setting in settings) { // Loop each setting provided
				config[setting] = settings[setting]; // Overwrite each setting, the current config settings are replaced by the parameter setting
			}
			
			form = document.getElementById(formId); // The formId is stored in the global form variable, we can nwo use it everywhere in the library
			filesEl = form.getElementsByClassName("input-file")[0]; // The same for the input-file element, we store it here in filesEl
			form.classList.add('image-uploader'); // Add the class image-uploader, found in the css file, to the form
			if (isAdvancedUpload) { // Check is we are able to drag and drop
				form.classList.add('image-uploader-drag-drop');	// Add the class image-uploader-drag-drop to the form
				form.addEventListener('dragenter', _fileUploaderObject.preventDefault, false); // Add preventDefauld to the dragenter event
				form.addEventListener('dragleave', _fileUploaderObject.preventDefault, false); // Same for dragleave
				form.addEventListener('dragover', _fileUploaderObject.preventDefault, false); // And dragover
				form.addEventListener('drop', _fileUploaderObject.preventDefault, false); // Even so for drop
				form.addEventListener('drop', _fileUploaderObject.handleDrop, false); // Add a eventhandler for the drop action, called handleDrop
				filesEl.addEventListener('change', _fileUploaderObject.uploadByBrowsing, false); // And add a handler for the change event, this is done when browsing and selecting files is done
			}		
		}

        /*
            Stop mouse activity from bubbling. In other words the mouse activity is temporarily disabled. 
            This comes out useful when fast clicks are made and double actions are to be avoided
        */
		_fileUploaderObject.preventDefault = function(e) {
			e.preventDefault(); // Stop default handling
			e.stopPropagation(); // Stop further propagation  
		}
		
        /*
            Start of handling the drop action
            e  : the object event initiater
        */
		_fileUploaderObject.handleDrop = function(e) {
			var data = e.dataTransfer; // Assign the dataTransfer   drag and drop data to data
			var files = data.files; // Assign the files to files
			_fileUploaderObject.handleFiles(files) // Call handleFiles with files as parameter   
		}
		
        /*
            Handle the files. For watch file start a upload instance and pass the file
            Before doing this, we check some validations
            files   : Files passed for uploading
        */
		_fileUploaderObject.handleFiles = function(files) {
            amountOfFiles = files.length;
            notSendErrors = true; // To check if returnErrors is called by uploadImage
            uploadedCount = 0;
			for (var i = 0, len = files.length; i < len; i++) { // Loop each file
				if (_fileUploaderObject.validateImage(files[i])) // Check if validations are met
                    notSendErrors = false; // returnErrors is called by uploadImage
					_fileUploaderObject.uploadImage(files[i]); // If so call the uploadImage and pass the file
			}
			if (notSendErrors) { // uploadImage is not called so...
                _fileUploaderObject.returnErrors(); // ..return the errors
            }
		}
		
        /*
            The file is being tested among several test
            ATM we have the check on :
                -   File extension types
                -   File size
                
            Errors definitions:
				1   : Type invalid, the user uploads a file with a extension that is not set in the config file.
				2   : File too large, the user wants to upload a file that is larger then de max_file_size setting.
                
                These errors can also be checked in the README.md
            
            file    : The file that needs to be validated
        */
		_fileUploaderObject.validateImage = function(file) {
			var validTypes = []; // Define a array for the valid extensions
			var fail = false; // Define fail as false since we have no fail yet
			
			for (var i in config['extensions_allowed']) { // Loop the extensions 
				validTypes.push('image/' + config['extensions_allowed'][i]); // And push them to validTypes with "image/" as prefix
			}
			
			var type = file.type; // Sore the file type in type
			if (!validTypes.includes(type)) { // If type not found in the extension list validType 	
				_fileUploaderObject.errorPush(file.name, 1); // Then store a error
				fail = true; // And set fail to true since we got a error
			}
			
            var size = file.size // Store the file size in size
            console.log(size)
			if (size > config['max_file_size']) { // If the file size is bigger then the maximum allowed size
				_fileUploaderObject.errorPush(file.name, 2); // Store a error
				fail = true; // Set the fail to true again
			}
			return !fail; // Return the success condition, false if we found a error if not its true
		}		
		
        /*
            The actual uploading of the file wile drag and drop
            image   : The images that must be uploaded
        */
		_fileUploaderObject.uploadImage = function(image) {
		
			var reader = new FileReader(); // Create a FileReader reader
			
			reader.readAsDataURL(image); // read the Image

			
			var formData = new FormData(); // create FormData
			formData.append('image', image);  // Append the image to the formData

			var ajax = new XMLHttpRequest(); // A new CMLHttprequest for ajax uploading the image
			
			ajax.open("POST", "../php/images-uploader-process.php?overwrite=" + config['overwrite'], true); // Start the upload and assign type and php file on the function
							   
			ajax.onreadystatechange = function(e) { // If we're done uploading 				
				if (ajax.readyState === 4) { // We finished the process and the resopnse is ready
                    uploadedCount++;
					if (ajax.status === 200) { // Successfully transfer has been made
                        if (ajax.response == 3) {
                            _fileUploaderObject.errorPush(image.name, 3); // Then store a error
                        } else {
                            _fileUploaderObject.returnSuccess(ajax.response); // Return the filename to the main programm for futher handling
                        }
					} else { // We have a error while uploading
						// Do something with the error
					}
                    if (uploadedCount == amountOfFiles) {
                        _fileUploaderObject.returnErrors(); // Return the errors to the main program, using this library
                    }
				}				
			}

			ajax.upload.onprogress = function(e) { // While we are in process
				// We can do a thing with the actual process
			}

			ajax.send(formData);			
		}


        /*
            The uploading function when browsing 
            e   : The object event initiator
        */         
		_fileUploaderObject.uploadByBrowsing = function(e) {
			_fileUploaderObject.preventDefault(e); // Prevent the input event from bubbling
			
			var files = filesEl.files; // Get the files from the form input
            
			var formData = new FormData(); // Create a FormData object		
			
            amountOfFiles = files.length;
            uploadedCount = 0;
            
			for(var file = 0; file <= files.length-1; file++ ) { // Loop each file
				if (!files[file].name) { // If is has no name it's not a file
					return; // So return and go to next file
				}	
				
				if (_fileUploaderObject.validateImage(files[file])) { // Check file validation			
					formData.append('input-files', files[file], files[file].name); // Add the file to the AJAX request		
					_fileUploaderObject.uploadByAjax(formData, files[file].name);
				} else { // Validation has not been met
					
				}			
			}
		}
         
        /*
            Upload the formDate
            formData    : The form data containing the file
        */
        _fileUploaderObject.uploadByAjax = function(formData, fileName) {
            var xhr = new XMLHttpRequest();	// Set up the request and assign to xhr					
            xhr.open('POST', '../php/images-uploader-process.php?overwrite=' + config['overwrite'], true); // Open the connection and parse the server php file to handle the file
            xhr.onreadystatechange = function (e) { // Handler for when the task for the request is complete
                if (xhr.readyState === 4) { // We finished the process and the resopnse is ready
                    uploadedCount++;
                    if (xhr.status == 200) {
                        // Upload complete and no errors
                       if (xhr.response == 3) {
                            _fileUploaderObject.errorPush(fileName, 3); // Then store a error
                        } else {
                            _fileUploaderObject.returnSuccess(xhr.response); // Return the filename to the main programm for futher handling
                        }
                    } else {
                        // xhr.error can be handled
                    }
                    if (uploadedCount == amountOfFiles) {
                        _fileUploaderObject.returnErrors(); // Return the errors to the main program, using this library
                    }
                } 
               
            };					
            xhr.send(formData); // Send the data and start transfer.
        }
        /*
            If there are error we need to push them in the same way each
            So when we push a error we get a structure in the variable errors
            name        : File name as a key in the array as unique ID
            errorValue  : The number of the error, as defined in the README.md
        */
		_fileUploaderObject.errorPush = function(name, errorValue) {
			if (!errors.hasOwnProperty(name)) { // Check if the key "name" already exists
				errors[name] = []; // If not create a key in the array and define is as a array
			}
			var errorAndTime = []; // Define a array to store the error and time 
			
			errorAndTime["error"] = errorValue; // Sore the errorValue in errorAndTime["error"]
			var d = new Date(); // Create a Date object d
			errorAndTime["time"] = d.getDate() + "-" + d.getMonth() + "-" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds(); // Store the date and time in errorAndTime["time"]
			
 			errors[name].push(errorAndTime); // Add/push the errorAndTime to the global variable errors
		}
		
        /*
            Set the function to be used in the main program to handle the errors
            funct   : The function to be used and assigned to the global variable errorHandleFunct
        */
		_fileUploaderObject.setErrorHandleFunct = function(funct) {
			errorHandleFunct = funct; // Assign the funct to errorHandleFunct
		}
    
        /*
            Return the errors to the function used for error handling, stored in errorHandleFunct
        */
		_fileUploaderObject.returnErrors = function() {
			fn = window[errorHandleFunct]; // fn is the function errorHandleFunct
			if(typeof fn === 'function') { // Wait, is it really a function?
				fn(errors); // If so then call it and parse the errors array with is
			} 
		}
        
        /*
            Set the function to be used in the main program to handle the successful uploaded images
            funct   : The function to be used and assigned to the global variable successHandleFunct
        */
		_fileUploaderObject.setSuccessHandleFunct = function(funct) {
			successHandleFunct = funct; // Assign the funct to errorHandleFunct
		}
        
        /*
            Return the errors to the function used for error handling, stored in errorHandleFunct
        */
		_fileUploaderObject.returnSuccess = function(imageName) {
			fn = window[successHandleFunct]; // fn is the function errorHandleFunct
			if(typeof fn === 'function') { // Wait, is it really a function?
				fn(imageName); // If so then call it and parse the errors array with is
			} 
		}
		
		return _fileUploaderObject; // Return the object _fileUploaderObject for this library
	}

	
	if(typeof(window.fileUploader) === 'undefined') { // If there is no fileUploader function then create is global
		window.fileUploader = fileUploaderLib(); // And assign the library to fileUploader
	}
})(window); // End of library


/*
* Check if the drag and drop functinality is available in the browser.
*/
var isAdvancedUpload = function() {
	var div = document.createElement('div'); // Create a div element
	return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div)) && 'FormData' in window && 'FileReader' in window; // Check the stats for drag and drop
}();




		 





