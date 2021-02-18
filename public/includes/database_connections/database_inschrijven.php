<?php
//region Send to database - inschrijven
// on form-sending, send to database
$destinationError = "";
if (isset($_POST['inschrijven'])) {


    if(isset($_POST['captcha_challenge']) && strtolower($_POST['captcha_challenge']) == strtolower($_SESSION['captcha_text'])) {

        $varId = filter_var($_GET['id'], FILTER_SANITIZE_STRING);
        $currentDate = date("Y-m-d");
        $sql = "SELECT * FROM bestemmingen WHERE id = '$varId'";

        $result = $conn->query($sql);
        if ($result->num_rows == 0) {
            echo "misluktResult\n";
            $destinationError = "De bestemming bestaat niet of de vertrekdatum is verschreden.";
        } else {

            //region Saving the inputs as variables - inschrijven

            // about the owner
            $ownerName = filter_var($_POST['naam-eigenaar'], FILTER_SANITIZE_STRING);
            $ownerAddress = filter_var($_POST['adres-eigenaar'], FILTER_SANITIZE_STRING);
            $ownerPostalCode = filter_var($_POST['postcode-eigenaar'], FILTER_SANITIZE_STRING);
            $ownerResidence = filter_var($_POST['woonplaats-eigenaar'], FILTER_SANITIZE_STRING);
            $ownerPhone = filter_var($_POST['tel-eigenaar']);
            $ownerEmail = filter_var($_POST['email-eigenaar'], FILTER_SANITIZE_EMAIL);

            // about the ship
            $shipName = filter_var($_POST['naam-schip'], FILTER_SANITIZE_STRING);
            $shipType = filter_var($_POST['soort-schip'], FILTER_SANITIZE_STRING);
            $shipBrand = filter_var($_POST['merk-schip'], FILTER_SANITIZE_STRING);
            $shipPort = filter_var($_POST['thuishaven-schip'], FILTER_SANITIZE_STRING);

            // Dimensions
            // Length
            $shipLengthM = filter_var($_POST['lengte-schip-m'], FILTER_VALIDATE_INT);
            $shipLengthCm = filter_var($_POST['lengte-schip-c'], FILTER_VALIDATE_INT);
            $shipLength = number_format($shipLengthM + ($shipLengthCm / 100), 2);

            // Width
            $shipWidthM = filter_var($_POST['breedte-schip-m'], FILTER_VALIDATE_INT);
            $shipWidthCm = filter_var($_POST['breedte-schip-c'], FILTER_VALIDATE_INT);
            $shipWidth = number_format($shipWidthM + ($shipWidthCm / 100), 2);

            // Depth
            $shipDepthM = filter_var($_POST['diepgang-schip-m'], FILTER_VALIDATE_INT);
            $shipDepthCm = filter_var($_POST['diepgang-schip-c'], FILTER_VALIDATE_INT);
            $shipDepth = number_format($shipDepthM + ($shipDepthCm / 100), 2);

            // Height
            $shipAboveWaterM = filter_var($_POST['boven-water-schip-m'], FILTER_VALIDATE_INT);
            $shipAboveWaterCm = filter_var($_POST['boven-water-schip-c'], FILTER_VALIDATE_INT);
            $shipAboveWater = number_format($shipAboveWaterM + ($shipAboveWaterCm / 100), 2);

            // Weight
            $shipWeightT = filter_var($_POST['gewicht-schip-t'], FILTER_VALIDATE_INT);
            $shipWeightS = filter_var($_POST['gewicht-schip-s'], FILTER_VALIDATE_INT);
            $shipWeight = number_format($shipWeightT + ($shipWeightS / 100), 1);

            // Other ship information
            $shipKeel = filter_var($_POST['kiel-schip'], FILTER_SANITIZE_STRING);
            $shipBuildDate = filter_var($_POST['bouwjaar-schip'], FILTER_VALIDATE_INT);
            $shipMaterial = filter_var($_POST['materiaal-schip'], FILTER_SANITIZE_STRING);

            // other remarks, departure date, departure place
            $questionsRemarks = filter_var($_POST['vragen-opmerkingen'], FILTER_SANITIZE_STRING);
            $departureDate = filter_var($_POST['departure-date'], FILTER_SANITIZE_STRING);
            $destination = filter_var($_POST['destination'], FILTER_SANITIZE_STRING);

            // About the business
            $isBusiness = filter_var($_POST['particulier-zakelijk'], FILTER_SANITIZE_STRING);
            $businessName = filter_var($_POST['name-business'], FILTER_SANITIZE_STRING);
            $businessKvk = filter_var($_POST['kvk-business'], FILTER_VALIDATE_INT);
            $businessBtw = filter_var($_POST['btw-business'], FILTER_SANITIZE_STRING);

            //endregion

            //region Inserting the values into the inschrijven-tables
            $sql = "INSERT INTO inschrijven (naam, adres, postcode, woonplaats, telefoon, email, naam_schip, type, merk, thuishaven, lengte, breedte, diepgang, hoogte_boven_water, kiel, bouwjaar, gewicht, bouwmateriaal, bestemming, vertrekdatum, vragen_opmerkingen, bedrijfsnaam, btw_nummer, kvk_nummer ) VALUES
                   ('$ownerName', '$ownerAddress', '$ownerPostalCode', '$ownerResidence', '$ownerPhone', '$ownerEmail', '$shipName', '$shipType', '$shipBrand', '$shipPort', '$shipLength', '$shipWidth', '$shipDepth', '$shipAboveWater', '$shipKeel', '$shipBuildDate', '$shipWeight', '$shipMaterial', '$destination', '$departureDate', '$questionsRemarks', '$businessName', '$businessBtw', '$businessKvk')";
            //endregion

            //region Success & errormessages - inschrijven
            if ($conn->query($sql) === TRUE) {
                echo "gelukt!";
                $sentApplication = "Uw inschrijving is verzonden. We nemen zo spoedig mogelijk contact met u op.";
                $sentId = "SentSuccessful";
                $last_id = $conn->insert_id;

                //region Get price
                $price = "Op aanvraag";
                while ($row = mysqli_fetch_assoc($result)) {

                    //smaller than 9m
                    if ($shipLength < 9) {
                        $price = "Op aanvraag";
                    } // 9 - 12m
                    else if ($shipLength >= 9 && $shipLength <= 12) {
                        if ($row['price_9_12'] != 0) {
                            $price = $row['price_9_12'];
                        }
                    } // 12 - 13m
                    else if ($shipLength <= 13) {
                        if ($row['price_13'] != 0) {
                            $price = $row['price_13'];
                        }
                    } // 13 - 14m
                    else if ($shipLength <= 14) {
                        if ($row['price_14'] != 0) {
                            $price = $row['price_14'];
                        }
                    } // 14 - 15m
                    else if ($shipLength <= 15) {
                        if ($row['price_15'] != 0) {
                            $price = $row['price_15'];
                        }
                    } // 15 - 16m
                    else if ($shipLength <= 16) {
                        if ($row['price_16'] != 0) {
                            $price = $row['price_16'];
                        }
                    } // 16 - 17m
                    else if ($shipLength <= 17) {
                        if ($row['price_17'] != 0) {
                            $price = $row['price_17'];
                        }
                    } // 17 - 18m
                    else if ($shipLength <= 18) {
                        if ($row['price_18'] != 0) {
                            $price = $row['price_18'];
                        }
                    } // 18 - 19m
                    else if ($shipLength <= 19) {
                        if ($row['price_19'] != 0) {
                            $price = $row['price_19'];
                        }
                    } // 19 - 20m
                    else if ($shipLength <= 20) {
                        if ($row['price_20'] != 0) {
                            $price = $row['price_20'];
                        }
                    }

                    //larger than 20m will automatically say "Op aanvraag"
                }
                //endregion

                $sql = "SELECT * FROM bestemmingen WHERE id = '$varId'";
                $result = mysqli_query($conn, $sql);
                if($result->num_rows > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $spacesLeft = $row['plaatsen_over'];
                    if ($spacesLeft > 0) {
                        $sql = "UPDATE bestemmingen SET plaatsen_over=bestemmingen.plaatsen_over-1 WHERE id='$varId'";
                        if ($conn->query($sql) === TRUE) {
                            echo "Record updated successfully";
                        } else {
                            //error logging
                            $error_message = "Error updating record: " . $conn->error;
                            $log_file = "../../../logs/error_log.log";
                            error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
                            echo "Error updating record: " . $conn->error;
                        }
                    } else {
                        $destinationError = "Er zijn geen plaatsen meer over bij deze bestemming.";
                    }
                }

                //region replace spaces with %20
                //destination
                $destEnc = str_replace(" ", "%20", $destination);

                //port
                $portEnc = str_replace(" ", "%20", $shipPort);

                //type
                $typeEnc = str_replace(" ", "%20", $shipType);

                //brand
                $brandEnc = str_replace(" ", "%20", $shipBrand);

                //draft
                $draftEnc = str_replace(" ", "%20", $shipKeel);

                //materials
                $matsEnc = str_replace(" ", "%20", $shipMaterial);

                //name
                $nameEnc = str_replace(" ", "%20", $ownerName);

                //street
                $streetEnc = str_replace(" ", "%20", $ownerAddress);

                //postal
                $postalEnc = str_replace(" ", "%20", $ownerPostalCode);

                //residence
                $residenceEnc = str_replace(" ", "%20", $ownerResidence);

                //price
                $priceEnc = str_replace(" ", "%20", $price);

                //business
                $businessNameEnc = str_replace(" ", "%20", $businessName);
                //endregion

                header("Location:includes/create_pdf.php?id=" . $last_id . "&date=" . $departureDate . "&destination=" . $destEnc . "&departureplace=" . $portEnc . "&price=" . $priceEnc . "&type=" . $typeEnc . "&brand=" . $brandEnc . "&length=" . $shipLength . "&width=" . $shipWidth . "&depth=" . $shipDepth . "&minheight=" . $shipAboveWater . "&draft=" . $draftEnc . "&weight=" . $shipWeight . "&material=" . $matsEnc . "&name=" . $nameEnc . "&street=" . $streetEnc . "&postal=" . $postalEnc . "&residence=" . $residenceEnc . "&appemail=" . $ownerEmail. "&business=" . $isBusiness . "&businessname=" . $businessNameEnc . "&businesskvk=" . $businessKvk . "&businessbtw=" . $businessBtw );

                //unsetting the session data if they failed captcha before
                unset($_SESSION['app-name']);
                unset($_SESSION['app-address']);
                unset($_SESSION['app-postal']);
                unset($_SESSION['app-residence']);
                unset($_SESSION['app-phone']);
                unset($_SESSION['app-email']);

                unset($_SESSION['app-business']);
                unset($_SESSION['app-business-name']);
                unset($_SESSION['app-business-kvk']);
                unset($_SESSION['app-business-btw']);

                unset($_SESSION['app-ship-name']);
                unset($_SESSION['app-ship-brand']);
                unset($_SESSION['app-ship-type']);
                unset($_SESSION['app-ship-port']);

                unset($_SESSION['app-ship-length-m']);
                unset($_SESSION['app-ship-length-cm']);
                unset($_SESSION['app-ship-width-m']);
                unset($_SESSION['app-ship-width-cm']);
                unset($_SESSION['app-ship-depth-m']);
                unset($_SESSION['app-ship-depth-cm']);
                unset($_SESSION['app-ship-height-m']);
                unset($_SESSION['app-ship-height-cm']);

                unset($_SESSION['app-ship-draft']);
                unset($_SESSION['app-ship-year']);
                unset($_SESSION['app-ship-weight-ton']);
                unset($_SESSION['app-ship-weight-part']);
                unset($_SESSION['app-ship-materials']);

                unset($_SESSION['app-other']);

            } else {
                echo "mislukt:(";
                $sentApplication = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
                $sentId = "SentError";
            }
            //endregion
        }
    } else {
        $captchaError = "You entered a wrong captcha. Please try again";

        //applicant information
        $_SESSION['app-name'] = filter_var($_POST['naam-eigenaar'], FILTER_SANITIZE_STRING);
        $_SESSION['app-address'] = filter_var($_POST['adres-eigenaar'], FILTER_SANITIZE_STRING);
        $_SESSION['app-postal'] = filter_var($_POST['postcode-eigenaar'], FILTER_SANITIZE_STRING);
        $_SESSION['app-residence'] = filter_var($_POST['woonplaats-eigenaar'], FILTER_SANITIZE_STRING);
        $_SESSION['app-phone'] = filter_var($_POST['tel-eigenaar']);
        $_SESSION['app-email'] = filter_var($_POST['email-eigenaar'], FILTER_SANITIZE_EMAIL);

        //business information
        $_SESSION['app-business'] = filter_var($_POST['particulier-zakelijk'], FILTER_SANITIZE_STRING);
        $_SESSION['app-business-name'] = filter_var($_POST['name-business'], FILTER_SANITIZE_STRING);
        $_SESSION['app-business-kvk'] = filter_var($_POST['kvk-business'], FILTER_VALIDATE_INT);
        $_SESSION['app-business-btw'] = filter_var($_POST['btw-business'], FILTER_SANITIZE_STRING);

        //ship information
        $_SESSION['app-ship-name'] = filter_var($_POST['naam-schip'], FILTER_SANITIZE_STRING);
        $_SESSION['app-ship-type'] = filter_var($_POST['soort-schip'], FILTER_SANITIZE_STRING);
        $_SESSION['app-ship-brand'] = filter_var($_POST['merk-schip'], FILTER_SANITIZE_STRING);
        $_SESSION['app-ship-port'] = filter_var($_POST['thuishaven-schip'], FILTER_SANITIZE_STRING);

        //ship dimensions
        $_SESSION['app-ship-length-m'] = filter_var($_POST['lengte-schip-m'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-length-cm'] = filter_var($_POST['lengte-schip-c'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-width-m'] = filter_var($_POST['breedte-schip-m'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-width-cm'] = filter_var($_POST['breedte-schip-c'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-depth-m'] = filter_var($_POST['diepgang-schip-m'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-depth-cm'] = filter_var($_POST['diepgang-schip-c'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-height-m'] = filter_var($_POST['boven-water-schip-m'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-height-cm'] = filter_var($_POST['boven-water-schip-c'], FILTER_VALIDATE_INT);

        $_SESSION['app-ship-draft'] = filter_var($_POST['kiel-schip'], FILTER_SANITIZE_STRING);
        $_SESSION['app-ship-year'] = filter_var($_POST['bouwjaar-schip'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-weight-ton'] = filter_var($_POST['gewicht-schip-t'],FILTER_VALIDATE_INT);
        $_SESSION['app-ship-weight-part'] = filter_var($_POST['gewicht-schip-s'], FILTER_VALIDATE_INT);
        $_SESSION['app-ship-materials'] = filter_var($_POST['materiaal-schip'], FILTER_SANITIZE_STRING);

        //other
        $_SESSION['app-other'] = filter_var($_POST['vragen-opmerkingen'], FILTER_SANITIZE_STRING);

    }
}
//endregion
?>