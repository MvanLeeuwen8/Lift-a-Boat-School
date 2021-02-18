<?php

    //use phpmailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require_once "../../vendor/phpmailer/src/Exception.php";
    require_once "../../vendor/phpmailer/src/PHPMailer.php";
    include "config.php";

    // Create connection
    global $servername, $username, $password, $database;
    $conn = mysqli_connect( $servername, $username, $password, $database);

    // Check connection
    if (!$conn) {
        //error logging
        $error_message = "Connection failed: " . mysqli_connect_error();
        $log_file = "../../logs/error_log.log";
        error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
        die("Connection failed: " . mysqli_connect_error());
    }

    $id = $_GET['id'];

    // connect to correct row
    $sql = "SELECT * FROM inschrijven WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $appEmail = $row['email'];
    $appName = $row['naam'];
    $appTel = $row['telefoon'];
    $pdfName = $_GET['pdfname'];
    $destination = $row['bestemming'];
    $departureDate = $row['vertrekdatum'];
    $shipName = $row['naam_schip'];
    $port = $row['thuishaven'];
    $shipYear = $row['bouwjaar'];
    $extraInfo = $row['vragen_opmerkingen'];

    //region Email to applicant

        $mailToApplicant = new PHPMailer(true);

        // From Lift a Boat
        $mailToApplicant->From = "noreply@liftaboat.nl";
        $mailToApplicant->FromName = "Lift a Boat";

        // To applicant
        $mailToApplicant->addAddress($appEmail, $appName);

        // Add pdf as attachment
        $mailToApplicant->addAttachment("../../invoices/" . $pdfName);

        // Reply email
        $mailToApplicant->addReplyTo("info@liftaboat.nl", "Reply");

        // Set html
        $mailToApplicant->isHTML(true);

        // Start of email
        $mailToApplicant->Subject = "Uw inschrijving bij LiftaBoat";
        $mailToApplicant->Body = "
                        <p>Dank u wel voor het inschrijven bij Lift a Boat.</p>
                        <p>We nemen zo spoedig mogelijk contact met u op. Mocht u nog vragen hebben, dan graag via het contactformulier of via <a href='mailto:info@liftaboat.nl'>info@liftaboat.nl</a></p>
                        <p>Met vriendelijke groet,<br>Lift a Boat</p>
        ";

        try {
            $mailToApplicant->send();
        } catch (Exception $e) {
            //error logging
            $error_message = "Mailer error: " . $mailToApplicant->ErrorInfo;
            $log_file = "../../logs/error_log.log";
            error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
            echo "Mailer Error: " . $mailToApplicant->ErrorInfo;
        }

    //endregion Email to applicant

    //region Email to LiftaBoat

    $mailToLAB = new PHPMailer(true);

    // From applicant
    $mailToLAB->From = $appEmail;
    $mailToLAB->FromName = $appName;

    // To Lift a Boat
    $mailToLAB->addAddress("info@liftaboat.nl", "Lift a Boat");

    // Add pdf as attachment
    $mailToLAB->addAttachment("../../invoices/" . $pdfName);

    // Reply email
    $mailToLAB->addReplyTo($appEmail, "Reply");

    // Set html
    $mailToLAB->isHTML(true);

    // Start of email
    $mailToLAB->Subject = "Nieuwe inschrijving: " . sprintf('%06d', $id);
    $mailToLAB->Body = "
                        <p>Er is een nieuwe inschrijving ontvangen voor de reis naar " . $destination . "`op " . $departureDate . "</p>".
                        "<p>Naam: " . $appName . "<br>Email: " . $appEmail . "<br>Telnr: " . $appTel . "</p>" .
                        "<p>Over het schip:<br>Naam schip: " . $shipName . "<br>Thuishaven schip: " . $port . "<br>Bouwjaar schip: " . $shipYear . "</p>" .
                        "<p>Overige vragen of opmerkingen:<br>" . $extraInfo .
                        "</p><p>Voor meer informatie, zie de bijlage.</p>
        ";

    try {
        $mailToLAB->send();
    } catch (Exception $e) {
        //error logging
        $error_message = "Mailer error: " . $mailToLAB->ErrorInfo;
        $log_file = "../../logs/error_log.log";
        error_log(date("Y-m-d H:i:s") . " " . $error_message . "\n", 3, $log_file);
        echo "Mailer Error: " . $mailToLAB->ErrorInfo;
    }

    //endregion Email to LiftaBoat

header("Location:../inschrijven.php?voltooid=true");