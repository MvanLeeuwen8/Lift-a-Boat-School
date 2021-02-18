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
    function backToReviews() {
        location.href="../../reviews.php";
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
    $sql = "SELECT id, name, rating, comment, send_date FROM reviews WHERE id = $id";
    $result = $conn->query($sql);

    // show current values
    if ($result->num_rows > 0) {

        // output data of one row
        while ($row = $result->fetch_assoc()) {

            //title after we fetched row
            echo "<title>Aanpassen review: " . $row['name'] . " - Lift a Boat</title>";

            // form with values already filled in and fetched from the database
            // form start
            echo "<form class='edit-review-form' action='' method='post'>";

            // Name of the reviewer
            echo "<div class='edit-review-name'><label style='min-width:30%; display:inline-block; margin-left:20px;'>Naam: </label>" . "<input type='text' name='newRevName' value='" . $row['name'] . "' required></div><br>";

            // Rating of the reviewer
            echo "<div class='edit-review-rating'><label style='min-width:30%; display:inline-block; margin-left:20px;'>Rating: </label>" . "<select name='newRating' id='newRating' value='" . $row['rating'] . "' required>";

            // The options
            // Select 1 if original rating was 1
            if ($row['rating'] == 1) {
                echo "<option value=5>5</option>";
                echo "<option value=4>4</option>";
                echo "<option value=3>3</option>";
                echo "<option value=2>2</option>";
                echo "<option value=1 selected='selected'>1</option>";

                // Select 2 if original rating was 2
            } elseif ($row['rating'] == 2) {
                echo "<option value=5>5</option>";
                echo "<option value=4>4</option>";
                echo "<option value=3>3</option>";
                echo "<option value=2 selected='selected'>2</option>";
                echo "<option value=1>1</option>";

                // Select 3 if original rating was 3
            } elseif ($row['rating'] == 3) {
                echo "<option value=5>5</option>";
                echo "<option value=4>4</option>";
                echo "<option value=3 selected='selected'>3</option>";
                echo "<option value=2>2</option>";
                echo "<option value=1>1</option>";

                // Select 4 if original rating was 4
            } elseif ($row['rating'] == 4) {
                echo "<option value=5>5</option>";
                echo "<option value=4 selected='selected'>4</option>";
                echo "<option value=3>3</option>";
                echo "<option value=2>2</option>";
                echo "<option value=1>1</option>";

                // Select 5 if original rating was 5
            } else {
                echo "<option value=5 selected='selected'>5</option>";
                echo "<option value=4>4</option>";
                echo "<option value=3>3</option>";
                echo "<option value=2>2</option>";
                echo "<option value=1>1</option>";
            }

            // end of ratings
            echo "</select></div><br>";

            // Reviewer comments --> not required
            echo "<div class='edit-review-comment'><label style='min-width:30%; display:inline-block; margin-left:20px;'>Comments: </label>" . "<textarea name='newComment' id='newComment' rows='9' cols='auto'>" . $row['comment'] . "</textarea></div><br>";

            // submit and cancel buttons
            echo "<div class='edit-review-buttons'><input type=submit value='Opslaan' name='updateReviews'>";
            echo "<input type=button value=Annuleren onclick='backToReviews()'></div>";
            echo "</form>";
        }
    }

    // Putting the filled-in values into variables
    if (isset($_POST['updateReviews'])) {
        $newRevName = filter_var($_POST['newRevName'], FILTER_SANITIZE_STRING);
        $newRating = filter_var($_POST['newRating'], FILTER_SANITIZE_STRING);
        $newComment = filter_var($_POST['newComment'], FILTER_SANITIZE_STRING);

        // Saving the values to the database
        $sql = "UPDATE reviews SET 
                     name = '$newRevName', 
                     rating = '$newRating',
                     comment = '$newComment'
                 WHERE id = $id;";

        // If the values are saved to the database, go back to reviews.php
        if ($conn->query($sql) === TRUE) {
            header('Location: ../../reviews.php');
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