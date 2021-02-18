<?php
// send trips to database
if (isset($_POST['trips'])) {
    $tripDeparture = filter_var($_POST['tripDeparture'], FILTER_SANITIZE_STRING);
    $tripDestination = filter_var($_POST['tripDestination'], FILTER_SANITIZE_STRING);
    $tripDate = filter_var($_POST['tripDate']);
    $tripPrice = filter_var($_POST['tripPrice'], FILTER_VALIDATE_FLOAT);
    $tripComments = filter_var($_POST['tripComments'], FILTER_SANITIZE_STRING);

    // sending the variables to the database (trips-table)
    $sql = "INSERT INTO trips (trip_departure_place, trip_destination, trip_date, trip_price, trip_comment) VALUES
                    ('$tripDeparture', '$tripDestination', '$tripDate', '$tripPrice', '$tripComments')";

    // error or success message for user, based on whether the data was sent to the database correctly
    if ($conn->query($sql) === TRUE) {
        $sentBoat = "Uw trip is toegevoegd";
        $sentId = "SentSuccessful";
    } else {
        $sentBoat = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
        $sentId = "SentError";
    }
}

// display trips on site
function getTrips() {
    // Create connection
    global $servername, $username, $password, $database;
    $conn = mysqli_connect( $servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id, trip_departure_place, trip_destination, trip_date, trip_price, trip_comment, send_date FROM trips";
    $result = mysqli_query($conn, $sql);

    // getting the boats from the database and showing them on the page
    if($result->num_rows > 0) {
        // start of trips area
        echo "<div class='trip-all'>";

        // start of loop
        while($row = mysqli_fetch_assoc($result)) {
            echo "<h3>" . $row['trip_departure_place'] . " - " . $row['trip_destination'] . "</h3>";
            echo "<h4>Vertrekdatum: " . $row['trip_date'] . "</h4>";
            echo "<h4>Prijs: " . $row['trip_price'] . "</h4>";
            echo "<p>" . $row['trip_comment'] . "</p>";

            // delete button -> code in delete-trip.php
            echo "<td><button class='update-delete-button'><a href='includes/delete_update/delete-trip.php?id=" . $row['id'] . "'>Verwijderen</a></button></td>";

            // edit button -> code in includes/update-trip.php
            echo "<td><button class='update-delete-button'><a href='includes/delete_update/update-trip.php?id=" . $row['id'] ."'>Aanpassen</a></button></td>";
        }

        // end of trips area
        echo "</div>";

    } else {
        // if there aren't any boats, show this:
        echo "Er zijn nog geen trips. Voeg eerst een trip toe.";
    }
}