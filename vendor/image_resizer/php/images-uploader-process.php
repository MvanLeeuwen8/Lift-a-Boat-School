<?php
	foreach($_FILES as $file) {
        if ($_GET['overwrite']) {
            move_uploaded_file($file['tmp_name'], "../../../bestemmingen/" . $_POST['destination'] . "/" . $file['name']);
        } else {
            if (file_exists("../../../bestemmingen/" . $_POST['destination'] . "/" . $file['name'])) {
                echo "3";
            } else {
                move_uploaded_file($file['tmp_name'], "../../../bestemmingen/" . $_POST['destination'] . "/" . $file['name']);
            }
        }
        echo $file['name'];
	}	
?>