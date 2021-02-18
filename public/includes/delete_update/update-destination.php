<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <?php
        include '../variables.php';
    ?>

    <link rel="stylesheet" href="../../styles/main.css" type="text/css">

    <?php
    session_start();
    ?>
</head>

<!-- update list -->
<script src="../../js/update-list.js"></script>

<script>
    // function on cancel button
    function backToDestinations() {
        location.href="../../bestemmingen.php";
    }
</script>

<?php
// check if the one trying to edit is an admin, if they are, show the content
if ($_SESSION["isAdmin"] === true) {

    //region connect to database and show data

    // To get the database info:
    include '../config.php';

    // setting id to the id of the one you want to update
    $id = $_GET['id'];

    //Connect to database
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        //error logging
        $error_message = "Connection failed: " . mysqli_connect_error();
        $log_file = "../../../logs/error_log.log";
        error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
        die("Connection failed: " . mysqli_connect_error());
    }

    // only select the row where the id matches
    $sql = "SELECT * FROM bestemmingen WHERE id = '$id'";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>Aanpassen " . $row['destination'] . " - Lift a Boat</title>";

            // form with values already filled in and fetched from the database
            // form start
            echo "<form class='edit-form' action='' method='post' enctype='multipart/form-data'>";

            // Destination
            echo "<div class='edit-destination'><label>Bestemming: </label><input type='text' name='newDestination' value='" . $row['destination'] . "' required></div><br>";

            // Date
            if ($row['departure_date'] == "1970-01-01") {
                $shownDate = "";
            } else {
                $shownDate = $row['departure_date'];
            }
            echo "<div class=edit-destination-date><label>Vertrekdatum:</label><input type='date' name='newDate' value='" . $shownDate ."'></div><br>";

            //region Price
            echo "<span>Prijzen:</span>";
            echo '<p style="color:red">Als er geen prijs is ingevuld of als de prijs gelijk is aan 0, wordt deze gezet als "op aanvraag"</p>';

            // left side
            echo '<div class=edit-price-left>';
                // 9-12m
                echo "<div class='edit-price-9-12'><label>Prijs 9-12m:</label><input type='number' name='newPrice-9-12' value='" . $row['price_9_12'] . "' min='0' step='0.01'></div><br>";

                // 13m
                echo "<div class='edit-price-13'><label>Prijs 13m:</label><input type='number' name='newPrice-13' value='" . $row['price_13'] . "' min='0' step='0.01'></div><br>";

                // 14m
                echo "<div class='edit-price-14'><label>Prijs 14m:</label><input type='number' name='newPrice-14' value='" . $row['price_14'] . "' min='0' step='0.01'></div><br>";

                // 15m
                echo "<div class='edit-price-15'><label>Prijs 15m:</label><input type='number' name='newPrice-15' value='" . $row['price_15'] . "' min='0' step='0.01'></div><br>";

                // 16m
                echo "<div class='edit-price-16'><label>Prijs 16m:</label><input type='number' name='newPrice-16' value='" . $row['price_16'] . "' min='0' step='0.01'></div><br>";
            echo '</div>';

            //right side
            echo '<div class=edit-price-right>';

                // 17m
                echo "<div class='edit-price-17'><label>Prijs 17m:</label><input type='number' name='newPrice-17' value='" . $row['price_17'] . "' min='0' step='0.01'></div><br>";

                // 18m
                echo "<div class='edit-price-18'><label>Prijs 18m:</label><input type='number' name='newPrice-18' value='" . $row['price_18'] . "' min='0' step='0.01'></div><br>";

                // 19m
                echo "<div class='edit-price-19'><label>Prijs 19m:</label><input type='number' name='newPrice-19' value='" . $row['price_19'] . "' min='0' step='0.01'></div><br>";

                // 20m
                echo "<div class='edit-price-20'><label>Prijs 20m:</label><input type='number' name='newPrice-20' value='" . $row['price_20'] . "' min='0' step='0.01'></div><br>";

            echo '</div>';

            //endregion

            // Description
            echo "<div class=edit-destination-description-short><label>Korte beschrijving:</label><textarea name='newShortDescription' id='newShortDescription' rows='5' cols='auto' required>" . $row['description_short'] . "</textarea></div><br>";

            echo "<div class='edit-destination-description-long'><label>Lange beschrijving:</label><textarea name='newLongDescription' id='newLongDescription' rows='9' cols='auto' required>" . $row['description_long'] . "</textarea></div><br>";

            // Image
                // Image string to array
                $imageArray = explode(", ", $row['destination_image_names']);
                $imageText = explode("___ ", $row['destination_image_text']);

                echo "<div class='images-gallery'>";
                //If no image, show this:
                if (!array_filter($imageArray)) {
                    echo "<p>Geen afbeeldingen gevonden. Voeg een afbeelding toe.</p>";
                } else {
                    //change image order
                    echo "<a href='update_image_order_destinations.php?id=" . $row['id'] . "'><input type='button' value='Volgorde aanpassen' class='changeOrder'></input></a>";
                    echo "<ul id='imageList'>";
                    //Loop image array
                    for ($i = 0; $i < count($imageArray); $i++) {
                        //Image
                        echo "<li class='listItem'><div class='imagebutton'><img src='../../../uploads/" . $imageArray[$i] . "'><br>";
                        //Image file-name
                        echo "<p class='image-filename'>" . $imageArray[$i] . "</p>";
                        //Delete image-button
                        echo "<a href='delete-image-destination.php?id=" . $row['id'] . "&image=" . $imageArray[$i] . "'><input type='button' value='Verwijder afbeelding' class='deleteImage'></input></a>";
                        //Add text to image
                        echo "<label for='image-text'>Afbeelding tekst:</label><textarea name='image-text-" . $i ."' rows='3'>". $imageText[$i] . "</textarea><br>";

                        echo "</div></li>";
                    }
                    echo "</ul>";
                }
                echo "</div>";

            echo "<div class='edit-destination-img'><label for='files'>Afbeeldingen toevoegen:</label><label class='upload-btn' for='files'>Bestanden kiezen</label>" . "<input class='input-file' type='file' name='input-files[]' id='files' onchange='updateList()' accept='image/jpg, image/png, image/jpeg, image/gif, image/pjpeg' multiple hidden></div><br>";
            echo "<div id=fileList></div>";

            // submit and cancel buttons
            echo "<div class='edit-buttons'><input type=submit value='Opslaan' name='updateDestination'>";
            echo "<input type=button value=Annuleren onclick='backToDestinations()'></div>";
            echo "</form>";
        }
    }
    //endregion

    //region clicked submit-button
    if (isset($_POST['updateDestination'])) {

        $totalFileSize = array_sum($_FILES['input-files']['size']);

        // Select the right row
        $sql = "SELECT * FROM bestemmingen WHERE id = '$id'";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {

            $imageArray = explode(", ", $row['destination_image_names']);

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

                // Image string to array
                $imageArray = explode(", ", $row['destination_image_names']);

                // Image text to array
                $textArray = explode("___ ", $row['destination_image_text']);

                // If no image has been uploaded and the image array is empty, give an error:
                if (!array_filter($imageArray)) {
                    echo "<p>Geen afbeelding ingevoegd. Upload eerst een afbeelding</p>";
                    die();
                }

                // Putting the filled-in values into variables, not including the image
                if (!empty($_POST['newDate'])){
                    $newDate = $_POST['newDate'];
                } else {
                    $newDate = '1970-01-01';
                }
                $newDestination = filter_var($_POST['newDestination'], FILTER_SANITIZE_STRING);
                $newShortDescription = filter_var($_POST['newShortDescription'], FILTER_SANITIZE_STRING);
                $newLongDescription = filter_var($_POST['newLongDescription'], FILTER_SANITIZE_STRING);
                $newPrice_9_12 = filter_var($_POST['newPrice-9-12'], FILTER_VALIDATE_FLOAT);
                $newPrice_13 = filter_var($_POST['newPrice-13'], FILTER_VALIDATE_FLOAT);
                $newPrice_14 = filter_var($_POST['newPrice-14'], FILTER_VALIDATE_FLOAT);
                $newPrice_15 = filter_var($_POST['newPrice-15'], FILTER_VALIDATE_FLOAT);
                $newPrice_16 = filter_var($_POST['newPrice-16'], FILTER_VALIDATE_FLOAT);
                $newPrice_17 = filter_var($_POST['newPrice-17'], FILTER_VALIDATE_FLOAT);
                $newPrice_18 = filter_var($_POST['newPrice-18'], FILTER_VALIDATE_FLOAT);
                $newPrice_19 = filter_var($_POST['newPrice-19'], FILTER_VALIDATE_FLOAT);
                $newPrice_20 = filter_var($_POST['newPrice-20'], FILTER_VALIDATE_FLOAT);

                //region prices
                if ($newPrice_9_12 == null || $newPrice_9_12 == "") {
                    $newPrice_9_12 = 0.00;
                }
                if ($newPrice_13 == null || $newPrice_13 == "") {
                    $newPrice_13 = 0.00;
                }
                if ($newPrice_14 == null || $newPrice_14 == "") {
                    $newPrice_14 = 0.00;
                }
                if ($newPrice_15 == null || $newPrice_15 == "") {
                    $newPrice_15 = 0.00;
                }
                if ($newPrice_16 == null || $newPrice_16 == "") {
                    $newPrice_16 = 0.00;
                }
                if ($newPrice_17 == null || $newPrice_17 == "") {
                    $newPrice_17 = 0.00;
                }
                if ($newPrice_18 == null || $newPrice_18 == "") {
                    $newPrice_18 = 0.00;
                }
                if ($newPrice_19 == null || $newPrice_19 == "") {
                    $newPrice_19 = 0.00;
                }
                if ($newPrice_20 == null || $newPrice_20 == "") {
                    $newPrice_20 = 0.00;
                }
                //endregion

                // Insert the values into the table
                $sql = "UPDATE bestemmingen SET 
                                destination = '$newDestination',
                                departure_date = '$newDate',
                                description_short = '$newShortDescription',
                                description_long = '$newLongDescription',
                                price_9_12 = '$newPrice_9_12',
                                price_13 = '$newPrice_13',
                                price_14 = '$newPrice_14',
                                price_15 = '$newPrice_15',
                                price_16 = '$newPrice_16',
                                price_17 = '$newPrice_17',
                                price_18 = '$newPrice_18',
                                price_19 = '$newPrice_19',
                                price_20 = '$newPrice_20',
                                destination_image_text = '$newTextArrayString'                                
                        WHERE id = '$id';";

                // Success and error notifications for user
                if ($conn->query($sql) === TRUE) {

                    echo("<script>location.href = '../../bestemmingen.php';</script>");
                } else {
                    print_r($conn);
                    $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                    echo "<p style='text-align: center; color:#fff;'>Error updating destination</p>";
                    $destinationSuccess = "UpdateError";
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

                $newDestination = filter_var($_POST['newDestination'], FILTER_SANITIZE_STRING);

                // Set target directory
                $target_dir = "../../../uploads/"; //file directory in root

                $destinationImageNames = $row['destination_image_names'];

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

                    // Set filename notation: destination-name_of_image-datetime.jpg
                    $newFileName = str_replace(" ", "_", strtolower($_POST['newDestination'] . "-" . basename($picture, $imageFileType) . "-" . date("Y-m-d_H-i-s") . $imageFileType));

                    // Check if file already exists
                    if (file_exists($target_dir . $newFileName)) {
                        $uploadError = "Sorry, file already exists.<br>";
                        $uploadOk = 0;
                    }

                    $filePath = $target_dir . $newFileName;

                    // if the upload is valid, upload the file
                    if ($uploadOk == 1) {
                        if (move_uploaded_file($_FILES['input-files']['tmp_name'][$x], $filePath)) {
                            //if the upload was okay, check if $destinationImageNames already had a value
                            if ($destinationImageNames === "") {
                                // If it didn't have a value yet, set it to the new file name
                                $destinationImageNames = strval($newFileName);
                            } else {
                                // If it already had a value, add the new file name to the value
                                $destinationImageNames = $destinationImageNames . ", " . strval($newFileName);
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
                        if (!empty($_POST['newDate'])){
                            $newDate = $_POST['newDate'];
                        } else {
                            $newDate = '1970-01-01';
                        }
                        $newShortDescription = filter_var($_POST['newShortDescription'], FILTER_SANITIZE_STRING);
                        $newLongDescription = filter_var($_POST['newLongDescription'], FILTER_SANITIZE_STRING);
                        $newPrice_9_12 = filter_var($_POST['newPrice-9-12'], FILTER_VALIDATE_FLOAT);
                        $newPrice_13 = filter_var($_POST['newPrice-13'], FILTER_VALIDATE_FLOAT);
                        $newPrice_14 = filter_var($_POST['newPrice-14'], FILTER_VALIDATE_FLOAT);
                        $newPrice_15 = filter_var($_POST['newPrice-15'], FILTER_VALIDATE_FLOAT);
                        $newPrice_16 = filter_var($_POST['newPrice-16'], FILTER_VALIDATE_FLOAT);
                        $newPrice_17 = filter_var($_POST['newPrice-17'], FILTER_VALIDATE_FLOAT);
                        $newPrice_18 = filter_var($_POST['newPrice-18'], FILTER_VALIDATE_FLOAT);
                        $newPrice_19 = filter_var($_POST['newPrice-19'], FILTER_VALIDATE_FLOAT);
                        $newPrice_20 = filter_var($_POST['newPrice-20'], FILTER_VALIDATE_FLOAT);


                        //region prices
                        if ($newPrice_9_12 == null || $newPrice_9_12 == "") {
                            $newPrice_9_12 = 0.00;
                        }
                        if ($newPrice_13 == null || $newPrice_13 == "") {
                            $newPrice_13 = 0.00;
                        }
                        if ($newPrice_14 == null || $newPrice_14 == "") {
                            $newPrice_14 = 0.00;
                        }
                        if ($newPrice_15 == null || $newPrice_15 == "") {
                            $newPrice_15 = 0.00;
                        }
                        if ($newPrice_16 == null || $newPrice_16 == "") {
                            $newPrice_16 = 0.00;
                        }
                        if ($newPrice_17 == null || $newPrice_17 == "") {
                            $newPrice_17 = 0.00;
                        }
                        if ($newPrice_18 == null || $newPrice_18 == "") {
                            $newPrice_18 = 0.00;
                        }
                        if ($newPrice_19 == null || $newPrice_19 == "") {
                            $newPrice_19 = 0.00;
                        }
                        if ($newPrice_20 == null || $newPrice_20 == "") {
                            $newPrice_20 = 0.00;
                        }
                        //endregion

                        // Updating the values in the table
                        $sql = "UPDATE bestemmingen SET 
                                destination = '$newDestination',
                                departure_date = '$newDate',
                                description_short = '$newShortDescription',
                                description_long = '$newLongDescription',
                                destination_image_names = '$destinationImageNames',
                                destination_image_text = '$newTextArrayString',
                                price_9_12 = '$newPrice_9_12',
                                price_13 = '$newPrice_13',
                                price_14 = '$newPrice_14',
                                price_15 = '$newPrice_15',
                                price_16 = '$newPrice_16',
                                price_17 = '$newPrice_17',
                                price_18 = '$newPrice_18',
                                price_19 = '$newPrice_19',
                                price_20 = '$newPrice_20'
                        WHERE id = '$id';";

                        // Success and error notifications for user
                        if ($conn->query($sql) === TRUE) {
                            echo("<script>location.href = 'update-destination.php?id=' + " . $row['id'] . " + ;</script>");
                        } else {
                            print_r($conn);
                            $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                            echo "<p style='text-align: center; color:#fff;'>Error updating destination</p>";
                            $destinationSuccess = "UpdateError";
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
    header( "location:../../index.php");
}

?>

</html>