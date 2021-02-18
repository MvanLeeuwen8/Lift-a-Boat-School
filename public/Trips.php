<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_trips.php';
    ?>
</head>

<body>
<div class="main-area">
    <!-- navigation -->
    <?php include "includes/nav.php"; ?>

    <!-- main body -->
    <main class="trips" id="main">
        <h2>Trips</h2>

        <!-- button to show the form to create a trip -->
        <button class="trips-button" onClick="openForm()">Maak een trip aan</button>

        <!-- show this form after clicking on the button -->
        <div class="popup-form">
            <form action="" class="form-trips" id="tripsForm" method="post">
                <h2>Trip toevoegen</h2>

                <!-- place of departure -->
                <div class="formblock-wide">
                    <label for="tripDeparture">Vertrekplaats:*</label>
                    <input type="text" name="tripDeparture" required>
                </div>

                <!-- destination -->
                <div class="formblock-wide block-destination">
                    <label for="tripDestination">Aankomstplaats:*</label>
                    <input type="text" name="tripDestination" required>
                </div>

                <!-- date -->
                <div class="formblock-wide">
                    <label for="tripDate">Vertrekdatum:*</label>
                    <input type="date" name="tripDate" required>
                </div>

                <!-- price -->
                <div class="formblock-wide block-price">
                    <label for=tripPrice">Prijs:*</label>
                    <input type="number" name="tripPrice" required>
                </div>

                <!-- comments -->
                <div class="formblock-textarea">
                    <label for="tripComments">Extra informatie:*</label>
                    <textarea rows="8" cols="auto" name="tripComments"></textarea>
                </div>

                <!-- submit and cancel buttons -->
                <div class="formblock-submit">
                    <input type="submit" value="Trip opslaan" name="trips">
                    <input type="button" value="annuleren" onclick="closeForm()">
                </div>

            </form>

            <!--
            Show trips from database,
            getTrips() is from functions.php
            -->
            <?php
            getTrips();
            ?>

    </main>

    <!-- footer -->
    <?php include "includes/footer.php"; ?>
</div>

</body>
</html>

<script>
    // open and close forms when clicking on button
    document.getElementById("tripsForm").style.display = "none";
    function openForm() {
        document.getElementById("tripsForm").style.display = "block";
    }

    function closeForm() {
        if (confirm("Weet je zeker dat je het formulier wilt resetten?")) {
            document.getElementById("tripsForm").reset();
            document.getElementById("tripsForm").style.display = "none";
        }
    }
</script>

<?php
