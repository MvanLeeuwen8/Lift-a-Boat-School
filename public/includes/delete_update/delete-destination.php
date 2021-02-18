<?php
include '../config.php';

session_start();

// check if the one trying to delete is an admin, if they are, show the content
if ($_SESSION["isAdmin"] === true) {

    // setting id to the id of the one you want to delete
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

    // Get the filepath for the image to delete that as well
    $sql = "SELECT id, destination, destination_image_names FROM bestemmingen WHERE id = '$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Image string to array
        $imageArray = explode(", ", $row['destination_image_names']);

        // Path to directory
        $dir = "../../../uploads/";

        // Delete files in the directory
        $failedDelete = false;
        foreach ($imageArray as $x => $image) {
            $deleted = unlink ($dir . $image);
            if(!$deleted) {
                $failedDelete = true;
            }
        }

        if ($failedDelete == true) {
            echo "Error deleting file.";
        }
        
        $sql = "DELETE FROM bestemmingen WHERE id = '$id'";
    }

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);

        // go back to bestemmingen after successful delete
        header('Location: ../../bestemmingen.php');
        exit;
    } else {
        // error message in case the destination didn't get deleted
        echo "Error deleting image";
    }
} else {
    //if they are not an admin, show them to "bestemmingen"
    header( "location:../../bestemmingen.php");
}
?>