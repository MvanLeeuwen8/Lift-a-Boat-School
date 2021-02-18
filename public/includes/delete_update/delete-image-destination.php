<?php
include '../config.php';

session_start();

// check if the one trying to delete is an admin, if they are, show the content
if ($_SESSION["isAdmin"] === true) {

    // setting id to the id of the row of the image you want to delete
    $id = $_GET['id'];
    $image = $_GET['image'];

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

    // Get the filepath for the image to delete that as well
    $sql = "SELECT id, destination, destination_image_names, destination_image_text FROM bestemmingen WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Image string to array
        $imageArray = explode(", ", $row['destination_image_names']);
        $textArray = explode("___ ", $row['destination_image_text']);


        // Path to directory
        $dir = "../../../uploads/";

        // Delete single image
        $failedDelete = false;
        if(!$deleted = unlink($dir . $image)) {
            $failedDelete = true;
        }

        // Delete value in the array, even if deleting the image wasn't successful (for example due to it not existing for some reason)
        $key = array_search($image, $imageArray);
        echo $key;
        unset($imageArray[$key]);
        unset($textArray[$key]);
        $implodedArray = implode(", ", $imageArray);
        $implodedText = implode("___ ", $textArray);

        $sql = "UPDATE bestemmingen SET 
                    destination_image_names = '$implodedArray',
                    destination_image_text = '$implodedText'
                WHERE id = '$id';";

        if (mysqli_query($conn, $sql)) {
            mysqli_close($conn);

            // go back to update the destination after successful delete
            header('Location: update-destination.php?id=' . $_GET['id']);
            exit;
        } else {
            // error message in case the image didn't get deleted
            echo "Error deleting image";
        }
    }
} else {
    //if they are not an admin, show them to "bestemmingen"
    header("location:../../bestemmingen.php");
}
?>