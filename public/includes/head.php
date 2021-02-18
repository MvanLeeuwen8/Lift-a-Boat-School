<meta charset="UTF-8">
<meta name="viewport"
      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<!-- disable zoom on mobile -->
<script>
document.addEventListener('gesturestart', function (e) {
e.preventDefault();
});
</script>
<!-- favicon -->
<link rel="icon" type="image/png" href="assets/lab_staand_cirkel_180x180.png">

<?php
//start session
session_start();
if (!isset($_SESSION)) {
    $_SESSION["logged-in"] = false;
    $_SESSION["isAdmin"] = false;
}

// if logged in or admin isn't set, set them to false
if(!isset($_SESSION["logged-in"])) {
    $_SESSION["logged-in"] = false;
}
if (!isset($_SESSION["isAdmin"])) {
    $_SESSION["isAdmin"] = false;
}

include 'includes/config.php';
include 'config.php';
include 'includes/variables.php';
include 'includes/nav_urls.php';
include 'includes/database_connections/database_bestemmingen.php';

// file-uploader config
//include_once '../vendor/file-uploader/functions.php';
?>

<!-- file-uploader -->
<!--
<script src="/vendor/file-uploader/js/file-uploader.js"></script>
<script>
    var newConfigFileUploader = <?php echo json_encode($CONFIG['file_uploader']); ?>;
    fileUploader.init("form-image-uploader", newConfigFileUploader); // Initialize the file uploader
</script>
-->

<!-- Fontawesome & CSS -->
<script src="https://kit.fontawesome.com/388e1ac915.js" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="<?= url() . '/liftaboat-new/public/' ?>styles/main.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- Js files -->
<script src="js/forms.js" type="text/javascript"></script>

<!--reCaptcha -->
<script src="https://www.google.com/recaptcha/api.js"></script>

<!-- Dynamic title based on the page you're on -->
<title>
    <?php
    $path_parts = pathinfo(basename($_SERVER['SCRIPT_NAME']), PATHINFO_FILENAME);
    if ($path_parts == "index.php" || $path_parts == "index") {
        echo "Lift a Boat";
    } else {
        echo ucfirst($path_parts) . " - Lift a Boat";
    }
    ?>
</title>