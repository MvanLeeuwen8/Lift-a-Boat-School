<!doctype html>
<html lang="en">
<head>
    <?php
    require '../includes/head.php';
    ?>
</head>
<body>
<div class="main-area">
    <!-- navigation -->
    <?php include "../includes/nav.php"; ?>

    <!-- main body -->
    <main class="error" id="main">
        <h1>502</h1>
        <p>Bad gateway. Try again later or contact <a href="mailto:<?= $mail ?>"><?= $mail ?></a></p>
    </main>

    <!-- footer -->
    <?php include "../includes/footer.php"; ?>
</div>
</body>
</html>

<?php
