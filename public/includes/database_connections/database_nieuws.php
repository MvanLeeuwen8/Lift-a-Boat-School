<?php
//region Send to database - nieuws
// on form-sending, send to database
if (isset($_POST['nieuws'])) {

    // setting values to variables
    $title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
    $newsText = $_POST['news-text'];

    // Set variables for images
    $newsImageNames = "";
    $imageTextArray = "";

    //region Uploads - nieuws
    //checking if file is uploaded or not
    if (!isset($_FILES) || $_FILES['input-files']['error'][0] == 4) {

        // inserting it into the nieuws-table
        $sql = "INSERT INTO nieuws (title, news_article) VALUES 
                                           ('$title', '$newsText')";

        // Success and error notifications for user
        if ($conn->query($sql) === TRUE) {
            $uploadError = "Het nieuwsartikel is toegevoegd.";
            $newsSuccess = "AddSuccessful";
        } else {
            print_r($conn);
            $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op.";
            $newsSuccess = "AddError";
        }

    } else {

        // Set uploadOk to 1 before the loop
        $uploadOk = 1;
        $uploadCheck = true;

        //region Maximum file size
        // set maximum file size
        $maxFileSize = 20 * 1024 * 1024; // 20MB

        // Check if the combined sized of all images are less than 20MB
        $totalFileSize = array_sum($_FILES['input-files']['size']);
        if ($totalFileSize > $maxFileSize) {
            $uploadOk = 0;
            $uploadError = "Your files exceed the maximum upload limit of 20MB";
        }

        // Continue if there was a file-upload and it was less than 20MB
        $inputFile = $_FILES['input-files']['name']; //array
        //endregion

        // Set target directory
        $target_dir = "../uploads/"; //file directory in root

        foreach ($inputFile as $x => $picture) { // loop through the array

            //region check if the upload is an image
            // Get image file extension
            $imageFileType = "." . strtolower(pathinfo($picture, PATHINFO_EXTENSION));

            // checking if file is actually an img (jpg,jpeg,png,gif)
            if($imageFileType != ".jpg" && $imageFileType != ".png" && $imageFileType != ".jpeg"
                && $imageFileType != ".gif" && $imageFileType != ".pjpeg" ) {
                $uploadError = "Error: Only files of the types of JPG, JPEG, PJPEG, PNG and GIF are allowed.<br>";
                $uploadOk = 0;
            }
            //endregion

            // Get file name
            $oldFileName = strtolower($_FILES['input-files']['name'][$x]);

            // Set filename notation: name_of_image-datetime.jpg
            $newFileName = str_replace(" ", "_",strtolower(basename($picture, $imageFileType) . "-" . date("Y-m-d_H-i-s") . $imageFileType));

            if (!is_dir($target_dir)) {
                mkdir(strtolower($target_dir), 0777, true);
            }

            // Check if file already exists
            if (file_exists($target_dir . $newFileName)) {
                $uploadError = "Sorry, file already exists.<br>";
                $uploadOk = 0;
            }

            $filePath = $target_dir . $newFileName;

            // if the upload is valid, upload the file
            if ($uploadOk == 1 ) {
                if (move_uploaded_file($_FILES['input-files']['tmp_name'][$x], $filePath)) {
                    if ($newsImageNames === "") {
                        $newsImageNames = strval($newFileName);
                    } else {
                        $newsImageNames = $newsImageNames . ", " . strval($newFileName);
                    }
                    if ($imageTextArray === "") {
                        $imageTextArray = "___ ";
                    }
                    else {
                        $imageTextArray = $imageTextArray . "___ " . "___ ";
                    }
                } else {
                    $uploadCheck = false;
                }
            } else {
                $uploadCheck = false;
            }

        } //end of loop

        // If upload is valid and the upload was successful, continue
        if ($uploadOk == 1) {
            if ($uploadCheck != false) {

                // inserting it into the nieuws-table
                $sql = "INSERT INTO nieuws (title, news_article, news_images, news_images_text) VALUES 
                                           ('$title', '$newsText', '$newsImageNames', '$imageTextArray')";

                // Success and error notifications for user
                if ($conn->query($sql) === TRUE) {
                    $uploadError = "Het nieuwsartikel is toegevoegd.";
                    $newsSuccess = "AddSuccessful";
                } else {
                    print_r($conn);
                    $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op.";
                    $newsSuccess = "AddError";
                }
            } else {
                echo "There was an error uploading your file, please try again later.";
            }
        } else {
            // if unknown error with uploading, show error
            echo "Sorry, there was an error uploading your file.";
            $_SESSION['uploadError'] = "Unknown error: there was an error uploading your file. Please try again in 5 minutes. If this error keeps persisting, please contact us.";
        }
    }
    //endregion
}

//endregion

//region getNews() -> Display news on website
    function getNews() {
        // Create connection
        global $servername, $username, $password, $database;
        $conn = mysqli_connect( $servername, $username, $password, $database);
        // Check connection
        if (!$conn) {
            //error logging
            $error_message = "Connection failed: " . mysqli_connect_error();
            $log_file = "../../../logs/error_log.log";
            error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT * FROM nieuws
                ORDER BY add_date DESC";
        $result = mysqli_query($conn, $sql);

        // getting the boats from the database and showing them on the page
        if($result->num_rows > 0) {

            // start of destinations area
            echo "<div class='news-all'>";

            // start of loop
            while($row = mysqli_fetch_assoc($result)) {

                // Start news article with id
                echo "<div class='news-single' id='" . strtolower(filter_var($row['title'], FILTER_SANITIZE_STRING)) . "'>";

                    // Title
                    echo "<h2>" . $row['title'] . "</h2>";
                    if ($_SESSION['isAdmin'] === true) {
                        echo "<div class='edit-delete-buttons'>";
                            echo "<a href='update-news.php?id=" . $row['id'] . "'><button class='form-button'>Aanpassen</button></a>";
                            echo "<a href='includes/delete_update/delete-news.php?id=" . $row['id'] . "'><button class='form-button'>Verwijderen</button></a>";
                        echo "</div>";
                    }
                    // Publishing date
                    echo "<p class='publish-time'>Gepubliceerd op: " . date('d-m-Y', strtotime($row['add_date'])) . "</p>";

                    // Main image and image-caption
                    if ($row['news_images'] != "" || $row['news_images'] != null || $row['news_images'] != false) {
                        echo "<div class='main-image-news'>";
                            echo "<img src='../uploads/" . explode(", ", $row['news_images'])[0] . "'>";
                            echo "<figcaption>" . explode("___ ", $row['news_images_text'])[0] . "</figcaption>";
                        echo "</div>";
                    }

                    // News text
                    echo "<div class='news-text'>";
                        echo $row['news_article'];
                    echo "</div>";

                    //image-gallery
                    echo "<div class='news-image-gallery'>";
                    if ($row['news_images'] != "" || $row['news_images'] != null || $row['news_images'] != false) {
                        for ($i = 0; $i < count(explode(", ", $row['news_images'])); $i++) {
                            $imageArray = explode(", ", $row['news_images']);
                            $textArray = explode("___ ", $row['news_images_text']);
                            echo "<div id='image-" . $row['id'] . "-" . $i .  "' class=single-image>";
                                echo "<img src='../uploads/" . $imageArray[$i] . "' onClick='openPicture(" . $i . "," . $row['id'] . ")'>";
                                echo "<figcaption>" . $textArray[$i] . "</figcaption>";
                            echo "</div>";
                        }
                        echo "</div>";
                    }

                    ?>

                <script>
                //region Modal function
                // Get the modal
                let modal<?=$row['id']?> = document.getElementById("foto-modal");

                // When the user clicks the button, open the modal
                function openPicture(pictureId, newsId) {
                    modal<?=$row['id']?>.style.display = "block";
                    let clickedImage = document.getElementById("image-" + newsId + "-" + pictureId).getElementsByTagName("img")[0].src;
                    let clickedImageText = document.getElementById("image-" + newsId + "-" + pictureId).getElementsByTagName("figcaption")[0].innerText;
                    document.getElementById("modal-image").src = clickedImage;
                    document.getElementById("modal-image").dataset.destination = newsId;
                    document.getElementById("modal-image").dataset.pictureNumber = pictureId;
                    document.getElementById("img-text").innerText = clickedImageText;
                }

                // When the user clicks on <span> (x), close the modal
                document.getElementById("close").onclick = function() {
                    modal<?=$row['id']?>.style.display = "none";
                }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal<?=$row['id']?>) {
                    modal<?=$row['id']?>.style.display = "none";
                    }
                }
                //endregion

                </script>


                    <?php

                //end news article
                echo "</div>";

            }

            // end of news-area
            echo "</div>";
        } else {
            // if there aren't any destinations, show this:
            echo "Er zijn nog geen nieuwsartikelen.";
        }

    }
//endregion