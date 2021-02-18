<?php
    
	include_once "../../../includes/config.php"; // Include our config file
    ini_set('max_execution_time', $CONFIG['file_resizer']['max_execution_time']);

    if (!isset($_POST['action'])) { // No action specified 
        // Return a error message
        die();
    } 
    
    if ($_POST['action'] == "file") { // If the action provided is "file"
        $file = $_POST['fileName']; // We store the $_POST filename into $file
        foreach($CONFIG['file_resizer']['image_formats'] as $format) { // If so then loop for each format in the config file 
            resizeImage($file, $format, $CONFIG['file_resizer']['source_folder']); // to resize the image and store it as a new one
        }
    } 
    if ($_POST['action'] == "folder") {
        $folder = $_POST['folder'];
        loopFolder($folder);
    }
    
	function loopFolder($folder) {
		GLOBAL $CONFIG;
		$image_formats = $CONFIG['file_resizer']['image_formats']; // Use the global formats form the config file
		
		$diffDir = []; // Creat a varaiable for storing the format folders
		foreach ($image_formats as $format) { // Loop the global format 
			array_push($diffDir,  $format['target_folder']); // Push the target folder into diffDir 
		}
		array_push($diffDir, '..'); // And add '..'
		array_push($diffDir, '.'); // And add '.'
		$original_files = array_diff(scandir($folder, 1), $diffDir); // Get all the files and folders in the target folder withoud the '..' and '.' and the format folders
        
		foreach ($original_files  as $file) { 
			if (!is_dir($folder . '/' . $file)) { // If the file is not a folder             
				$file_name_parts = explode('.', $file); // Split the name into two parts, the part in front of the dot and behind it
				$file_extension = strtolower(end($file_name_parts)); // Store the extension of the file name into a variable
		
				if (in_array($file_extension, $CONFIG['file_resizer']['extensions_allowed']) === true) {// See if the file extension is in the available extention list
					foreach($image_formats as $format) { // If so then loop for each format in the config file 
						resizeImage($file, $format, $folder); // to resize the image and store it as a new one
					}
				} 
			}
			else { // If the fils is a folder,
				loopFolder($folder .'/' . $file); // loop this folder too
			}
		}
	}
		
    /*
        Resize the a image
        The new image extension is always changed to .png
               
        file    : Name of the file we need to resize
        format  : Format to change the iamge too
        folder  : Folder of the location of the file
    */
    
	function resizeImage($file, $format, $folder) {
        GLOBAL $CONFIG; // Use the GLOBAl config
		$orgFile = $file; // Original file name is stored into orgFile
		$mime = getimagesize($folder . "/" . $file); // Get the mime data of the file
		$source_width = $mime[0]; // Get the image width and store it into source_width
		$source_height = $mime[1]; // Get the image height and store it into source_height
		
		$source_ratio = $source_width / $source_height; // Calculate the ratio of the image
		$new_ratio = $format['width'] / $format['height']; // Calculate the new image ratio
		
		
		$offset_x = 0; // offset_x is set to 0
		$offset_y = 0; // offset_y is set to 0
		$new_width = $format['width']; // Sore the target image width to new_width
		$new_height = $format['height']; // Sore the target image height to new_height
		
		
		if ($source_ratio >=1) {
			$new_height = $format['height'] / $source_ratio;
			$offset_y = ($format['height'] - $new_height) / 2;			
		} else {
			$new_width = $format['width'] * $source_ratio;
			$offset_x = ($format['width'] - $new_width) / 2;			
		}
		
		// Image width >= height?
		if ($source_ratio >= 1) {
			if ($new_ratio >= 1) {
				if ($source_ratio >= $new_ratio) {
					$new_height = $format['width'] / $source_ratio;
					$offset_y = ($format['height'] - $new_height) / 2;
					$new_width = $format['width'];
					$offset_x = 0;
				} else {
					$new_width = $format['height'] * $source_ratio;
					$offset_x = ($format['width'] - $new_width) / 2;
					$new_height = $format['height'];
					$offset_y = 0;
				}
			} else {
                $new_height = $format['width'] / $source_ratio;
				$offset_y = ($format['height'] - $new_height) / 2;
				$new_width = $format['width'];
				$offset_x = 0;
			
			}
		} else {
			if ($new_ratio >= 1) {
				$new_width = $format['height'] * $source_ratio;
				$offset_x = ($format['width'] - $new_width) / 2;
				$new_height = $format['height'];
				$offset_y = 0;
			} else {
				if ($source_ratio >= $new_ratio) {
					$new_height = $format['width'] / $source_ratio;
					$offset_y = ($format['height'] - $new_height) / 2;
					$new_width = $format['width'];
					$offset_x = 0;
				} else {
					$new_width = $format['height'] * $source_ratio;
					$offset_x = ($format['width'] - $new_width) / 2;
					$new_height = $format['height'];
					$offset_y = 0;
				}
			}
		}
		
	
		if($mime['mime']=='image/png') { 
			$source_image = imagecreatefrompng($folder. '/' . $file);
		}	
		
		if($mime['mime']=='image/jpg' || $mime['mime']=='image/jpeg' || $mime['mime']=='image/pjpeg') {
			$source_image = imagecreatefromjpeg($folder. '/' . $file);
		} 
		
		if($mime['mime']=='image/gif') {
			$source_image = imagecreatefromgif($folder. '/' . $file);
		}		

		$destination_image = ImageCreateTrueColor($format['width'], $format['height']);
		
		imagealphablending($destination_image, false);
		
		imagesavealpha($destination_image, true);
		
		$transparent = imagecolorallocatealpha($destination_image, 255, 255, 255, 127);
		
		imagefilledrectangle($destination_image, 0, 0, $format['width'], $format['height'], $transparent);
	
		imagecopyresampled($destination_image, $source_image, $offset_x, $offset_y, 0, 0, $new_width, $new_height, $source_width, $source_height );	
	
		$file_name_parts = explode('.', $file); // Split the name into two parts, the part in front of the dot and behind it
		// $file_extension = strtolower(end($file_name_parts)); // Store the extension of the file name into a variable
		$file = $file_name_parts[0] . '.png'; // User the name part [0] which is the name of the file, and add the .png extension
		$destination_directory = $folder . '/' . $format['target_folder'];
		createDirectory($destination_directory);
		imagepng($destination_image, $destination_directory .  '/' .  $file, -1);
		// echo "<img src='" . $folder . "/" . $orgFile . "' width='100'>";
		// echo "<img src='" . $destination_directory . "/" . $file . "' width='100' style='border:1px solid #f00'>"
	}
	
	function createDirectory($dir) {
		if (!is_dir($dir)) {
			if (!mkdir($dir, 0755, true)) {
				return false;
			}			
		} 
		return true;
	}
?>