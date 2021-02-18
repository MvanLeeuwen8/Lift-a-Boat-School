<?php
//region Send to database - boten
if (isset($_POST['boten'])) {

    //region Saving the inputs as variables - boten
    $shipName = filter_var($_POST['shipname'], FILTER_SANITIZE_STRING);
    $shipLength = filter_var($_POST['shiplength'], FILTER_VALIDATE_FLOAT);
    $shipWidth = filter_var($_POST['shipwidth'], FILTER_VALIDATE_FLOAT);
    $shipHeight = filter_var($_POST['shipheight'], FILTER_VALIDATE_FLOAT);
    $shipDepth = filter_var($_POST['shipdepth'], FILTER_VALIDATE_FLOAT);
    $shipWeight = filter_var($_POST['shipweight'], FILTER_VALIDATE_INT);
    $shipPower = filter_var($_POST['shippower'], FILTER_VALIDATE_INT);
    //endregion

    //region Inserting the values into the boten-table
    $sql = "INSERT INTO boten (ship_name, ship_length, ship_width, ship_height, ship_depth, ship_weight, ship_power) VALUES
                ('$shipName', '$shipLength', '$shipWidth', '$shipHeight', '$shipDepth', '$shipWeight', '$shipPower' )";
    //endregion

    //region Success & errormessages
    if ($conn->query($sql) === TRUE) {
        $sentBoat = "Uw boot is toegevoegd";
        $sentId = "SentSuccessful";
    } else {
        $sentBoat = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
        $sentId = "SentError";
    }
    //endregion
}
//endregion

//region getBoats() -> display boats on site
function getBoats() {
    // Create connection
    global $servername, $username, $password, $database;
    $conn = mysqli_connect( $servername, $username, $password, $database);
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT id, ship_name, ship_length, ship_width, ship_height, ship_depth, ship_weight, ship_power, add_date FROM boten";
    $result = mysqli_query($conn, $sql);

    // getting the boats from the database and showing them on the page
    if($result->num_rows > 0) {

        // start of table
        echo "<table class='boat-table'>";

        // Table headers
        echo "<tr>
                    <th>Naam</th>
                    <th>Lengte</th>
                    <th>Breedte</th>
                    <th>Hoogte</th>
                    <th>Diepte</th>
                    <th>Gewicht</th>
                    <th>Kracht</th>
                    <th>Verwijderen</th>
                    <th>Aanpassen</th>
              </tr>";

        // start of loop with table content
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td>". $row['ship_name'] ."</td>";
                echo "<td>". $row['ship_length'] ."</td>";
                echo "<td>". $row['ship_width'] ."</td>";
                echo "<td>". $row['ship_height'] ."</td>";
                echo "<td>". $row['ship_depth'] ."</td>";
                echo "<td>". $row['ship_weight'] ."</td>";
                echo "<td>". $row['ship_power'] ."</td>";

                // delete button -> code in includes/delete-boat.php
                echo "<td><button class='update-delete-button'><a href='includes/delete_update/delete-boat.php?id=" . $row['id'] . "'>Verwijderen</a></button></td>";

                // edit button -> code in includes/update-boat.php
                echo "<td><button class='update-delete-button'><a href='includes/delete_update/update-boat.php?id=" . $row['id'] . "'>Aanpassen</button></td>";

            // end of row
            echo "</tr>";
        }
        // end of table
        echo "</table>";
    } else

        // if there aren't any boats, show this:
        echo "Er zijn nog geen boten. Voeg eerst een boot toe.";
}
//endregion