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
    function backToTrips() {
        location.href="../../trips.php";
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
    $sql = "SELECT id, trip_departure_place, trip_destination, trip_date, trip_price, trip_comment, send_date FROM trips WHERE id = $id";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>Aanpassen " . $row['trip_departure_place'] . " - " . $row['trip_destination'] . " - Lift a Boat</title>";

            // form with values already filled in and fetched from the database
            // form start
            echo "<form action='' method='post' style='background-color:rgba(0,0,0,0.5); color: #fff; width:50%; margin: 30px auto auto auto; padding:20px;'>";

            // Departure place
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Vertrekplaats: </label>" . "<input type='text' name='newDeparturePlace' value='" . $row['trip_departure_place'] . "' required><br>";

            // Destination place
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Aankomstplaats: </label>" . "<input type='text' name='newDestination' value='" . $row['trip_destination'] . "' required><br>";

            // Trip date
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Vertrekdatum: </label>" . "<input type='date' name='newDepartureDate' value='" . $row['trip_date'] . "' required><br>";

            // Trip price
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Prijs: </label>" . "<input type='number' name='newTripPrice' value='" . $row['trip_price'] . "' required><br>";

            // Trip information
            echo "<label style='min-width:30%; display:inline-block; margin-left:20px;'>Extra informatie: </label>" . "<textarea name='newTripComments' id='newTripComment' rows='6' cols='auto' style='min-width:40%;'>" . $row['trip_comment'] . "</textarea><br>";

            // submit and cancel buttons
            echo "<input type=submit value='Opslaan' name='updateTrips'>";
            echo "<input type=button value='Aannuleren' onclick='backToTrips()'>";
            echo "</form>";
        }
    }

    // Putting the filled-in values into variables
    if (isset($_POST['updateTrips'])) {
        $newDeparturePlace = filter_var($_POST['newDeparturePlace'], FILTER_SANITIZE_STRING);
        $newDestination = filter_var($_POST['newDestination'], FILTER_SANITIZE_STRING);
        $newDepartureDate = filter_var($_POST['newDepartureDate']);
        $newTripPrice = filter_var($_POST['newTripPrice'], FILTER_VALIDATE_FLOAT);
        $newTripComments = filter_var($_POST['newTripComments'], FILTER_SANITIZE_STRING);

        // Saving the values to the database
        $sql = "UPDATE trips SET 
                     trip_departure_place = '$newDeparturePlace', 
                     trip_destination = '$newDestination',
                     trip_date = '$newDepartureDate',
                     trip_price = '$newTripPrice',
                     trip_comment = '$newTripComments'
                 WHERE id = $id;";

        // If the values are saved to the database, go back to trips.php
        if ($conn->query($sql) === TRUE) {
            header('Location: ../../trips.php');
        } else {
            // Else give an error
            echo "<p style='text-align: center; color:#fff;'>Error updating trip</p>";
        }
    }
} else {
    //if they are not an admin, show them to the homepage
    header( "location:../../index.php");
}

?>
</html>