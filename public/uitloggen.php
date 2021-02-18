<?php

// set session as an array to remove all the contents, then destroy the session
$_SESSION = array();
session_destroy();

// start session again to put the logged-in and admin statuses to false
session_start();
$_SESSION["logged-in"] = false;
$_SESSION["isAdmin"] = false;

// after logging out, send them to the "inloggen"-page
header("location: inloggen.php");

exit;