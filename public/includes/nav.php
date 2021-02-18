<!doctype html>

<?php
    //define variables
    $currentHome = "";
    $currentContact = "";
    $currentBestemmingen = "";
    $currentInschrijven = "";
    $currentNieuws = "";
    $currentReviews = "";
    $currentBuitenland = "";
    $currentInloggen = "";

    //get basename
    $basename = basename($_SERVER['PHP_SELF'], ".php");

    // set id to the current page
    if ($basename == "index") {
        $currentHome = "current-page";
    }

    if ($basename == "contact") {
        $currentContact= "current-page";
    }

    if ($basename == "bestemmingen") {
        $currentBestemmingen = "current-page";
    }

    if ($basename == "inschrijven") {
        $currentInschrijven = "current-page";
    }

    if ($basename == "nieuws") {
        $currentNieuws = "current-page";
    }

    if ($basename == "reviews") {
        $currentReviews = "current-page";
    }

    if ($basename == "buitenland") {
        $currentBuitenland = "current-page";
    }

    if ($basename == "inloggen" || $basename == "uitloggen") {
        $currentInloggen = "current-page";
    }

?>

<!-- navigation bar -->

<div class="nav-outside">
    <nav id="navbar">
        <div class="logo-nav-mobile"><a href="<?= navUrl() . $aboutUs ?>.php"><img id="mobile-logo-nav" src="assets/lab_staand_cirkel_180x180.png"></a></div>
        <div id="menuToggle">
            <!-- Facebook desktop -->
            <a class="facebook-navbar" href="<?= $facebook; ?>" target="_blank"><i class="fab fa-facebook"></i></a>
            <!--
            checkbox for mobile hamburger menu
            Checkbox configuration is in hamburger_menu.js
            -->
            <input id="checkbox" type="checkbox">
            <span></span>
            <span></span>
            <span></span>
            <div id="navLinks">

                <!-- function navUrl is in nav_urls.php -->
                <div class="logo-nav"><a href="<?= navUrl() . $aboutUs ?>.php"><img src="assets/lab_staand_cirkel_180x180.png"></a></div>
                <a class="hover-underline home-link" id="<?= $currentHome ?>" href="<?= navUrl() . $aboutUs?>.php">Home</a>
                <a class="hover-underline" id="<?= $currentBestemmingen ?>" href="<?= navUrl() . $bestemmingen?>.php">Bestemmingen</a>
                <!--
                <a class="hover-underline" id="<?= $currentInschrijven ?>" href="<?= navUrl() . $inschrijven?>.php">Inschrijven</a>
                -->
                <a class="hover-underline" id="<?= $currentNieuws ?>" href="<?= navUrl() . $nieuws ?>.php">Nieuws</a>
                <a class="hover-underline" id="<?= $currentBuitenland ?>" href="<?= navUrl() . $buitenland ?>.php">Varen in het buitenland</a>
                <a class="hover-underline" id="<?= $currentContact ?>" href="<?= navUrl() . $contact?>.php">Contact</a>

                <!-- Show either 'log in' or 'log out' depending on if the user is already logged in -->
                <?php /*if($_SESSION["logged-in"] === false || !isset($_SESSION["logged-in"])) {
                    echo "<a class='hover-underline' id='" . $currentInloggen . "' href='" . navUrl() . $inloggen . ".php'>Inloggen</a>";
                }
                if($_SESSION["logged-in"] === true ) {
                    echo "<a class='hover-underline' id='" . $currentInloggen . "' href='" . navUrl() . $uitloggen . ".php'>Uitloggen</a>";
                } */?>
                <!-- hidden for now
                <a class="hover-underline" href="<?= navUrl() . $boten?>">Boten</a>
                <a class="hover-underline" href="<?= navUrl() . $trips?>">Trips</a>
                -->

                <!-- var facebook is in variables.php -->
                <!-- Facebook mobile -->
                <a class="facebook-nav-mobile" href="<?= $facebook; ?>"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </nav>

</div>
<div class="banner"><img src="assets/banner_home.jpg"></div>