<?php
//region Send to database - bestemmingen
// on form-sending, send to database
if (isset($_POST['bestemmingen'])) {

    //region Uploads - bestemmingen
    //checking if file is uploaded or not
    if (empty($_FILES)) {

        // If no file was uploaded: error
        $uploadError = "Please upload an image";
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

        $destination = str_replace(str_split('\\/:*?"<>|'), "", filter_var($_POST['bestemmingsplaats'], FILTER_SANITIZE_STRING));

        // set variables before loop
        $destinationImageNames = "";
        $imageTextArray = "";

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

            // Set filename notation: destination-name_of_image-datetime.jpg
            $newFileName = str_replace(" ", "_",strtolower($destination . "-" . basename($picture, $imageFileType) . "-" . date("Y-m-d_H-i-s") . $imageFileType));

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
                    if ($destinationImageNames === "") {
                        $destinationImageNames = strval($newFileName);
                    } else {
                        $destinationImageNames = $destinationImageNames . ", " . strval($newFileName);
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

                // setting values to variables
                $departureDate = $_POST['vertrekdatum'];
                $descriptionShort = filter_var($_POST['bestemmingsinfo-kort'], FILTER_SANITIZE_STRING);
                $descriptionLong = filter_var($_POST['bestemmingsinfo-lang'], FILTER_SANITIZE_STRING);
                $price9_12 = filter_var($_POST['bestemming-price-9-12'], FILTER_VALIDATE_FLOAT);
                $price13 = filter_var($_POST['bestemming-price-13'], FILTER_VALIDATE_FLOAT);
                $price14 = filter_var($_POST['bestemming-price-14'], FILTER_VALIDATE_FLOAT);
                $price15 = filter_var($_POST['bestemming-price-15'], FILTER_VALIDATE_FLOAT);
                $price16 = filter_var($_POST['bestemming-price-16'], FILTER_VALIDATE_FLOAT);
                $price17 = filter_var($_POST['bestemming-price-17'], FILTER_VALIDATE_FLOAT);
                $price18 = filter_var($_POST['bestemming-price-18'], FILTER_VALIDATE_FLOAT);
                $price19 = filter_var($_POST['bestemming-price-19'], FILTER_VALIDATE_FLOAT);
                $price20 = filter_var($_POST['bestemming-price-20'], FILTER_VALIDATE_FLOAT);
                if ($departureDate == "" || $departureDate == null) {
                    $departureDate = '1970-01-01';
                }
                //region prices
                    if ($price9_12 == null || $price9_12 == "") {
                        $price9_12 = 0;
                    }
                    if ($price13 == null || $price13 == "") {
                        $price13 = 0;
                    }
                    if ($price14 == null || $price14 == "") {
                        $price14 = 0;
                    }
                    if ($price15 == null || $price15 == "") {
                        $price15 = 0;
                    }
                    if ($price16 == null || $price16 == "") {
                        $price16 = 0;
                    }
                    if ($price17 == null || $price17 == "") {
                        $price17 = 0;
                    }
                    if ($price18 == null || $price18 == "") {
                        $price18 = 0;
                    }
                    if ($price19 == null || $price19 == "") {
                        $price19 = 0;
                    }
                    if ($price20 == null || $price20 == "") {
                        $price20 = 0;
                    }
                //endregion

                // inserting it into the bestemmingen-table
                $sql = "INSERT INTO bestemmingen (destination, departure_date, price_9_12, price_13, price_14, price_15, price_16, price_17, price_18, price_19, price_20, description_short, description_long, destination_image_names, destination_image_text) VALUES ('$destination', '$departureDate', '$price9_12', '$price13', '$price14', '$price15', '$price16', '$price17', '$price18', '$price19', '$price20', '$descriptionShort', '$descriptionLong', '$destinationImageNames', '$imageTextArray')";

                // Success and error notifications for user
                if ($conn->query($sql) === TRUE) {
                    $uploadError = "De bestemming is toegevoegd.";
                    $destinationSuccess = "AddSuccessful";
                } else {
                    print_r($conn);
                    $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                    $destinationSuccess = "AddError";
                }
            }
            else {
                echo "There was an error uploading your file, please try again later.";
            }
        } else {
            // if unknown error with uploading, show error
            echo "Sorry, there was an error uploading your file.";
            $_SESSION['uploadError'] = "Unknown error: there was an error uploading your file. Please try again in 5 minutes. If this error keeps persisting, please contact " . $mail . ".";
        }

    }
    //endregion
}

//endregion

//region getDestinations() -> Display destinations on website
    function getDestinations() {
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

        $sql = "SELECT * FROM bestemmingen
                ORDER BY CASE WHEN departure_date = '1970-01-01' THEN 2 ELSE 1 END, departure_date";
        $result = mysqli_query($conn, $sql);

        // getting the boats from the database and showing them on the page
        if($result->num_rows > 0) {

            // start of destinations area
            echo "<div class='destinations-all'>";

            echo '<script>const path = "../uploads/"</script>';

            // start of loop
            while($row = mysqli_fetch_assoc($result)) {
                // Date
                $departure_date = $row['departure_date'];
                if ($departure_date == "1970-01-01") {
                    $shownDeparture = "datum nog niet bekend";
                } else {
                    $shownDeparture = "Vertrek " . date("d-m-Y",strtotime($departure_date));
                }

                $space = $row['plaatsen_over'];

                if ($_SESSION["isAdmin"] == true || ($departure_date >= date('Y-m-d') || $shownDeparture == "datum nog niet bekend")) {
                    if ($_SESSION["isAdmin"] == true || $space >= 1) {

                    // Add id for potential links
                    echo "<div class='bestemming-single' id='" . strtolower(filter_var($row['destination'], FILTER_SANITIZE_STRING)) . "'>";
                    echo '<div class="bestemming-img-caption">';

                    //destination
                    $destinationTitle = $row['destination'];
                    if (strlen($destinationTitle) > 19) {
                        $destinationTitle = substr($row['destination'], 0, 19);
                    }
                    echo '<h2>' . $destinationTitle . '</h2>';


                    echo '<figcaption>' . $shownDeparture . '</figcaption>';

                    //image
                        // Image string to array
                        $imageArray = explode(", ", $row['destination_image_names']);
                    echo '<a href="meer-info.php?id=' . $row['id'] . '"><img src="../uploads/' . $imageArray[0] . '"></a>';
                    echo '</div>';

                    //description
                    echo '<div class="bestemming-text"><p>';
                    echo $row['description_long'];

                    //price
                    if ($row['price_9_12'] == 0 || $row['price_9_12'] == null) {
                        $displayedPrice = "Prijs op aanvraag";
                        $displayedTaxes = "";
                    } else {
                        $displayedPrice = "Vanaf: € " . $row['price_9_12'] . "*";
                        $displayedTaxes = "Inclusief belastingen en toeslagen";
                    }
                    echo "<br><span class='bestemming-price'><b>$displayedPrice</b></span><span class='taxes'>$displayedTaxes</span>";
                    // inschrijven-knop
                    echo '<a class="inschrijven-destination" href="inschrijven.php?id=' . strtolower(filter_var($row['id'], FILTER_SANITIZE_STRING)) . '"><button style="display:block; margin-top:10px;">Inschrijven</button></a>';

                    // knop "bekijk meer afbeeldingen"
                    ?>
                    <script>
                        let $_<?= $row['id'] ?> = "images-<?= strtolower($row['id']) ?>";
                        function showImages($currentCity) {
                            if(document.getElementById($currentCity).style.display === "block") {
                                document.getElementById($currentCity).style.display = "none";
                            } else {
                                document.getElementById($currentCity).style.display = "block";
                            }
                        }
                    </script>
                    <?php

                    echo '<a class="bekijk-meer" onClick="showImages($_' . $row['id'] . ')"><button>Bekijk meer afbeeldingen</button></a>';

                    // delete and edit buttons -> only shown if the user is an admin
                    if ($_SESSION["isAdmin"] === true ) {
                        echo "<a class='delete-destination' href='includes/delete_update/delete-destination.php?id=" . $row['id'] . "'><button class='update-delete-button'>Verwijderen</button></a>";
                        echo "<a class='update-destination' href='includes/delete_update/update-destination.php?id=" . $row['id'] . "'><button class='update-delete-button'>Aanpassen</button></a>";
                    }
                    echo '</p></div>';

                    //start slideshow

                    echo '<div id="images-' . strtolower($row['id']) . '" class="image-slideshow">';
                        echo '<span id=close></span>';
                        echo '<div class=image-slide-images>';
                    ?>
                            <!--prev arrow-->
                            <a class=prev onclick="showImg('prev', <?=$row['id']?>)">&#10094;</a>

                            <div class='slider-images' id='slide-images<?= $row['id']?>'">

                            </div>

                            <!-- next arrow-->
                            <a class=next onclick="showImg('next', <?=$row['id']?>)">&#10094;</a>

                            <script>
                                // Set PHP image-array to javascript image-array
                                let imageArray<?= $row['id']?> = <?= json_encode(explode(", ", $row['destination_image_names'])) ?>;
                                let textArray<?=$row['id']?> = <?= json_encode(explode("___ ", $row['destination_image_text'])) ?>;

                                // Set amount of slides
                                let slideAmount<?= $row['id']?> = imageArray<?=$row['id']?>.length;

                                // Set destination for images
                                let gallery<?=$row['id']?> = document.getElementById("slide-images<?= $row['id']?>");

                                gallery<?=$row['id']?>.innerHTML = "";

                                // Set current slide
                                let currentSlide_<?=$row['id']?> = 0;

                                // loop to show all images on the page
                                gallery<?=$row['id']?>.innerHTML += gallery<?=$row['id']?>.innerHTML = '<div class="inactive active" id="image-<?=$row['id']?>' + '-' + 0 + '"><img src="../uploads/' + imageArray<?=$row['id']?>[0] + '" onclick="openPicture(0, <?=$row['id']?>)"><p>' + textArray<?=$row['id']?>[0] + '</p></div>';

                                for (let i = 1; i < slideAmount<?=$row['id']?>; i++) {
                                    gallery<?=$row['id']?>.innerHTML += gallery<?=$row['id']?>.innerHTML = '<div class="inactive" id="image-<?=$row['id']?>' + '-' + i + '"><img src="../uploads/' + imageArray<?=$row['id']?>[i] + '" onclick="openPicture(' + i + ', <?=$row['id']?>)"><p>' + textArray<?=$row['id']?>[i] + '</p>';
                                }

                                //region Slider function
                                    function showImg(direction, id) {

                                            // Get current Slide
                                            let slide = document.getElementById("slide-images" + id).getElementsByClassName("active");
                                            let currentSlide = slide[0];
                                            console.log(currentSlide);

                                            // Remove active tag from the currently active image
                                            document.getElementById(currentSlide.id).classList.remove("active");

                                            // if you pressed on the right button
                                            if (direction === "next") {

                                                // Get next slide
                                                let nextSlide = currentSlide.nextSibling;

                                                // if the current slide is going past the final one, go back to the first one
                                                if (nextSlide == null) {
                                                    document.getElementById("slide-images" + id).firstElementChild.classList.add("active");
                                                } else {
                                                    nextSlide.classList.add("active");
                                                }

                                                // If you pressed on the left button
                                            } else {

                                                // Get previous slide
                                                let prevSlide = currentSlide.previousSibling;

                                                if (prevSlide == null) {
                                                    document.getElementById("slide-images" + id).lastElementChild.classList.add("active");
                                                } else {
                                                    prevSlide.classList.add("active");
                                                }
                                            }
                                    }
                                //endregion

                                //region Modal function
                                // Get the modal
                                let modal<?=$row['id']?> = document.getElementById("foto-modal");

                                // When the user clicks the button, open the modal
                                function openPicture(pictureId, destinationId) {
                                    modal<?=$row['id']?>.style.display = "block";
                                    let clickedImage = document.getElementById("image-" + destinationId + "-" + pictureId).getElementsByTagName("img")[0].src;
                                    let clickedImageText = document.getElementById("image-" + destinationId + "-" + pictureId).getElementsByTagName("p")[0].innerText;
                                    document.getElementById("modal-image").src = clickedImage;
                                    document.getElementById("modal-image").dataset.destination = destinationId;
                                    document.getElementById("modal-image").dataset.pictureNumber = pictureId;
                                    document.getElementById("img-text").innerText = clickedImageText;
                                }

                                // When the user clicks on <span> (x), close the modal
                                document.getElementById("close").onclick = function() {
                                    document.getElementById("images-<?=$row['id']?>").style.display="none";
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

                        echo '</div>';

                        echo '<br>';
                        echo '<a class="bekijk-meer" onClick="showImages($_' . $row['id'] . ')"><button> &#10006;</button></a>';

                    //end slideshow
                    echo '</div>';
                    echo '</div>';

                    }

                }
            }
            // end of destinations area
            echo "</div>";

        } else {
            // if there aren't any destinations, show this:
            echo "Er zijn nog geen bestemmingen. Voeg eerst een bestemming toe.";
        }

    }
//endregion

//region Single function for all sidebars (getSidebarDestinations();)
function getSidebarDestinations($side) {

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

    if ($side == "right") {
        $sql = "SELECT * FROM 
                  (
                  SELECT *, ROW_NUMBER() over (ORDER BY CASE WHEN departure_date = '1970-01-01' THEN 2 ELSE 1 END, departure_date) AS rn FROM bestemmingen WHERE plaatsen_over > 0
                  ) temp
                  WHERE (temp.rn%2) = 0;";
    } elseif ($side == "left") {
        $sql = "SELECT * FROM 
                  (
                  SELECT *, ROW_NUMBER() over (ORDER BY CASE WHEN departure_date = '1970-01-01' THEN 2 ELSE 1 END, departure_date) AS rn FROM bestemmingen WHERE plaatsen_over > 0
                  ) temp
                  WHERE (temp.rn%2) = 1;";
    } else {
        $sql = "SELECT * FROM bestemmingen WHERE plaatsen_over > 0 ORDER BY CASE WHEN departure_date = '1970-01-01f' THEN 2 ELSE 1 END, departure_date";
    }
    $result = mysqli_query($conn, $sql);

    // getting the destinations from the database and showing them on the page
    if($result->num_rows > 0) {
        // start of destinations area
        echo "<div class='destinations-sidebar'>";

        // start of loop
        while($row = mysqli_fetch_assoc($result)) {

            // date
            $departure_date = $row['departure_date'];
            if ($departure_date == "1970-01-01") {
                $shownDeparture = "datum nog niet bekend";
            } else {
                $shownDeparture = "Vertrek " . date("d-m-Y", strtotime($departure_date));
            }

            $space = $row['plaatsen_over'];

            if ($_SESSION["isAdmin"] == true || ($departure_date >= date('Y-m-d') || $shownDeparture == "datum nog niet bekend")) {
                if ($_SESSION["isAdmin"] == true || $space >= 1) {

                    echo "<div class='bestemming-single-sidebar'>";

                    // destination
                    $destinationTitle = $row['destination'];
                    if (strlen($destinationTitle) > 19) {
                        $destinationTitle = substr($row['destination'], 0, 19);
                    }
                    echo "<h2 class='destination-sidebar-name'>" . $destinationTitle . "</h2>";

                    echo '<figcaption class="destination-sidebar-date">' . $shownDeparture . '</figcaption>';

                    //image
                    // Image string to array
                    $imageArray = explode(", ", $row['destination_image_names']);
                    echo '<div class="tooltip"><a href="meer-info.php?id=' . strtolower(filter_var($row['id'], FILTER_SANITIZE_STRING)) . '"><img class="destination-sidebar-img" src="../uploads/' . $imageArray[0] . '"></a><span class="tooltip-text">' . $row['description_short'] . '</span></div>';

                    echo '</div>';
                }
            }
        }

        // end of destinations area
        echo "</div>";

    } else {
        // if there aren't any destinations, show this:
        echo "Er zijn nog geen bestemmingen. Voeg eerst een bestemming toe.";
    }
}
//endregion

//region getSidebarDestinationsCenterInfo()
    function getSidebarDestinationsCenterInfo() {
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

        $sql = "SELECT * FROM bestemmingen ORDER BY CASE WHEN departure_date = '1970-01-01' THEN 2 ELSE 1 END, departure_date";
        $result = mysqli_query($conn, $sql);

        // getting the boats from the database and showing them on the page
        if($result->num_rows > 0) {
            // start of destinations area
            echo "<div class='destinations-sidebar'>";

            // start of loop
            while($row = mysqli_fetch_assoc($result)) {
                // Add id for potential links
                echo "<div class='bestemming-single-sidebar'>";
                echo "<h2 class='destination-sidebar-name'>" . $row['destination'] . "</h2>";

                // date
                $departure_date = $row['departure_date'];
                if ($departure_date == "1970-01-01") {
                    $shownDeparture = "datum nog niet bekend";
                } else {
                    $shownDeparture = "Vertrek " . date("d-m-Y",strtotime($departure_date));
                }
                echo '<figcaption class="destination-sidebar-date">' . $shownDeparture . '</figcaption>';

                //image
                // Image string to array
                $imageArray = explode(", ", $row['destination_image_names']);
                echo '<div class="tooltip"><a href="meer-info.php?id=' . $row['id'] .  '"><img class="destination-sidebar-img" src="../uploads/' . $imageArray[0] . '"></a><span class="tooltip-text">' . $row['description_short'] . '</span></div>';
                echo '</div>';
            }

            // end of destinations area
            echo "</div>";

        } else {
            // if there aren't any destinations, show this:
            echo "Er zijn nog geen bestemmingen. Voeg eerst een bestemming toe.";
        }
    }
//endregion

?>