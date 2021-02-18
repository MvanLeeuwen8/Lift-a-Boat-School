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
<?php

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

    //image array to string in database-format
    $imagesFromGet = $_GET['image'];
    $imagesInArray = explode(",,,,", $imagesFromGet);
    foreach($imagesInArray as $i => $image) {
        $imagesInArray[$i] = str_replace("uploads/", "", stristr($image, "uploads/"));
    }
    $imagesInString = implode(", ", $imagesInArray);
    echo ($imagesInString);
    echo "<br>";

    //text array to string in database-format
    $textFromGet = $_GET['text'];
    $textFromGet = str_replace("%20", " ", $textFromGet);
    $textInArray = explode("____", $textFromGet);
    $textInString = implode("___ ", $textInArray);
    echo $textInString;

    //Sending the order to the database
    $sql = "UPDATE bestemmingen SET 
                        destination_image_names = '$imagesInString',
                        destination_image_text = '$textInString'
                        WHERE id = '$id'";
    if ($conn->query($sql) === TRUE) {
       header('location:update-destination.php?id=' . $_GET['id']);
    } else {
        print_r($conn);
        $uploadError = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
        echo "<p style='text-align: center; color:#fff;'>Error updating destination</p>";
        $destinationSuccess = "UpdateError";
    }

} else {
    header("location:../../index.php");
}
