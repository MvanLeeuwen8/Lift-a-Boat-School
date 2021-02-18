<!doctype html>
<html lang="en">
<head>

    <?php
    session_start();
    ?>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- update-list -->
    <script src="js/update-list.js"></script>

    <!-- Tiny editor -->
    <script src="https://cdn.tiny.cloud/1/2ppygj1ewlkg3tgte1ikcbuz3gpxzdn3cierwxjmjh88bepv/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector:'#news-textarea',
            plugins: 'image',
            menu: {
                file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
                edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
                view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
                insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
                tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
                table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
                help: { title: 'Help', items: 'help' }
            },
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image',

            //region source: https://www.tiny.cloud/docs/demo/file-picker/
            /* enable title field in the Image dialog */
            image_title: true,

            /* enable automatic uploads of images represented by blob or data URIs */
            automatic_uploads: true,

            /*URL of our upload handler (for more details check:
            https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url) */
            images_upload_url: 'includes/postAcceptor_tiny.php',

            //here we add custom filepicker only to Image dialog
            file_picker_types: 'image',
            /* and here's our custom image picker*/
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                /*
                  Note: In modern browsers input[type="file"] is functional without
                  even adding it to the DOM, but that might not be the case in some older
                  or quirky browsers like IE, so you might want to add it to the DOM
                  just in case, and visually hide it. And do not forget do remove it
                  once you do not need it anymore.
                */

                input.onchange = function () {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function () {
                        /*
                          Note: Now we need to register the blob in TinyMCEs image blob
                          registry. In the next release this part hopefully won't be
                          necessary, as we are looking to handle it internally.
                        */
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
            //endregion
        });
    </script>

    <?php
    include 'includes/variables.php';
    ?>

    <link rel="stylesheet" href="styles/main.css" type="text/css">
</head>

<script>
    // function on cancel button
    function backToNews() {
        location.href="nieuws.php";
    }
</script>

<?php
// check if the one trying to edit is an admin, if they are, show the content
if ($_SESSION["isAdmin"] === true) {

    //region connect to database and show data

    // To get the database info:
    include 'includes/config.php';

    // setting id to the id of the one you want to update
    $id = $_GET['id'];

    //Connect to database
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        //error logging
        $error_message = "Connection failed: " . mysqli_connect_error();
        $log_file = "../logs/error_log.log";
        error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
        die("Connection failed: " . mysqli_connect_error());
    }

    // only select the row where the id matches
    $sql = "SELECT * FROM nieuws WHERE id = '$id'";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>Aanpassen " . $row['title'] . " - Lift a Boat</title>";

            // form with values already filled in and fetched from the database
            // form start
            echo "<form class='edit-form' action='' method='post' enctype='multipart/form-data'>";

            // Title
            echo "<div class='edit-news'><label>Titel: </label><input type='text' name='newTitle' value='" . $row['title'] . "' required></div><br>";

            // Text
            echo "
                <div class='news-form-section'>
                        <div class='text-section'>
                            <label for='news-text'>Tekst:</label>
                            <textarea name='newNewsText' id='news-textarea' cols='auto' rows='15'>" . $row['news_article'] . "</textarea>
                        </div>
                </div>
               ";

            // Image
            // Image string to array
            $imageArray = explode(", ", $row['news_images']);
            $imageText = explode("___ ", $row['news_images_text']);

            //If no image, show this:
            if (!array_filter($imageArray)) {
                echo "<p>Geen afbeeldingen gevonden.";
            } else {
                echo "<a href='includes/delete_update/update_image_order_news.php?id=" . $row['id'] . "'><input type='button' value='Volgorde aanpassen' class='changeOrder'></input></a><ul style='padding:0'>";
                //Loop image array
                for ($i = 0; $i < count($imageArray); $i++) {
                    //Image
                    echo "<li class='listItem'><div class='imagebutton'><img src='../uploads/" . $imageArray[$i] . "'><br>";
                    //Delete image-button
                    echo "<a href='includes/delete_update/delete-image-news.php?id=" . $row['id'] . "&image=" . $imageArray[$i] . "'><input type='button' value='Verwijder afbeelding' class='deleteImage'></input></a>";
                    //Add text to image
                    echo "<label for='image-text'>Afbeelding tekst:</label><textarea name='image-text-" . $i ."' rows='3'>". $imageText[$i] . "</textarea><br>";

                    // Use as default checkbox
                    echo "</div></li>";
                }
            }

            echo "</ul><div class='edit-news-img'><label for='files'>Afbeeldingen toevoegen:</label><label class='upload-btn' for='files'>Bestanden kiezen</label>" . "<input class='input-file' type='file' name='input-files[]' id='files' onchange='updateList()' accept='image/jpg, image/png, image/jpeg, image/gif, image/pjpeg' multiple hidden></div><br>";
            echo "<div id=fileList></div>";

            // submit and cancel buttons
            echo "<div class='edit-buttons'><input type=submit value='Opslaan' name='updateNews'>";
            echo "<input type=button value=Annuleren onclick='backToNews()'></div>";
            echo "</form>";
        }
    }
    //endregion

    //region clicked submit-button
    if (isset($_POST['updateNews'])) {

        $totalFileSize = array_sum($_FILES['input-files']['size']);

        // Select the right row
        $sql = "SELECT * FROM nieuws WHERE id = '$id'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {

            $imageArray = explode(", ", $row['news_images']);

            $newTextArrayString = "";
            for ($i = 0; $i < count($imageArray); $i++) {
                if($newTextArrayString == "") {
                    $newTextArrayString = $_POST['image-text-' . $i];
                } else {
                    $newTextArrayString = $newTextArrayString . "___ " . $_POST['image-text-' . $i];
                }
            }
            $newTextArray = explode("___ " ,$newTextArrayString);

            // Check if another file has been uploaded
            if ($totalFileSize == 0) {

            //region no new image
                // Putting the filled-in values into variables, not including the image
                $newTitle = filter_var($_POST['newTitle'], FILTER_SANITIZE_STRING);
                $newNewsText = $_POST['newNewsText'];

                $newTextArrayString = str_replace("'", "\'", $newTextArrayString);
                $newNewsText = str_replace("'", "\'", $newNewsText);

                // Insert the values into the table
                $sql = "UPDATE nieuws SET 
                                title = '$newTitle',
                                news_article = '$newNewsText',
                                news_images_text = '$newTextArrayString'
                        WHERE id = '$id';";

                // Success and error notifications for user
                if ($conn->query($sql) === TRUE) {
                    echo("<script>location.href = 'nieuws.php';</script>");
                } else {
                    print_r($conn);
                    $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                    echo "<p style='text-align: center; color:#fff;'>Error updating news</p>";
                    $newsSuccess = "UpdateError";
                }
            //endregion
            } else {
            //region with new image

                // Set uploadOk to 1 before the loop
                $uploadOk = 1;
                $uploadCheck = true;

                //region Maximum file size
                // set maximum file size
                $maxFileSize = 20 * 1024 * 1024; // 20MB

                // Check if the combined sized of all images are less than 20MB
                if ($totalFileSize > $maxFileSize) {
                    $uploadOk = 0;
                    $uploadError = "Your files exceed the maximum upload limit of 20MB";
                }

                // Continue if there was a file-upload and it was less than 20MB
                $inputFile = $_FILES['input-files']['name']; //array

                //endregion

                $newTitle = filter_var($_POST['newTitle'], FILTER_SANITIZE_STRING);

                // Set target directory
                $target_dir = "../uploads/"; //file directory in root

                $newsImages = $row['news_images'];

                foreach ($inputFile as $x => $picture) { // loop through the array

                    //region check if the upload is an image
                    // Get image file extension
                    $imageFileType = "." . strtolower(pathinfo($picture, PATHINFO_EXTENSION));

                    // checking if file is actually an img (jpg,jpeg,png,gif)
                    if ($imageFileType != ".jpg" && $imageFileType != ".png" && $imageFileType != ".jpeg"
                        && $imageFileType != ".gif" && $imageFileType != ".pjpeg") {
                        $uploadError = "Error: Only files of the types of JPG, JPEG, PJPEG, PNG and GIF are allowed.<br>";
                        $uploadOk = 0;
                    }
                    //endregion

                    // Get file name
                    $oldFileName = strtolower($_FILES['input-files']['name'][$x]);

                    // Set filename notation: name_of_image-datetime.jpg
                    $newFileName = str_replace(" ", "_", strtolower(basename($picture, $imageFileType) . "-" . date("Y-m-d_H-i-s") . $imageFileType));

                    // Check if file already exists
                    if (file_exists($target_dir . $newFileName)) {
                        $uploadError = "Sorry, file already exists.<br>";
                        $uploadOk = 0;
                    }

                    $filePath = $target_dir . $newFileName;

                    // if the upload is valid, upload the file
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES['input-files']['tmp_name'][$x], $filePath)) {
                            //if the upload was okay, check if $newsImages already had a value
                            if ($newsImages === "") {
                                // If it didn't have a value yet, set it to the new file name
                                $newsImages = strval($newFileName);
                            } else {
                                // If it already had a value, add the new file name to the value
                                $newsImages = $newsImages . ", " . strval($newFileName);
                            }
                        } else {
                            // if the upload didn't work, set uploadCheck to false
                            $uploadCheck = false;
                        }
                    } else {
                        // if some condition wasn't met during uploading, set uploadCheck to false
                        $uploadCheck = false;
                    }

                } //end of loop

                if ($uploadOk == 1) {
                    if ($uploadCheck != false) {
                        //If the upload succeeded

                        // Putting the filled-in values into variables
                        $newTitle = filter_var($_POST['newTitle'], FILTER_SANITIZE_STRING);
                        $newNewsText = $_POST['newNewsText'];

                        // Updating the values in the table
                        $sql = "UPDATE nieuws SET 
                                title = '$newTitle',
                                news_article = '$newNewsText',
                                news_images = '$newsImages',
                                news_images_text = '$newTextArrayString'
                        WHERE id = '$id';";

                        // Success and error notifications for user
                        if ($conn->query($sql) === TRUE) {
                            echo("<script>location.href = 'nieuws.php';</script>");
                        } else {
                            print_r($conn);
                            $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                            echo "<p style='text-align: center; color:#fff;'>Error updating news</p>";
                            $newsSuccess = "UpdateError";
                        }
                    } else {
                        echo "There was an error uploading your file, please try again later.";
                    }
                } else {
                    echo "Sorry, there was an error uploading your file.";
                    $_SESSION['uploadError'] = "Unknown error: there was an error uploading your file. Please try again in 5 minutes. If this error keeps persisting, please contact " . $mail . ".";
                }
                //endregion
            }
        }
    }
    //endregion
} else {
    //if they are not an admin, show them to the homepage
    header( "location:index.php");
}

?>
</html>