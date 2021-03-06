<?php
include '../config.php';
include 'includes/config.php';

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

    // delete boat
    $sql = "DELETE FROM boten WHERE id = $id";

    if (mysqli_query($conn, $sql)) {
        mysqli_close($conn);

        // go back to boten after successful delete
        header('Location: ../../boten.php');
        exit;
    } else {
        // error message in case the boat didn't get deleted
        echo "Error deleting boat";
    }
} else {
    //if they are not an admin, show them to the homepage
    header( "location:../../index.php");
}
?>