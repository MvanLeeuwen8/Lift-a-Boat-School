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

            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- left sidebar -->
            <div class='left-sidebar' id="left-sidebar">
                <?php getSidebarDestinations("left"); ?>
            </div>

            <!-- main body -->
            <main class="meer-info" id="main">

                <!-- region modal -->
                <div id="foto-modal" class="modal">
                    <div class="modal-content">
                        <span id="close"></span>
                        <img id="modal-image" src="">
                        <p id="img-text"></p>
                    </div>
                </div>
                <!--endregion modal -->

                <!--region show destination-info -->
                <?php

                //Check to see if destination has been passed through
                if (!isset($_GET['id'])) {
                    $_GET['id'] = "";
                }
                $sentId = $_GET['id'];
                $sql = "SELECT * FROM bestemmingen WHERE id = '$sentId'";
                $result = $conn->query($sql);

                // if no destination has been selected, show this text and the sidebar in the center
                if ($result-> num_rows == 0 ) { ?>
                    <h3>Kies eerst een geldige bestemming:</h3>
                    <?php
                    getSidebarDestinationsCenterInfo();

                } // Else show the information for the selected destination
                elseif ($result->num_rows > 0 ) {
                    while($row = mysqli_fetch_assoc($result)) {
                        $imageArray = explode(", ", $row['destination_image_names']);
                        ?>

                        <!-- Title-->
                        <h2><?=$row['destination']?></h2>

                        <!-- Inschrijf-button -->
                        <a href="inschrijven.php?id=<?=$row['id']?>"><button class="inschrijf-btn">Inschrijven</button></a>

                        <!-- Date -->
                        <?php if ($row['departure_date'] == '1970-01-01') {
                            $departureDate = "Nog niet bekend";
                        } else {
                            $departureDate = date("d-m-Y",strtotime($row['departure_date']));
                        }
                        ?>
                        <p class="departure-date">Vertrekdatum: <?=$departureDate?></p>

                        <!-- Short description -->
                        <p><?=$row['description_short']?></p>

                        <div>

                            <!-- Main image -->
                            <div class="info-main-img">
                                <img src="../uploads/<?=$imageArray[0]?>">
                            </div>

                            <!--region prices -->
                            <div class="prices">
                                <?php
                                    // set price to "op aanvraag" if price = 0
                                    //set variables euro-sign
                                    $sign_9_12 = $sign_13 = $sign_14 = $sign_15 = $sign_16 = $sign_17 = $sign_18 = $sign_19 = $sign_20 = "€ ";

                                    //region set prices to "op aanvraag" if price = 0
                                    if ($row['price_9_12'] == 0 ) {
                                        $row['price_9_12'] = "Op aanvraag";
                                        $sign_9_12 = "";
                                        }
                                    if ($row['price_13'] == 0 ) {
                                        $row['price_13'] = "Op aanvraag";
                                        $sign_13 = "";
                                    }
                                    if ($row['price_14'] == 0 ) {
                                        $row['price_14'] = "Op aanvraag";
                                        $sign_14 = "";
                                    }
                                    if ($row['price_15'] == 0 ) {
                                        $row['price_15'] = "Op aanvraag";
                                        $sign_15 = "";
                                    }
                                    if ($row['price_16'] == 0 ) {
                                        $row['price_16'] = "Op aanvraag";
                                        $sign_16 = "";
                                    }
                                    if ($row['price_17'] == 0 ) {
                                        $row['price_17'] = "Op aanvraag";
                                        $sign_17 = "";
                                    }
                                    if ($row['price_18'] == 0 ) {
                                        $row['price_18'] = "Op aanvraag";
                                        $sign_18 = "";
                                    }
                                    if ($row['price_19'] == 0 ) {
                                        $row['price_19'] = "Op aanvraag";
                                        $sign_19 = "";
                                    }
                                    if ($row['price_20'] == 0 ) {
                                        $row['price_20'] = "Op aanvraag";
                                        $sign_20 = "";
                                    }
                                    //endregion set price to "op aanvraag" if price = 0
                                ?>

                                <!--region table prices -->
                                <table>
                                    <tr>
                                        <th>lengte van de boot</th>
                                        <th>Prijs</th>
                                    </tr>
                                    <tr>
                                        <td>9-12 m</td>
                                        <td><?=$sign_9_12 . $row['price_9_12']?></td>
                                    </tr>
                                    <tr>
                                        <td>13 m</td>
                                        <td><?=$sign_13 . $row['price_13']?></td>
                                    </tr>
                                    <tr>
                                        <td>14 m</td>
                                        <td><?=$sign_14 . $row['price_14']?></td>
                                    </tr>
                                    <tr>
                                        <td>15 m</td>
                                        <td><?=$sign_15 . $row['price_15']?></td>
                                    </tr>
                                    <tr>
                                        <td>16 m</td>
                                        <td><?=$sign_16 . $row['price_16']?></td>
                                    </tr>
                                    <tr>
                                        <td>17 m</td>
                                        <td><?=$sign_17 . $row['price_17']?></td>
                                    </tr>
                                    <tr>
                                        <td>18 m</td>
                                        <td><?=$sign_18 .$row['price_18']?></td>
                                    </tr>
                                    <tr>
                                        <td>19 m</td>
                                        <td><?=$sign_19 . $row['price_19']?></td>
                                    </tr>
                                    <tr>
                                        <td>20 m</td>
                                        <td><?=$sign_20 . $row['price_20']?></td>
                                    </tr>
                                </table>
                                <!-- endregion table prices -->
                            </div>
                            <!--endregion prices -->

                        </div>

                        <!-- Long description -->
                        <div class="info-lang">
                            <p><?=$row['description_long']?></p>
                        </div>

                        <!--region image-gallery -->
                        <div class="gallery">
                            <?php
                            $textArray = explode("___ ", $row['destination_image_text']);

                            for ($i = 0; $i < count($imageArray); $i++) {
                                echo '<img id="image-'. $i . '" src="../uploads/' . $imageArray[$i] . '" onclick="openPicture(' . $i . ', ' .  $i . ')">';
                            }
                            ?>
                        </div>
                        <!--endregion image-gallery-->

                <script>
                    //region Modal function
                    // Get the modal
                    let modal = document.getElementById("foto-modal");

                    // When the user clicks the button, open the modal
                    function openPicture(pictureId, pictureNumber) {
                        let textArray = <?= json_encode($textArray)?>;
                        let text = textArray[pictureNumber];
                        if (text === undefined) {
                            text = "";
                        }
                        modal.style.display = "block";
                        document.getElementById("modal-image").src = document.getElementById("image-" + pictureId).src;
                        document.getElementById("img-text").innerText = text;
                    }

                    // When the user clicks on <span> (x), close the modal
                    document.getElementById("close").onclick = function() {
                        modal.style.display = "none";
                    }

                    // When the user clicks anywhere outside of the modal, close it
                    window.onclick = function(event) {
                        if (event.target == modal) {
                        modal.style.display = "none";
                        }
                    }
                    //endregion
                </script>

                <!-- inschrijf-button -->
                <a href="inschrijven.php?id=<?=$row['id']?>"><button>Inschrijven</button></a>

                <?php
                    }
                }
                ?>
                <!--endregion show destination info -->

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
