<?php
// on form-sending, send to database
if (isset($_POST['contact'])) {

    // Saving the inputs as variables
    $senderEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senderQuestion = filter_var($_POST['vraag'], FILTER_SANITIZE_STRING);

    if(isset($_POST['captcha_challenge']) && strtolower($_POST['captcha_challenge']) == strtolower($_SESSION['captcha_text'])) {

        // inserting it into the contact-table
        $sql = "INSERT INTO contact (email, vraag) VALUES
                    ('$senderEmail', '$senderQuestion')";

        $mailBody = wordwrap($senderQuestion);
        $mailToSender = "Uw bericht is verstuurd naar $mail. Wij zullen hier zo spoedig mogelijk op terugkomen.
    
    Uw verzonden bericht:
    $mailBody";

        $to = $mail;
        $subject = "Vraag ontvangen van $senderEmail";
        $headers = "From: $senderEmail";

        mail($to, $subject, $mailBody, $headers);
        mail($senderEmail, "Uw bericht is verstuurd", $mailToSender, "From: $mail");

        // Success and error notifications for user
        if ($conn->query($sql) === TRUE) {
            global $sentQuestion;
            global $sentId;
            $sentQuestion = "Uw vraag is verzonden. We komen hier zo spoedig mogelijk op terug.";
            $sentId = "SentSuccessful";
            unset($_SESSION['useremail']);
            unset($_SESSION['userquestion']);
        } else {
            $sentQuestion = "Er is een fout opgetreden. Probeer het over 5 minuten nog een keer.<br>Als deze fout blijft optreden, neem dan contact met ons op via $mail.";
            $sentId = "SentError";
        }
    } else {
        $captchaError = "You entered a wrong captcha. Please try again";
        $_SESSION['useremail'] = $senderEmail;
        $_SESSION['userquestion'] = $senderQuestion;
    }
}
?>