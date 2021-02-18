<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_boten.php';
    ?>
</head>

<script src="js/forms.js" type="text/javascript"></script>

<body>
<div class="main-area">
    <!-- navigation -->
    <?php include "includes/nav.php"; ?>

    <!-- main body -->
    <main class="boten" id="main">
        <h2>Uw boten</h2>
        <button class="boten-button" onClick="openForm()">Maak een schip aan</button>

        <div class="popup-form">
            <form action="" class="form-boat" id="boatForm" method="post">
                <h2>Schip toevoegen</h2>
                <div class="formblock-wide">
                    <label for="shipname">Naam van het schip*</label>
                    <input type="text" name="shipname" required>
                </div>

                <div class="formblock-medium">
                    <div class="form-half-md">
                        <div class="half-md-section">
                            <label for="shiplength">Lengte in meters*</label>
                            <input type="number" name="shiplength" required>
                        </div>

                        <div class="half-md-section">
                            <label for=shipwidth">Breedte in meters*</label>
                            <input type="number" name="shipwidth" required>
                        </div>

                        <div class="half-md-section">
                            <label for="shipheight">Hoogte in meters*</label>
                            <input type="number" name="shipheight" required>
                        </div>
                    </div>

                    <div class="form-half-md">
                        <div class="half-md-section">
                            <label for="shipdepth">Diepgang in meters*</label>
                            <input type="number" name="shipdepth" required>
                        </div>

                        <div class="half-md-section">
                            <label for="shipweight">Gewicht in kg*</label>
                            <input type="number" name="shipweight" required>
                        </div>

                        <div class="half-md-section">
                            <label for="shippower">Motorvermogen in pk*</label>
                            <input type="number" name="shippower" required>
                        </div>
                    </div>
                </div>

                <div class="formblock-submit">
                    <input type="submit" value="Schip opslaan" name="boten">
                    <input type="button" value="annuleren" onclick="closeForm()">
                </div>

            </form>

            <div class="sentError" id="<?= $sentId ?>">
                <p><?= $sentBoat ?></p>
            </div>

        </div>

        <!-- getBoats() is from functions.php -->
        <?php
        getBoats();
        ?>

    </main>

    <!-- footer -->
    <?php include "includes/footer.php"; ?>
</div>

<?php

?>

<script>
    // open & close form on button click
    function openForm() {
        document.getElementById("boatForm").style.display = "block";
    }

    function closeForm() {
        if (confirm("Weet je zeker dat je het formulier wilt resetten?")) {
            document.getElementById("boatForm").reset();
            document.getElementById("boatForm").style.display = "none";
        }
    }
</script>

</body>
</html>

<?php
