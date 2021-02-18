<!doctype html>
<html lang="en">
<head>
    <?php
    // include header
    require 'includes/head.php';
    ?>

    <!-- file uploader -->
    <?php include_once "../vendor/image_resizer/includes/config.php"; ?>

    <!-- Includes all js files -->
    <script src="../vendor/image_resizer/js/docready.js"></script>
    <script src="../vendor/image_resizer/vendor/file-uploader/js/file-uploader.js"></script>

    <!-- update-list -->
    <script src="js/update-list.js"></script>

    <!-- Include all CSS files -->
    <link rel="stylesheet" type="text/css" href="../vendor/file-uploader/css/file-uploader.css">
</head>

<body>
    <div class="main-area" id="bestemmingen">
        <div class="main-no-footer">

            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- main body -->
            <main class="bestemmingen" id="main">

                <!-- region modal -->
                    <div id="foto-modal" class="modal">
                        <div class="modal-content">
                            <span id="close"></span>
                            <img id="modal-image" src="">
                            <p id="img-text"></p>
                        </div>
                    </div>
                <!--endregion modal -->

                <?php
                // show button if user is an admin
                if($_SESSION["isAdmin"] === true ) {
                    echo "<button class='form-button' onclick='openForm()'>Bestemming toevoegen</button>";
                } ?>

                <!--region popup-form -->
                <div class="popup-form">
                    <form class="form-bestemmingen" id="popupForm" method="post" enctype="multipart/form-data">
                        <h2>Nieuwe bestemming</h2>

                        <!--region Basic information -->
                            <!-- Destination -->
                            <div class="bestemmingForm-section">
                                <div class="bestemming-plaats">
                                    <label for="bestemmingsplaats">Bestemming:*</label>
                                    <input type="text" name="bestemmingsplaats" required>
                                </div>
                            </div>

                            <!-- Departure date -->
                            <div class="bestemmingForm-section">
                                <div class="bestemming-datum">
                                    <label for="vertrekdatum">Vertrekdatum:</label>
                                    <input style="margin-bottom:0" type="date" name="vertrekdatum" min="<?= date("Y-m-d") ?>">
                                    <p style="color:red; margin-block-start: 0">Als er geen datum is ingevuld, wordt hij automatisch op "datum nog niet bekend"  gezet</p>
                                </div>
                            </div>
                        <!--endregion Basic information-->

                        <!--region Price -->
                        <span style="margin-top:20px">Prijzen:</span>
                        <p style="color:red; margin-block-start: 0; margin-block-end: 0">Als er geen prijs is ingevuld of als de prijs gelijk is aan 0, wordt deze gezet als "op aanvraag"</p>

                        <div class="bestemmingForm-section bestemming-price" style="margin-top:20px">
                            <!-- left part -->
                            <div class="bestemming-price-left">

                                <!-- 9-12m -->
                                <div class="bestemming-price-9-12">
                                    <label for="bestemming-price-9-12">Prijs 9-12m:</label>
                                    <input type="number" name="bestemming-price-9-12" min="0" step="0.01">
                                </div>
                                <!-- 13m -->
                                <div class="bestemming-price-13">
                                    <label for="bestemming-price-13">Prijs 13m:</label>
                                    <input type="number" name="bestemming-price-13" min="0" step="0.01">
                                </div>

                                <!-- 14m -->
                                <div class="bestemming-price-14">
                                    <label for="bestemming-price-14">Prijs 14m:</label>
                                    <input type="number" name="bestemming-price-14" min="0" step="0.01">
                                </div>

                                <!-- 15m -->
                                <div class="bestemming-price-15">
                                    <label for="bestemming-price-15">Prijs 15m:</label>
                                    <input type="number" name="bestemming-price-15" min="0" step="0.01">
                                </div>

                                <!-- 16m -->
                                <div class="bestemming-price-16">
                                    <label for="bestemming-price-16">Prijs 16m:</label>
                                    <input type="number" name="bestemming-price-16" min="0" step="0.01">
                                </div>

                            </div>

                            <!-- right part -->
                            <div class="bestemming-price-right">

                                <!-- 17m -->
                                <div class="bestemming-price-17">
                                    <label for="bestemming-price-17">Prijs 17m:</label>
                                    <input type="number" name="bestemming-price-17" min="0" step="0.01">
                                </div>

                                <!-- 18m -->
                                <div class="bestemming-price-18">
                                    <label for="bestemming-price-18">Prijs 18m:</label>
                                    <input type="number" name="bestemming-price-18" min="0" step="0.01">
                                </div>

                                <!-- 19m -->
                                <div class="bestemming-price-19">
                                    <label for="bestemming-price-19">Prijs 19m:</label>
                                    <input type="number" name="bestemming-price-19" min="0" step="0.01">
                                </div>

                                <!-- 20m -->
                                <div class="bestemming-price-20">
                                    <label for="bestemming-price-20">Prijs 20m:</label>
                                    <input type="number" name="bestemming-price-20" min="0" step="0.01">
                                </div>

                            </div>
                        </div>
                        <!--endregion Price -->

                        <!--region About destination -->
                            <!-- Destination information short -->
                            <div class="bestemmingForm-section">
                                <div class="bestemming-info-kort">
                                    <label for="bestemmingsinfo-kort">Korte beschrijving:*</label>
                                    <textarea name="bestemmingsinfo-kort" id="bestemmingsinfo_kort" rows="3" cols="auto" required></textarea>
                                </div>
                            </div>

                            <!-- Destination information long -->
                            <div class="bestemmingForm-section">
                                <div class="bestemming-info-lang">
                                    <label for="bestemmingsinfo-lang">Volledige beschrijving:*</label>
                                    <textarea name="bestemmingsinfo-lang" id="bestemmingsinfo_lang" rows="6" cols="auto" required></textarea>
                                </div>
                            </div>

                            <!-- Destination image upload -->
                            <div class="bestemmingForm-section">
                                <div class="bestemming-afbeelding">
                                    <label for="files">Afbeelding:*</label>
                                    <label class='upload-btn' for='files'>Bestanden kiezen</label>
                                    <input class="input-file" type="file" accept="image/jpg, image/png, image/jpeg, image/gif, image/pjpeg" onchange='updateList()' name="input-files[]" id = "files" multiple hidden required>
                                    <div id=fileList></div>
                                </div>
                            </div>
                        <!--endregion About destination -->

                        <!-- Submit and cancel buttons -->
                        <div class="bestemmingForm-section">
                            <div class="bestemming-submit-cancel">
                                <input type="submit" value="Toevoegen" name="bestemmingen">
                                <input type="button" value="Annuleren" onclick="closeForm()">
                            </div>
                        </div>

                    </form>
                </div>
                <!--endregion popup-form -->

                <?php
                    echo "<span class=error-message>" . $uploadError . "</span>";
                ?>

                <?php
                // getDestinations is from includes/database_connections/database_bestemmingen.php
                // this displays the destinations from the database
                getDestinations();
                ?>
            </main>

        </div>

    <!-- footer -->
    <?php include "includes/footer.php"; ?>

    </div>

</body>

<script>
    //script for file-uploader

    // Create a var newconfig to pass to the file uploader init function
    var newConfigFileUploader = <?php echo json_encode($CONFIG['file_uploader']); ?>;
    // Create a var newconfig to pass to the file uploader init function
    var newConfigImageResizer = <?php echo json_encode($CONFIG['file_resizer']); ?>;

    var errorDiv = document.getElementById('errorDiv');
    var folderDiv = document.getElementById('folderDiv');
    var fileDiv = document.getElementById('fileDiv');
    var currentDir = "";

    function handleImageErrors(errors) {
        errorDiv.innerHTML = "";
        for (item in errors){
            var itemParagraph = document.createElement("p");
            var itemText = document.createTextNode(item + ":");
            itemParagraph.style.color = "red";
            itemParagraph.appendChild(itemText);
            errorDiv.appendChild(itemParagraph);
            for (error in errors[item]) {
                var errorParagraph = document.createElement("p");
                var errorTxt = document.createTextNode(errors[item][error]['time'] + " - error_nr: " + errors[item][error]['error']);
                errorParagraph.appendChild(errorTxt);
                errorDiv.appendChild(errorParagraph);
            }
        }
    }

    function handleImageSuccess(image)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../vendor/image_resizer/vendor/file-resizer/php/file-resizer.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("action=file&fileName="+image);
    }

    function resizeFolder(image)
    {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../vendor/image_resizer/vendor/file-resizer/php/file-resizer.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("action=folder&folder="+newConfigImageResizer['source_folder']);
    }

    function getFiles(dir) {
        var xhttp = new XMLHttpRequest();
        xhttp.open("POST", "../vendor/image_resizer/php/getFiles.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xhttp.onreadystatechange = function(e) { // If we're done with the server script
            if (xhttp.readyState === 4) { // We finished the process and the resopnse is ready
                folderDiv.innerHTML = "";
                fileDiv.innerHTML = "";
                files = JSON.parse(xhttp.responseText);
                if (dir != "../images/") {
                    var a = document.createElement('a');
                    var itemParagraph = document.createElement("p");
                    var itemText = document.createTextNode("parent directory");
                    a.href = "javascript:changeDir('..')";
                    itemParagraph.appendChild(itemText);
                    a.appendChild(itemParagraph);
                    folderDiv.appendChild(a);
                }
                for (file in files) {
                    if (files[file]['type'] == "d") {
                        var a = document.createElement('a');
                        var itemParagraph = document.createElement("p");
                        var itemText = document.createTextNode(files[file]['name']);
                        a.href = "javascript:changeDir('" + files[file]['name'] + "')";
                        itemParagraph.appendChild(itemText);
                        a.appendChild(itemParagraph);
                        folderDiv.appendChild(a);
                    } else {
                        var a = document.createElement('a');
                        a.href = dir + '/' + files[file]['name'];
                        var img = document.createElement('img');
                        img.src = dir + '/' + files[file]['name'];
                        img.setAttribute('width', '400');
                        img.setAttribute('border', '1px');
                        a.appendChild(img)
                        fileDiv.appendChild(a);
                    }
                }
            }
        }

        xhttp.send("dir=" + dir);
    }

    function changeDir(dir) {
        if (dir == '..') {
            var splitDir = currentDir.split('/');
            currentDir = splitDir.slice(0, splitDir.length - 1).join("/");
        } else {
            currentDir += '/' + dir;
        }
        getFiles("../images/" + currentDir);
    }


   docReady(function() { // When the document is loaded and ready
        fileUploader.init("form-image-uploader", newConfigFileUploader); // Initialize the file uploader
        fileUploader.setErrorHandleFunct("handleImageErrors"); // Set the error handle function of the file uploader
        fileUploader.setSuccessHandleFunct("handleImageSuccess"); // Set the succes handle function of the file uploader for further process
        getFiles("../images/" + currentDir);
    })


    </html>
