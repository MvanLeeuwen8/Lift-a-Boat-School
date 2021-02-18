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
        die("Connection failed: " . mysqli_connect_error());
    }

    // delete review
    $sql = "DELETE FROM trips WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);

        // go back to trips after successful delete
        header('Location: ../../trips.php');
        exit;
    } else {
        // error message in case the trip didn't get deleted
        echo "Error deleting trip";
    }
} else {
    //if they are not an admin, show them to the homepage
    header( "location:../../index.php");
}
?>