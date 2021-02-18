<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    include "includes/database_connections/database_contact.php";

    if (!isset($captchaError)) {
        $captchaError = "";
    }
    if (!isset($sentQuestion)) {
        $sentQuestion = "";
    }
    if (!isset($sentId)) {
        $sentId = "";
    }

    if (!isset($_SESSION["useremail"])) {
        $_SESSION['useremail'] = "";
    }
    if (!isset($_SESSION["userquestion"])) {
        $_SESSION['userquestion'] = "";
    }
    ?>

</head>

<body>
<div class="main-area">
    <div class="main-no-footer">
        <!-- navigation -->
        <?php include "includes/nav.php"; ?>

        <!-- left sidebar -->
        <div class='left-sidebar' id="left-sidebar">
            <?php getSidebarDestinations("left") ?>
        </div>

        <!-- main body -->
        <main class="contact" id="main">

            <!--region contact-form -->
                <form name="contactForm" id="contactForm" method="post">
                    <p class="contact-intro">Mocht u meer informatie willen of bent u geïnteresseerd in een andere datum of bestemming, dan kunt u hieronder uw vraag stellen. Wij nemen dan zo spoedig mogelijk contact met u op.</p>

                    <!-- email -->
                    <div class="contact-email">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required value="<?=$_SESSION['useremail']?>">
                    </div>

                    <!-- vraag -->
                    <div class="contact-question">
                        <label for="vraag">Vraag:</label>
                        <textarea name="vraag" id="vraag" rows="8" cols="40" required><?=$_SESSION['userquestion']?></textarea>
                    </div>

                    <!-- self-made captcha -->
                    <div class="contact-captcha">
                        <label for="captcha">Captcha:</label>
                        <input type="text" id="captcha" name="captcha_challenge" pattern="^[a-zA-Z0-9]{6}$" autocomplete="off"><br>
                        <div class="captcha-img"><img src="includes/captcha.php" alt="CAPTCHA" class="captcha-image"><i class="fas fa-redo refresh-captcha" onclick="refreshButton()"></i></div>
                        <div><?=$captchaError?></div>
                    </div>

                    <!-- versturen -->
                    <input type="submit" value="Versturen" name="contact">

                    <div class="sentError" id="<?= $sentId ?>">
                        <p><?= $sentQuestion ?></p>
                    </div>

                    <!-- email, facebook and phone-links, links from includes/variables.php -->
                    <div class="contactlinks">
                        <a href="tel:<?= $tel; ?>" class="tel">Tel: <?= $tel;?></a>
                        <a href="mailto:<?= $mail; ?>" class="mail">E-mail: <?= $mail; ?></a>
                        <div class="socialmedia">
                            <a href="<?= $facebook;?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                        </div>
                    </div>

                    <!-- end form -->
                </form>
            <!--endregion contact-form -->
        </main>

        <!-- right sidebar -->
        <div class='right-sidebar' id="right-sidebar">
            <?php getSidebarDestinations("right") ?>
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

<script>
    refreshButton = function () {
        document.querySelector(".captcha-image").src = 'includes/captcha.php?' + Date.now();
    }
</script>
</html>