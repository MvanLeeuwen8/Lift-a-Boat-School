--==[FILE-UPLOADER]==-

-Features

File size check
Overwrite existing file yes/no

-How to install/use

Copy the file-uploader folder and its content to the ./vendor/ folder.

Use the configuration file, existing in ./includes/cofig/php to set configuration.

Parse the configuration settings to the file-uploader library to overwrite the library settings.
Example:
    <?php include_once "../includes/config.php"; ?>
    <script src="../vendor/file-uploader/js/file-uploader.js"></script>
    <script>
        var newConfigFileUploader = <?php echo json_encode($CONFIG['file_uploader']); ?>;
        fileUploader.init("form-image-uploader", newConfigFileUploader); // Initialize the file uploader		
    </script>
    
To handle the error and succes results set the following two functions:
    fileUploader.setErrorHandleFunct("handleImageErrors"); // Set the error handle function of the file uploader
    fileUploader.setSuccessHandleFunct("handleImageSuccess"); // Set the succes handle function of the file uploader for further process
            
-Error results
    The form in which the errors are resulted are like:
    errors['file_name1'][0]['time'];
    errors['file_name1'][0]['error'];
    
    error possibly contans:
    1   : Type invalid, the user uploads a file with a extension that is not set in the config file.
    2   : File too large, the user wants to upload a file that is larger then de max_file_size setting.
                
			
            

