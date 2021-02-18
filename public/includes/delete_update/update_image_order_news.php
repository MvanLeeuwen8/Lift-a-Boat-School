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

    <script>
        // function on cancel button
        function backToDestination() {
            location.href="../../update-news.php?id=" + <?=$_GET['id']?>;
        }

        let listItemsArray = document.getElementsByClassName('listItem');

        //setting variables
        let imageArray = "";
        let textArray = "";
        let orderLink = "";

        //get id
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const id = urlParams.get('id');

    </script>

</head>

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
    $sql = "SELECT * FROM nieuws WHERE id = '$id'";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>" . $row['title'] . " foto-volgorde aanpassen - Lift a Boat</title>";

            // Image string to array
            $imageArray = explode(", ", $row['news_images']);
            $imageText = explode("___ ", $row['news_images_text']);

            echo "<div class='images-gallery'>";
            //If no image, show this:
            if (!array_filter($imageArray)) {
                echo "<p>Geen afbeeldingen gevonden. Voeg een afbeelding toe.</p>";
            } else {
                //change image order
                echo "<form class='edit-form'>";
                echo "<h2>Aanpassen afbeeldingsvolgorde " . $row['title'] . "</h2>";
                echo "<ul id='imageList'>";
                //Loop image array
                for ($i = 0; $i < count($imageArray); $i++) {
                    //Image
                    echo "<li class='listItem orderChange'><div class='imagebutton'><img src='../../../uploads/" . $imageArray[$i] . "'><br>";
                    //Image file-name
                    echo "<p class='image-filename'>" . $imageArray[$i] . "</p>";
                    //Add text to image
                    echo "<label for='image-text'>Afbeelding tekst:</label><textarea name='image-text-" . $i ."' rows='3'>". $imageText[$i] . "</textarea><br>";

                    echo "</div></li>";
                }

                echo "</ul>";

                // submit and cancel buttons
                echo "<div class='edit-buttons'><input type=button value='Opslaan' onclick='updateImageOrder()'>";
                echo "<input type=button value=Annuleren onclick='backToDestination()'></div>";
                echo "</form>";
            }
            echo "</div>";
        }
    } else {
        header("location:../../nieuws.php");
    }
} else {
    //if they are not an admin, show them to the homepage
    header( "location:../../index.php");
}

?>
<!-- reorder images -->
<script type="text/javascript" src="../../js/reorder_images.js"></script>

<script>

    //imageArray is updated array of images to be sent to the database (already in string-form)
    //textArray is updated array of image-text to be sent to the database (already in string-form)
    function updateImageOrder() {
        //looping through list
        for (i = 0; i < listItemsArray.length; i++) {
            try {
                //if the variable is still empty, set the variables as their own values
                if (imageArray === "") {
                    imageArray = listItemsArray[i].getElementsByTagName("img")[0].src.replace("../../../uploads/", "");
                    textArray = listItemsArray[i].getElementsByTagName("textarea")[0].value;
                }
                //else add the new values on top of the already-set values with separators
                else {
                    imageArray = imageArray + ",,,," + listItemsArray[i].getElementsByTagName("img")[0].src.replace("../../../uploads/", "");
                    textArray = textArray + "____" + listItemsArray[i].getElementsByTagName("textarea")[0].value;
                }
            } catch (e) {
                break;
            }
        }

        textArray.replace(" ", "%20");

        orderLink = "update_order_news.php?id=" + id + "&image=" + imageArray + "&text=" + textArray;

        window.location.href = orderLink;
    }
</script>