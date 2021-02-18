<!doctype html>
<html lang="en">
<head>
<?php
require 'includes/head.php';
?>
</head>

<body>
    <div class="main-area" id="home">
        <div class="main-no-footer">

            <?php
                $conn = mysqli_connect( $servername, $username, $password, $database);
                // Check connection
                if (!$conn) {

                    //error logging
                    $error_message = "Connection failed: " . mysqli_connect_error();
                    $log_file = "../logs/error_log.log";
                    error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
                    die("Connection failed: " . mysqli_connect_error());
                }

                $sql = "SELECT * FROM bestemmingen WHERE plaatsen_over > 0 ORDER BY CASE WHEN departure_date = '1970-01-01' THEN 2 ELSE 1 END, departure_date";

                $result = mysqli_query($conn, $sql);

                if($result->num_rows > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $destinationId = $row['id'];
                    $DMY = date("d-m-Y",strtotime($row['departure_date']));
                    $month = date('n', strtotime($row['departure_date']));

                    //region Dutch months
                    if ($month == 1) {
                        $fullMonth = "januari";
                    } else if ($month == 2) {
                        $fullMonth = "februari";
                    } else if ($month == 3) {
                        $fullMonth = "maart";
                    } else if ($month == 4) {
                        $fullMonth = "april";
                    } else if ($month == 5) {
                        $fullMonth = "mei";
                    } else if ($month == 6) {
                        $fullMonth = "juni";
                    } else if ($month == 7) {
                        $fullMonth = "juli";
                    } else if ($month == 8) {
                        $fullMonth = "augusutus";
                    } else if ($month == 9) {
                        $fullMonth = "september";
                    } else if ($month == 10) {
                        $fullMonth = "oktober";
                    } else if ($month == 11) {
                        $fullMonth = "november";
                    } else if ($month == 12) {
                        $fullMonth = "december";
                    }
                    //endregion

                    $day = date("d", strtotime($row['departure_date']));
                    $year = date("Y", strtotime($row['departure_date']));
                    $weekday = date('D', strtotime($row['departure_date']));

                    //region days
                    if ($weekday == "Mon") {
                        $weekday = "Maandag";
                    } else if ($weekday == "Tue") {
                        $weekday = "Dinsdag";
                    } else if ($weekday == "Wed") {
                        $weekday = "Woensdag";
                    } else if ($weekday == "Thu") {
                        $weekday = "Donderdag";
                    } else if ($weekday == "Fri") {
                        $weekday = "Vrijdag";
                    } else if ($weekday == "Sat") {
                        $weekday = "Zaterdag";
                    } else if ($weekday == "Sun") {
                        $weekday = "Zondag";
                    }
                    //endregion

                    $dateNextTrip = $day . " " . $fullMonth . " " . $year;

                    $destinationNextTrip = $row['destination'];

                    $spaceLeft = $row['plaatsen_over'];

                } else {
                    $dateNextTrip = "";
                    $destinationNextTrip = "";
                }

            ?>

            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- left sidebar -->
            <div class='left-sidebar' id="left-sidebar">
                <?php getSidebarDestinations("left"); ?>
            </div>

            <!-- main body -->
            <main class="home" id="main">

                <!--region About us -->
                    <h2>Wilt u met uw motorjacht op vakantie naar de allermooiste plaatsen in Duitsland en Frankrijk aan de Rijn?</h2>
                    <p>Wij organiseren transporten, waarbij we uw jacht vervoeren in het ruim van een binnenvaartschip de Rijn op. U kunt op die manier al uw vakantiedagen benutten op deze mooie rivier of een van de mooie zijrivieren. Aan het eind van uw vakantie kunt u stroomafwaarts de Rijn af richting Nederland.</p>

                    <h2>Hoe gaat het in zijn werk?</h2>
                    <p>Uw jacht wordt door een gerenommeerd kraanbedrijf in een centraal gelegen Nederlandse havens aan boord gehesen in het ruim van een binnenvaartschip en zorgvuldig door ervaren mensen op bokken vastgezet. Als uw jacht ingeladen is bieden wij u de service om u naar het dichtstbijzijnde treinstation te vervoeren. Als alle jachten ingeladen zijn vertrekt het schip naar de plaats van bestemming. Drie a vier dagen later organiseren wij een touringcar, die u naar de plaats van bestemming brengt. U komt aan in de haven, uw jacht wordt uit het schip gehesen en voor u in het water gelegd. U neemt uw jacht in ontvangst en vanaf dat moment begint de ultieme vakantie!</p>
                    <p>U kunt vanaf die plaats alle mooie zijrivieren (Neckar, Ruhr, Moezel, Main, Donau, Lahn, enz.) bevaren en/of op uw eigen tempo stroomafwaarts de Rijn af zakken richting Nederland. U komt dan langs de allermooiste plaatsen, zoals Mainz, Bingen, Rudesheim, St Goar (Lorelei), Lahnstein, Koblenz, Andernach, Bonn, Keulen, Dusseldorf en Duisburg. En dat alles beleeft u aan boord van uw eigen motorjacht!</p>

                <?php if (isset($destinationId)) {
                    echo '<p class="next-destination"><b><a href="meer-info.php?id=' . $destinationId . '">' . $weekday . " " . $dateNextTrip . ' organiseren we ons eerste transport naar ' . $destinationNextTrip . '. Er is nog plaats voor ' . $spaceLeft . ' jachten</a></b></p>';
                }
                ?>

                    <p style="margin-block-start: 0; margin-block-end: 0">Alle prijzen zijn inclusief!<br>
                    <ul style="margin-block-start:0; margin-block-end: 0">
                        <li>Vervoer naar het station in Nederland en vervoer naar uw vakantiebestemming in Duitsland</li>
                        <li>Uw jacht is tijdens ons transport compleet verzekerd (in de kraan en in het binnenvaartschip)!</li>
                    </ul></p>

                    <p>Mocht u na dit lezen geïnteresseerd zijn in een van onze geplande reizen, dan kunt u zich hier <a href="bestemmingen.php">inschrijven</a>.<br>Hebt u eerst nog vragen, wilt u nog aanvullende informatie of gaat uw voorkeur uit naar een andere datum en/of andere bestemming, dan kunt dit aangeven in het <a href="contact.php">contact formulier</a>.<br>Wij zullen dan zo snel mogelijk contact met u opnemen.</p>

                    <p>Met vriendelijke groeten,<br>Marco van Oeveren en Bram Sturm</p>
                <!--endregion About us -->

            </main>

            <!-- right sidebar -->
            <div class='right-sidebar' id="right-sidebar">
                <?php getSidebarDestinations("right"); ?>
            </div>

            <!-- bottom sidebar for mobile -->
            <div class="bottom-sidebar-mobile" id="bottom-sidebar">
                <?php getSidebarDestinations("center"); ?>
            </div>
        </div>


    <!-- footer -->
    <?php include "includes/footer.php"; ?>

    </div>
</body>
</html>
<?php
