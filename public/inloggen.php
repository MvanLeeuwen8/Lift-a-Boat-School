<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_login.php';
    ?>
</head>

<body>
    <div class="main-area" id="inloggen">
        <div class="main-no-footer">
            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- main body -->
            <main class="login" id="main">

                <?php if ($_SESSION['logged-in'] === true) {
                    echo "<h2>U bent al ingelogd.</h2>";
                    echo "<a class=logout href='uitloggen.php'><button>Uitloggen</button></a>";
                } else {
                    ?>
                <!--region login-form -->
                <form class="login-form" method="post">

                    <!-- username -->
                    <div class="username">
                        <label for="usernameLogin">Naam of email:</label>
                        <input type="text" name="usernameLogin" required>
                    </div>

                    <!-- Password -->
                    <div class="password">
                        <label for="passwordLogin">Wachtwoord:</label>
                        <input type="password" name="passwordLogin" required>
                    </div>

                    <!-- Submit-button -->
                    <div class="login-submit">
                        <input type="submit" value="Inloggen" name="login">
                    </div>

                    <div class="login-error"><span><?= $errorLogin ?></span></div>

                </form>
                <!--endregion login-form -->
                <?php
                }
             ?>

            </main>
        </div>

        <!-- footer -->
        <?php include "includes/footer.php"; ?>

    </div>
</body>
</html>
<?php
