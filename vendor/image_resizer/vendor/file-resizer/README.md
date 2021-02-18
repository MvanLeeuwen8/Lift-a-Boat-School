--==[FILE-UPLOADER]==-

-Features

Resize a single file
Resize and loop a folder

-How to install/use

Copy the file-uploader folder and its content to the ./vendor/ folder.

The configuration file, existing in ./includes/cofig/php must contain the configuration (explained later).

Example for multiple files in folder and its sub folders. (This does not include the config format folders):
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../vendor/file-resizer/php/file-resizer.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=folder&folder="+newConfigImageResizer['source_folder']); 

Example for multiple files in folder and its sub folders. (This does not include the config format folders):
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "../vendor/file-resizer/php/file-resizer.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("action=file&fileName="+image); 		

The configuratino file must contain the following settings:
    $CONFIG['file_resizer'] =   [
        /*
            image_formats has 1 or many formats. They are provided with the target_folder.
            This target_folder is a subfolder in which the files are created. 
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
        
        'source_folder' => '../../../images', // The folder in which teh files are located
        'max_execution_time' => '300', // Maximum execution time in seconds
        'extensions_allowed' => array("pjpeg", "jpeg", "jpg", "gif", "png"), // Allowed extensions for uplaoding
    ]
    
    