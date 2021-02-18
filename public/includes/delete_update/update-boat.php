<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="../../styles/main.css">
    <?php
    session_start();
    ?>
</head>

<script>
    // function on cancel button
    function backToBoats() {
        location.href="../../boten.php";
    }
</script>

<?php
// check if the one trying to edit is an admin, if they are, show the content
if ($_SESSION["isAdmin"] === true) {

    // To get the database info:
    include '../config.php';

    // setting id to the id of the one you want to update
    $id = $_GET['id'];

    //Connect to database
    $conn = mysqli_connect($servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // only select the row where the id matches
    $sql = "SELECT id, ship_name, ship_length, ship_width, ship_height, ship_depth, ship_weight, ship_power FROM boten WHERE id = $id";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>Aanpassen " . $row['ship_name'] . " - Lift a Boat</title>";

            // form with values already filled in and fetched from the database
            // form start
            echo "<form action='' method='post' style='background-color:rgba(0,0,0,0.5); color: #fff; width:50%; margin: 30px auto auto auto; padding:20px;'>";

            // Name of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Bootnaam: </label>" . "<input type='text' name='newName' value='" . $row['ship_name'] . "' required><br>";

            // Length of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot lengte: </label>" . "<input type='number' name='newLength' value='" . $row['ship_length'] . "' required><br>";

            // Width of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot breedte: </label>" . "<input type='number' name='newWidth' value='" . $row['ship_width'] . "' required><br>";

            // Height of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot hoogte: </label>" . "<input type='number' name='newHeight' value='" . $row['ship_height'] . "' required><br>";

            // Depth of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot diepte: </label>" . "<input type='number' name='newDepth' value='" . $row['ship_depth'] . "' required><br>";

            // Weight of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot gewicht: </label>" . "<input type='number' name='newWeight' value='" . $row['ship_weight'] . "' required><br>";

            // Power of the ship
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Boot paardenkracht: </label>" . "<input type='number' name='newPower' value='" . $row['ship_power'] . "' required><br>";

            // Submit and cancel buttons
            echo "<input type=submit value='Opslaan' name='updateBoats'>";
            echo "<input type=button value=Annuleren onclick='backToBoats()'>";

            // end form
            echo "</form>";
        }
    }

    // Putting the filled-in values into variables
    if (isset($_POST['updateBoats'])) {
        $newName = filter_var($_POST['newName'], FILTER_SANITIZE_STRING);
        $newLength = filter_var($_POST['newLength'], FILTER_VALIDATE_FLOAT);
        $newWidth = filter_var($_POST['newWidth'], FILTER_VALIDATE_FLOAT);
        $newHeight = filter_var($_POST['newHeight'], FILTER_VALIDATE_FLOAT);
        $newDepth = filter_Var($_POST['newDepth'], FILTER_VALIDATE_FLOAT);
        $newWeight = filter_var($_POST['newWeight'], FILTER_VALIDATE_INT);
        $newPower = filter_var($_POST['newPower'], FILTER_VALIDATE_INT);

        // Saving the values to the database
        $sql = "UPDATE boten SET 
                     ship_name = '$newName', 
                     ship_length = '$newLength',
                     ship_width = '$newWidth',
                     ship_height = '$newHeight',
                     ship_depth = '$newDepth',
                     ship_weight = '$newWeight',
                     ship_power = '$newPower'
                 WHERE id = $id;";

        // If the values are saved to the database, go back to boten.php
        if ($conn->query($sql) === TRUE) {
            header('Location: ../../boten.php');
        } else {
            // Else give an error
            echo "<p style='text-align: center; color:#fff;'>Error updating boat</p>";
        }
    }
} else {
    //if they are not an admin, show them to the homepage
    header( "location:../../index.php");
}

?>
</html>