<?php
    // app settings (zws01)
    $CONFIG = [];
    $CONFIG["file_uploader"] =  [   'target_folder'         => "../../uploads", // The folder relative to programm_root
                                    'max_file_size'         => "4768371", // Maximum file size (5 MB)
                                    'overwrite'             => true, // Overwrite the files 
                                    'extensions_allowed'    => array("pjpeg", "jpeg", "jpg", "gif", "png"), // Allowed extensions for uploading
                                ];
    
    $CONFIG['file_resizer'] =   [
                                 /*
                                    image_formats has 1 or many formats. They are provided with the target_folder.
                                    This target_folder is a subfolder in the $CONFIG['file_resizer']['target_folder']
                                    as set below in this config.
                                    width and height need to be set for each format. 
                                    So in this case we have 5 formats all set to different target folders and size.
                                 */
                                 'image_formats' => [ 
                                    'thumbnails' => [ // Thumbnails need to be created 
                                        'target_folder' => 'thumbs', // Its targed folder for the thumbmails
                                        'width'			=> 64, // The width size of the tumbsnail 
                                        'height'		=> 64 // The height size of the tumbsnail 
                                    ],
                                    'big_ratio_short' => [ // Setup the same but then for the medium copies
                                        'target_folder' => 'big_ratio_short',
                                        'width'			=> 1000,			
                                        'height'		=> 800
                                    ],
                                    'big_ratio_long' => [ // Setup the same but then for the medium copies
                                        'target_folder' => 'big_ratio_long',
                                        'width'			=> 2400,			
                                        'height'		=> 800
                                    ],
                                    'small_ratio_short' => [ // Setup the same but then for the medium copies
                                        'target_folder' => 'small_ratio_short',
                                        'width'			=> 800,			
                                        'height'		=> 1000
                                    ],
                                    'small_ratio_long' => [ // Setup the same but then for the medium copies
                                        'target_folder' => 'small_ratio_long',
                                        'width'			=> 800,			
                                        'height'		=> 2400
                                    ]
                                ],
                                'source_folder' => '../../../images',
                                'target_folder' => '../../../images',
                                'max_execution_time' => '300', // Maximum execution time in seconds
                                "extensions_allowed" => array("pjpeg", "jpeg", "jpg", "gif", "png"), // Allowed extensions for uplaoding                                
                                
                                ];
                        
                        
    // if this file is being run as a script, redirect to root
    if (__FILE__ == $_SERVER["SCRIPT_FILENAME"]) {
        // redirect to root
        header("location: ./");
    }
?>
