<!doctype html>
<html lang="en">
<head>
    <style>
        @media only screen and (max-width:750px) {
            .bottom-sidebar-mobile .bestemming-single-sidebar {
                margin: auto 2.5% !important;
            }
        }
        @media only screen and (max-width:440px) {
            .bottom-sidebar-mobile .bestemming-single-sidebar {
                margin: auto !important;
                display: block !important;
            }
        }
    </style>
    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_inschrijven.php';

    //region set session if they don't exist
    if (!isset($captchaError)) {
        $captchaError = "";
    }

    //region about applicant
    if (!isset($_SESSION["app-name"])) {
        $_SESSION['app-name'] = "";
    }
    if (!isset($_SESSION["app-address"])) {
        $_SESSION['app-address'] = "";
    }
    if (!isset($_SESSION["app-postal"])) {
        $_SESSION['app-postal'] = "";
    }
    if (!isset($_SESSION["app-residence"])) {
        $_SESSION['app-residence'] = "";
    }
    if (!isset($_SESSION["app-phone"])) {
        $_SESSION['app-phone'] = "";
    }
    if (!isset($_SESSION["app-email"])) {
        $_SESSION['app-email'] = "";
    }
    //endregion

    //region about business
    if (!isset($_SESSION["app-business"])) {
        $_SESSION['app-business'] = "";
    }
    if (!isset($_SESSION["app-business-name"])) {
        $_SESSION['app-business-name'] = "";
    }
    if (!isset($_SESSION["app-business-kvk"])) {
        $_SESSION['app-business-kvk'] = "";
    }
    if (!isset($_SESSION["app-business-btw"])) {
        $_SESSION['app-business-btw'] = "";
    }
    //endregion

    //region about ship - general
    if (!isset($_SESSION["app-ship-name"])) {
        $_SESSION['app-ship-name'] = "";
    }
    if (!isset($_SESSION["app-ship-brand"])) {
        $_SESSION['app-ship-brand'] = "";
    }
    if (!isset($_SESSION["app-ship-type"])) {
        $_SESSION['app-ship-type'] = "";
    }
    if (!isset($_SESSION["app-ship-port"])) {
        $_SESSION['app-ship-port'] = "";
    }
    //endregion

    //region about ship - dimensions
    if (!isset($_SESSION["app-ship-length-m"])) {
        $_SESSION['app-ship-length-m'] = "";
    }
    if (!isset($_SESSION["app-ship-length-cm"])) {
        $_SESSION['app-ship-length-cm'] = "";
    }
    if (!isset($_SESSION["app-ship-width-m"])) {
        $_SESSION['app-ship-width-m'] = "";
    }
    if (!isset($_SESSION["app-ship-width-cm"])) {
        $_SESSION['app-ship-width-cm'] = "";
    }
    if (!isset($_SESSION["app-ship-depth-m"])) {
        $_SESSION['app-ship-depth-m'] = "";
    }
    if (!isset($_SESSION["app-ship-depth-cm"])) {
        $_SESSION['app-ship-depth-cm'] = "";
    }
    if (!isset($_SESSION["app-ship-height-m"])) {
        $_SESSION['app-ship-height-m'] = "";
    }
    if (!isset($_SESSION["app-ship-height-cm"])) {
        $_SESSION['app-ship-height-cm'] = "";
    }
    //endregion

    //region about ship - other information
    if (!isset($_SESSION["app-ship-draft"])) {
        $_SESSION['app-ship-draft'] = "";
    }
    if (!isset($_SESSION["app-ship-year"])) {
        $_SESSION['app-ship-year'] = "";
    }
    if (!isset($_SESSION["app-ship-weight-ton"])) {
        $_SESSION['app-ship-weight-ton'] = "";
    }
    if (!isset($_SESSION["app-ship-weight-part"])) {
        $_SESSION['app-ship-weight-part'] = "";
    }
    if (!isset($_SESSION["app-ship-materials"])) {
        $_SESSION['app-ship-materials'] = "";
    }
    //endregion

    if (!isset($_SESSION["app-other"])) {
        $_SESSION['app-other'] = "";
    }
    //endregion

    ?>

    <script>
        //particulier of zakelijk
        function checkBusiness() {
            let selected = document.querySelector('input[name="particulier-zakelijk"]:checked').value;
            if (selected == "zakelijk") {
                //show business-section in form
                document.getElementById('business').style.display = "block";

                //set required to the inputs
                document.getElementById('name-business').setAttribute('required', 'required');
                document.getElementById('kvk-business').setAttribute('required', 'required');
                document.getElementById('btw-business').setAttribute('required', 'required');
            } else {
                //don't show business-section in form
                document.getElementById('business').style.display = "none";

                //remove required from the inputs
                document.getElementById('name-business').removeAttribute('required');
                document.getElementById('kvk-business').removeAttribute('required');
                document.getElementById('btw-business').removeAttribute('required');
            }
        }

    </script>
</head>

<body>

    <div class="main-area">
        <div class="main-no-footer">

            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- left sidebar -->
            <div class='left-sidebar inschrijven-left-sidebar' id="left-sidebar">
                <?php getSidebarDestinations("left"); ?>
            </div>

            <!-- main body -->
            <main class="inschrijven" id="main">

                <!--region inschrijf-form -->
                <?php

                // check if a destination has been selected
                if (!isset($_GET['id'])) {
                    $_GET['id'] = "";
                }
                $varId = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
                $sql = "SELECT * FROM bestemmingen WHERE id = '$varId'";

                $result = $conn->query($sql);
                $row = mysqli_fetch_assoc($result);

                $currentDate = date("Y-m-d", strtotime("now"));
                if ($_GET['id'] == "") {
                    $databaseDate = "";
                } else {
                    $databaseDate = date("Y-m-d", strtotime($row['departure_date']));
                }
                $noDate = false;

                if ($databaseDate <= date("Y-m-d", strtotime("1-1-1970"))) {
                    $noDate = true;
                }

                // if no destination has been selected or if the destination isn't valid, show this text and the sidebar in the center
                if(isset($_GET['voltooid'])) {
                    if ($_GET['voltooid'] == "true") {
                        echo '<h2>Uw inschrijving is gelukt.</h2>';
                        getSidebarDestinations("center");
                    }
                } else if ($result-> num_rows == 0) { ?>
                    <h2>Kies eerst een geldige bestemming:</h2>
                <?php
                    getSidebarDestinations("center");

                } else if ($noDate == false && $currentDate > $databaseDate) {?>
                    <h2>De vertrekdatum is al verstreken. Kies een andere bestemming:</h2>
                    <?php
                    getSidebarDestinations("center");

                } //if there are no more spaces
                else if ($row['plaatsen_over'] <= 0){?>
                    <h2>Er zijn geen plaatsen meer over bij deze bestemming. Kies een andere bestemming:</h2>
                    <?php
                    getSidebarDestinations("center");

                } //else: show the form
                else { ?>

                <form id="inschrijfform" name="inschrijfForm" method="post">

                    <!-- header -->
                    <?php
                    if ($noDate === true) {
                        $vertrekDatum = " (vertrekdatum nog niet bekend)";
                    } else {
                        $vertrekDatum = " op " . date("d-m-Y", strtotime($row['departure_date']));
                    }

                    ?>
                    <h2>Inschrijven voor <?= $row['destination'] . $vertrekDatum ?></h2>

                    <!--region intro-tekst -->
                        <p class="form-intro-text" style="margin-block-start: 0">Geachte heer/mevrouw,</p>
                        <p class="form-intro-text">Indien u geïnteresseerd bent dan kunt u deze aanvraag invullen, wij sturen u dan een offerte op maat. Indien u verder nog vragen heeft kunt U altijd contact opnemen met ons door het contactformulier in te vullen of uw vraag te stellen via ons mailadres <a href="mailto:<?= $mail ?>"><?=$mail?></a>. Wij nemen dan zo snel mogelijk contact met u op.</p>
                    <!--endregion intro-tekst -->

                    <!--region over de eigenaar -->
                    <div class="form-about">
                        <h4>Over u</h4>

                        <!-- naam eigenaar -->
                        <div class="formblock-about naam-eigenaar">
                            <label for="naam-eigenaar">Uw naam*:</label>
                            <input type="text" id="naam-eigenaar" name="naam-eigenaar" value="<?=$_SESSION['app-name']?>" required>
                        </div>

                        <!-- adres -->
                        <div class="formblock-about adres-eigenaar">
                            <label for="adres-eigenaar">Adres*:</label>
                            <input type="text" id="adres-eigenaar" name="adres-eigenaar" value="<?=$_SESSION['app-address']?>" required>
                        </div>

                        <!-- postcode -->
                        <div class="formblock-about postcode-eigenaar">
                            <label for="postcode-eigenaar">Postcode*:</label>
                            <input type="text" id="postcode-eigenaar" name="postcode-eigenaar" pattern="[0-9A-Za-z]{3-10}" value="<?=$_SESSION['app-postal']?>" required>
                        </div>

                        <!-- woonplaats -->
                        <div class="formblock-about woonplaats-eigenaar">
                            <label for="woonplaats-eigenaar">Woonplaats*:</label>
                            <input type="text" id="woonplaats-eigenaar" name="woonplaats-eigenaar" value="<?=$_SESSION['app-residence']?>" required>
                        </div>

                        <!-- telefoonnummer -->
                        <div class="formblock-about tel-eigenaar">
                            <label for="tel-eigenaar">Telefoonnummer*:</label>
                            <input type="tel" id="tel-eigenaar" name="tel-eigenaar" pattern="^((\+|00(\s|\s?\-\s?)?)31(\s|\s?\-\s?)?(\(0\)[\-\s]?)?|0)[1-9]((\s|\s?\-\s?)?[0-9])((\s|\s?-\s?)?[0-9])((\s|\s?-\s?)?[0-9])\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]\s?[0-9]$" value="<?=$_SESSION['app-phone']?>" required>
                        </div>

                        <!-- email -->
                        <div class="formblock-about email-eigenaar">
                            <label for="email-eigenaar">Email*:</label>
                            <input type="email" id="email-eigenaar" name="email-eigenaar" value="<?=$_SESSION['app-email']?>" required>
                        </div>


                    </div>
                    <!--endregion over de eigenaar -->

                    <!-- zakelijk of particulier -->
                    <div class="formblock-about zakelijk-particulier">
                        <input type="radio" id="particulier" name="particulier-zakelijk" value="particulier" onchange="checkBusiness()"
                            <?php
                                if ($_SESSION['app-business'] != "zakelijk") {
                                    echo "checked";
                                }
                            ?>
                        >
                        <label>Particulier</label>
                        <input type="radio" id="zakelijk" name="particulier-zakelijk" value="zakelijk" onchange="checkBusiness()"
                            <?php
                            if ($_SESSION['app-business'] == "zakelijk") {
                                echo "checked";
                            }
                            ?>
                        >
                        <label>Zakelijk</label>
                    </div>

                    <!-- region over het bedrijf -->
                    <div class="form-business" id="business">

                        <!-- bedrijfsnaam -->
                        <div class="formblock-business name-business">
                            <label for="name-business">Bedrijfsnaam*:</label>
                            <input type="text" id="name-business" name="name-business" value="<?=$_SESSION['app-business-name']?>">
                        </div>

                        <!-- KVK-nummer -->
                        <div class="formblock-business kvk-business">
                            <label for="kvk-business">KVK-nummer*:</label>
                            <!-- NL KVK-nummer -->
                            <input type="number" id="kvk-business" name="kvk-business" pattern="([0-9]{8})" value="<?=$_SESSION['app-business-kvk']?>">
                        </div>

                        <!-- BTW-nummer -->
                        <div class="formblock-business btw-business">
                            <label for="btw-business">BTW-nummer*:</label>
                            <!-- NL BTW-nummer -->
                            <input type="text" id="btw-business" name="btw-business" pattern="([Nn][Ll])([0-9]{9})([Bb])([0-9]{2})" value="<?=$_SESSION['app-business-btw']?>">
                        </div>

                    </div>
                    <!--region over het schip -->
                    <div class="form-ship">

                        <h4>Over uw schip</h4>

                        <!--region basis informatie schip -->
                        <div class="basic-ship-info">

                            <!-- naam schip -->
                            <div class="formblock-ship naam-schip">
                                <label for="naam-schip">Naam schip:</label>
                                <input type="text" id="naam-schip" name="naam-schip" value="<?=$_SESSION['app-ship-name']?>">
                            </div>

                            <!-- soort schip -->
                            <div class="formblock-ship soort-schip">
                                <label for="soort-schip">Soort schip*:</label>
                                <select id="soort-schip" name="soort-schip" required>
                                    <option value="Motorboot"
                                    <?php
                                        if ($_SESSION['app-ship-type'] == "Motorboot") {
                                            echo "selected";
                                        }
                                    ?>
                                    >Motorboot</option>
                                    <option value="Zeilboot"
                                        <?php
                                        if ($_SESSION['app-ship-type'] == "Zeilboot") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Zeilboot</option>
                                    <option value="Sleepboot"
                                        <?php
                                        if ($_SESSION['app-ship-type'] == "Sleepboot") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Sleepboot</option>
                                </select>
                            </div>

                            <!-- merk/type schip -->
                            <div class="formblock-ship merk-schip">
                                <label for="merk-schip">Merk/type schip*:</label>
                                <input type="text" name="merk-schip" id="merk-schip" value="<?=$_SESSION['app-ship-brand']?>" required>
                            </div>

                            <!-- thuishaven schip -->
                            <div class="formblock-ship thuishaven-schip">
                                <label for="thuishaven-schip">Thuishaven schip:</label>
                                <input type="text" name="thuishaven-schip" id="thuishaven-schip" value="<?=$_SESSION['app-ship-port']?>">
                            </div>
                        </div>
                        <!--endregion basis informatie schip -->

                        <!--region afmetingen schip -->
                        <div class="afmetingen-schip">
                            <h5>Afmetingen en data</h5>

                            <!-- Lengte over alles -->
                            <div class="formblock-ship lengte-schip form-half-width">
                                <label for="lengte-schip">Lengte over alles (m)*:</label>
                                <div class="inputs">
                                    <input type="number" name="lengte-schip-m" id="lengte-schip-m" min="0" step="1" value="<?=$_SESSION['app-ship-length-m']?>" required><span>,</span>
                                    <input type="number" name="lengte-schip-c" id="lengte-schip-c" min="0" max="99" step="1" value="<?=$_SESSION['app-ship-length-cm']?>">
                                </div>
                            </div>

                            <!-- Breedte over alles -->
                            <div class="formblock-ship breedte-schip form-half-width">
                                <label for="breedte-schip">Breedte over alles (m)*:</label>
                                <div class="inputs">
                                <input type="number" name="breedte-schip-m" id="breedte-schip-m" min="0" step="1" value="<?=$_SESSION['app-ship-width-m']?>" required><span>,</span>
                                <input type="number" name="breedte-schip-c" id="breedte-schip-c" min="0" max="99" step="1" value="<?=$_SESSION['app-ship-width-cm']?>">
                                </div>
                            </div>

                            <!-- Diepgang -->
                            <div class="formblock-ship diepgang-schip form-half-width">
                                <label for="diepgang-schip">Diepgang (m)*:</label>
                                <div class="inputs">
                                <input type="number" name="diepgang-schip-m" id="diepgang-schip-m" min="0" step="1" value="<?=$_SESSION['app-ship-depth-m']?>" required><span>,</span>
                                <input type="number" name="diepgang-schip-c" id="diepgang-schip-c" min="0" max="99" step="1" value="<?=$_SESSION['app-ship-depth-cm']?>">
                                </div>
                            </div>

                            <!-- Minimale hoogte boven water -->
                            <div class="formblock-ship boven-water-schip form-half-width">
                                <label for="boven-water-schip">Minimale hoogte boven water (m)*:</label>
                                <div class="inputs">
                                <input type="number" name="boven-water-schip-m" id="boven-water-schip-m" min="0" step="1" value="<?=$_SESSION['app-ship-height-m']?>" required><span>,</span>
                                <input type="number" name="boven-water-schip-c" id="boven-water-schip-c" min="0" max="99" step="1" value="<?=$_SESSION['app-ship-height-cm']?>">
                                </div>
                            </div>
                        </div>

                        <!--endregion afmetingen schip -->

                        <!--region andere informatie schip-->
                        <div class="detailed-ship-info">
                            <!-- Type kiel -->
                            <div class="formblock-ship kiel-schip">
                                <label for="kiel-schip">Type kiel*:</label>
                                <select id="kiel-schip" name="kiel-schip" required>
                                    <option value="Diepe V"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "Diepe V") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Diepe V</option>
                                    <option value="Lange kiel"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "Lange kiel") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Lange kiel</option>
                                    <option value="Diepe kiel"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "Diepe kiel") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Diepe kiel</option>
                                    <option value="Kim kiel"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "Kim kiel") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Kim kiel</option>
                                    <option value="1x scheg"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "1x scheg") {
                                            echo "selected";
                                        }
                                        ?>
                                    >1x scheg</option>
                                    <option value="2x scheg"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "2x scheg") {
                                            echo "selected";
                                        }
                                        ?>
                                    >2x scheg</option>
                                    <option value="Overig"
                                        <?php
                                        if ($_SESSION['app-ship-draft'] == "Overig") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Overig</option>
                                </select>
                            </div>

                            <!-- Bouwjaar -->
                            <div class="formblock-ship bouwjaar-schip">
                                <label for="bouwjaar-schip">Bouwjaar schip:</label>
                                <input type="number" name="bouwjaar-schip" id="bouwjaar-schip" min="1900" max="<?= date("Y") ?>" value="<?=$_SESSION['app-ship-year']?>">
                            </div>

                            <!-- Gewicht -->
                            <div class="formblock-ship gewicht-schip form-half-width">
                                <label for="gewicht-schip">Gewicht (ton)*:</label>
                                <div class="inputs">
                                    <input type="number" name="gewicht-schip-t" id="gewicht-schip-t" min="0" step="1" value="<?=$_SESSION['app-ship-weight-ton']?>" required><span>,</span>
                                    <input type="number" name="gewicht-schip-s" id="gewicht-schip-s" min="0" max="9" step="1" value="<?=$_SESSION['app-ship-weight-part']?>">
                                </div>
                            </div>

                            <!-- Bouwmateriaal romp -->
                            <div class="formblock-ship materiaal-schip">
                                <label for="materiaal-schip">Bouwmateriaal romp*:</label>
                                <select id="materiaal-schip" name="materiaal-schip" required>
                                    <option value="Staal"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Staal") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Staal</option>
                                    <option value="Aluminium"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Aluminium") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Aluminium</option>
                                    <option value="Polyester"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Polyester") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Polyester</option>
                                    <option value="Woodcore"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Woodcore") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Woodcore</option>
                                    <option value="Composiet"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Composiet") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Composiet</option>
                                    <option value="Hout"
                                        <?php
                                        if ($_SESSION['app-ship-materials'] == "Hout") {
                                            echo "selected";
                                        }
                                        ?>
                                    >Hout</option>
                                </select>
                            </div>
                        </div>
                        <!--endregion andere informatie schip -->

                    </div>
                    <!--endregion over het schip -->

                    <!--region overig -->
                    <!-- overige vragen of opmerkingen -->
                    <div class="form-remarks">
                        <label for="vragen-opmerkingen">Overige vragen of opmerkingen:</label>
                        <textarea name="vragen-opmerkingen" id="vragen-opmerkingen" rows="8" cols="auto"><?=$_SESSION['app-other']?></textarea>
                    </div>

                    <!-- hidden info -->
                    <input type="hidden" name="destination" value="<?=$row['destination']?>" readonly>
                    <input type="hidden" name="departure-date" value="<?=$row['departure_date']?>" readonly>

                    <!-- self-made captcha -->
                    <div class="contact-captcha">
                        <label for="captcha">Captcha:</label>
                        <input type="text" id="captcha" name="captcha_challenge" pattern="^[a-zA-Z0-9]{6}$" autocomplete="off"><br>
                        <div class="captcha-img"><img src="includes/captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fas fa-redo refresh-captcha" onclick="refreshButton()"></i></div>
                        <div><?=$captchaError?></div>
                    </div>

                    <!-- submit & cancel buttons -->
                    <div class="formblock-submit">

                        <!-- submit -->
                        <input type="submit" value="Verzenden" name="inschrijven">

                        <!-- cancel: resetApplicationForm() is in config.js -->
                        <input type="button" value="Annuleren" onclick="resetApplicationForm()">
                    </div>
                    <!--endregion overig-->

                    <div class="sentError" id="<?= $sentId ?>">
                        <p><?= $sentApplication ?></p>
                    </div>

                </form>
                <?php } ?>
                <!--endregion inschrijf-form -->

            </main>

            <!-- right sidebar -->
            <div class='right-sidebar inschrijven-right-sidebar' id="right-sidebar">
                <?php getSidebarDestinations("right"); ?>
            </div>

            <!-- bottom sidebar for mobile -->
            <div class="bottom-sidebar-mobile inschrijven-bottom-sidebar" id="bottom-sidebar">
                <?php getSidebarDestinations("center"); ?>
            </div>

        </div>

        <!-- footer -->
        <?php include "includes/footer.php"; ?>
    </div>

</body>

<script>
    refreshButton = function () {
        document.querySelector(".captcha-image").src = 'includes/captcha.php?' + Date.now();
    }

    checkBusiness();
</script>

</html>