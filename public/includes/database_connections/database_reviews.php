<?php
//region Send to database - reviews
    // on form-sending, send to database
    if (isset($_POST['review'])) {

        //region Saving the inputs as variables - reviews
        $reviewName = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $reviewRating = filter_var($_POST['rating'], FILTER_SANITIZE_STRING);
        $reviewComment = filter_var($_POST['commentaar'], FILTER_SANITIZE_STRING);
        //endregion

        //region Inserting the values into the reviews-table
        $sql = "INSERT INTO reviews (name, rating, comment) VALUES
                    ('$reviewName', '$reviewRating', '$reviewComment')";
        //endregion

        //region Success & errormessages
        if ($conn->query($sql) === TRUE) {
            $sentReview = "Bedankt voor uw review!";
            $sentId = "SentSuccessful";
        } else {
            $sentReview = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
            $sentId = "SentError";
        }
        //endregion
    }
//endregion

//region getReviews() -> Display reviews on website
    function getReviews() {
        // Create connection
        global $servername, $username, $password, $database;
        $conn = mysqli_connect( $servername, $username, $password, $database);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        //$conn = new mysqli($servername, $username, $password, 'reviews');
        $sql = "SELECT id, name, rating, comment, send_date FROM reviews";
        $result = mysqli_query($conn, $sql);


        // getting the reviews from the database and showing them on the page
        if($result->num_rows > 0) {
            echo "<table class='review-table'>";


            // start of loop
            while($row = mysqli_fetch_assoc($result)) {

                //checking if there needs to be a dash in front of the name, it gets removed if they haven't written down a comment.
                //also remove or keep <br> depending on if there is a comment or not
                $spaceWithDash = "";
                $shown = "display:none";

                //see if comment isn't empty
                if ($row['comment'] != "") {
                    $spaceWithDash = "&emsp;- ";
                    $shown = "display:block";
                }


                // Star ratings changed into fontawesome icons (stars with and without filling)
                if ($row['rating'] == 5) {
                    $starImg = " - <i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i>";
                } elseif ($row['rating'] == 4) {
                    $starImg = " - <i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i><i class='far fa-star'></i>";
                } elseif ($row['rating'] == 3) {
                    $starImg = " - <i class='fas fa-star'></i><i class='fas fa-star'></i><i class='fas fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i>";
                } elseif ($row['rating'] == 2) {
                    $starImg = " - <i class='fas fa-star'></i><i class='fas fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i>";
                } elseif ($row['rating'] == 1) {
                    $starImg = " - <i class='fas fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i><i class='far fa-star'></i>";
                } else {
                    // if somehow there was no rating filled in, don't display stars
                    $starImg = "";
                }

                // echo each review in database with name and star ratings
                echo "<tr class='review-single'><td>";
                // reviewer comment
                echo "<span class='review-comment' style='" . $shown ."; margin-bottom:-1em;'>" . $row['comment'] . "</span><br style='" . $shown . "'>";
                // reviewer name and rating
                echo $spaceWithDash . $row['name'] . $starImg . "<br>";

                // delete and edit buttons -> only shown if the user is an admin
                if ($_SESSION["isAdmin"] === true ) {

                    // delete button -> code in delete-review.php
                    echo "<a href='includes/delete_update/delete-review.php?id=" . $row['id'] . "'><button class='update-delete-button'>Verwijderen</button></a>";

                    // edit button -> code in update-review.php
                    echo "<a href='includes/delete_update/update-review.php?id=" . $row['id'] . "'><button class='update-delete-button'>Aanpassen</button></a>";
                }

                // end form and table rows etc.
                echo "</form></td></tr>";
            }
            // end of table
            echo "</table>";
        } else

            // if there aren't any reviews, show this:
            echo "Er zijn nog geen reviews. Wees de eerste!";
    }
//endregion
?>