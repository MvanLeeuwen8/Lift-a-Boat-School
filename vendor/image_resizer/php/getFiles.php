<?php
    if (!isset($_POST['dir'])) {
        die();
    }

    $dir = $_POST['dir']; 
    $scannedFiles = array_diff(scandir($dir), array('..', '.'));
    $files = [];
    foreach($scannedFiles as $file)
    {
        $fileData = [];
        $fileData['name'] = $file;
        if (is_file($dir . '/' . $file))
        {
            $fileData['type'] = "f";
        } 
        else 
        {
            $fileData['type'] = "d";
        }
        array_push($files, $fileData);
    }
    
    echo json_encode($files);

?>