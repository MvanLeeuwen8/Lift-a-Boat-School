<?php
//empty variable for login errors
$errorLogin = "";

//region login
    if(isset($_POST['login'])) {
    $usernameLogin = filter_var($_POST['usernameLogin'], FILTER_SANITIZE_EMAIL);
    $passwordLogin = filter_var($_POST['passwordLogin'], FILTER_SANITIZE_STRING);

    $conn = mysqli_connect($servername, $username, $password, $database);

    // if the input is a username in the database, check if the hashed password is the same one as in the database
    $resultName = $conn->query("SELECT id, password, isAdmin, username FROM users WHERE username = '$usernameLogin'");
    $resultEmail = $conn->query("SELECT id, password, isAdmin, username FROM users WHERE email = '$usernameLogin'");

    //check if username matches one in the database
    if ($resultName->num_rows !== 0) {
        //if one matches, check if the hashed password matches
        $row = mysqli_fetch_assoc($resultName);
        $storedHash = $row['password'];
        $valid = password_verify($passwordLogin, $storedHash);
        if ($valid) {
            $_SESSION["logged-in"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["username"] = $row['username'];
            // if the username and password match: check admin status
            $isAdmin = $row['isAdmin'];
            if ($isAdmin == 1) {
                // log in as admin
                $_SESSION["isAdmin"] = true;
                header("location: index.php");
            } else {
                // log in as regular user
                $_SESSION["isAdmin"] = false;
                header("location: index.php");
            }
        } else {
            // Throw error: incorrect login
            $errorLogin = "De opgegeven gebruikersnaam of het opgegeven wachtwoord is onjuist.";
        }

    } else if ($resultEmail->num_rows !== 0) {
        $row = mysqli_fetch_assoc($resultEmail);
        $valid = password_verify($passwordLogin, $row['password']);
        if ($valid) {
            $_SESSION["logged-in"] = true;
            $_SESSION["id"] = $row['id'];
            $_SESSION["username"] = $row['username'];
            // if the email and password match: check admin status
            $isAdmin = $row['isAdmin'];
            if ($isAdmin == 1) {
                // Log in as admin
                $_SESSION["isAdmin"] = true;
                header("location: index.php");
            } else {
                // Log in as regular user
                $_SESSION["isAdmin"] = false;
                header("location: index.php");
            }
        } else {
            // Throw error: incorrect login
            $errorLogin = "De opgegeven gebruikersnaam of het opgegeven wachtwoord is onjuist.";
        }
    } else {
        // Throw error: incorrect login
        $errorLogin = "De opgegeven gebruikersnaam of het opgegeven wachtwoord is onjuist.";
    }
}
//endregion