<?php
//region Url's in navigation

//region Url-variables
$aboutUs = "index";
$contact = "contact";
$bestemmingen = "bestemmingen";
$inschrijven = "inschrijven";
$nieuws = "nieuws";
$buitenland = "buitenland";
$inloggen = "inloggen";
$uitloggen = "uitloggen";

//deprecated
$reviews = "reviews";
$boten = "boten";
$trips = "trips";
//endregion

// Setting variable for errormessage uploads
$uploadError = "";

// to compare the variables, to the current page:
$currentPage = basename($_SERVER['SCRIPT_FILENAME'], '.php');

//region captcha unset sessions if moving to other page

    //region contact
        if ($currentPage !== 'contact') {
            unset($_SESSION['useremail']);
            unset($_SESSION['userquestion']);
        }
    //endregion

    //region inschrijvingen
        if ($currentPage !== 'inschrijven') {
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
        }

    //endregion

//endregion

//region navUrl()
// called if navigating using the navigation bar
function navUrl() {
    // using either https or http
    if (isset($_SERVER['HTTPS'])) {
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    } else {
        $protocol = 'http';
    }
    // if different page from current page, returning full path
    return $protocol . "://" . $_SERVER['HTTP_HOST']  . "/liftaboat-new/public/";
}
//endregion

//region url() -> Base Url
function url(){
    if(isset($_SERVER['HTTPS'])){
        $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
    }
    else{
        $protocol = 'http';
    }
    return $protocol . "://" . $_SERVER['HTTP_HOST'];
}
//endregion
//endregion
?>