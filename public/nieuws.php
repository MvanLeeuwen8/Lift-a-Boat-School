<!doctype html>
<html lang="en">
<head>

    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_nieuws.php';
    ?>

    <!-- update-list -->
    <script src="js/update-list.js"></script>

    <!-- Tiny editor -->
    <script src="https://cdn.tiny.cloud/1/2ppygj1ewlkg3tgte1ikcbuz3gpxzdn3cierwxjmjh88bepv/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector:'#news-textarea',
            plugins: 'image',
            menu: {
                file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
                edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
                view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
                insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
                tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
                table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
                help: { title: 'Help', items: 'help' }
            },
            toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | image',

            //region source: https://www.tiny.cloud/docs/demo/file-picker/
            /* enable title field in the Image dialog */
            image_title: true,

            /* enable automatic uploads of images represented by blob or data URIs */
            automatic_uploads: true,

            /*URL of our upload handler (for more details check:
            https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url) */
            images_upload_url: 'includes/postAcceptor_tiny.php',

            //here we add custom filepicker only to Image dialog
            file_picker_types: 'image',
            /* and here's our custom image picker*/
            file_picker_callback: function (cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');

                /*
                  Note: In modern browsers input[type="file"] is functional without
                  even adding it to the DOM, but that might not be the case in some older
                  or quirky browsers like IE, so you might want to add it to the DOM
                  just in case, and visually hide it. And do not forget do remove it
                  once you do not need it anymore.
                */

                input.onchange = function () {
                    var file = this.files[0];

                    var reader = new FileReader();
                    reader.onload = function () {
                        /*
                          Note: Now we need to register the blob in TinyMCEs image blob
                          registry. In the next release this part hopefully won't be
                          necessary, as we are looking to handle it internally.
                        */
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache =  tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);

                        /* call the callback and populate the Title field with the file name */
                        cb(blobInfo.blobUri(), { title: file.name });
                    };
                    reader.readAsDataURL(file);
                };

                input.click();
            }
            //endregion

        });
    </script>
</head>

<body>
    <div class="main-area">
        <div class="main-no-footer">

            <!-- navigation -->
            <?php include "includes/nav.php"; ?>

            <!-- left sidebar -->
            <div class='left-sidebar' id="left-sidebar">
                <?php getSidebarDestinations("left"); ?>
            </div>

            <!-- main body -->
            <main class="nieuws" id="main">

                <!-- region modal -->
                    <div id="foto-modal" class="modal">
                        <div class="modal-content">
                            <span id="close"></span>
                            <img id="modal-image" src="">
                            <p id="img-text"></p>
                        </div>
                    </div>
                <!--endregion modal -->

                <!-- if user is admin, show button to add a news article-->
                <?php
                    if ($_SESSION['isAdmin'] === true) {
                        echo "<button class='form-button' onclick='openForm()'>Nieuws toevoegen</button>";
                    }
                ?>

                <!--region popup-form -->
                <div class="popup-form">
                    <form class="form-nieuws" id="popupForm" method="post" enctype="multipart/form-data">
                        <h2>Nieuw nieuwsartikel</h2>

                        <!-- Title -->
                        <div class="news-form-section">
                            <div class="title">
                                <label for="title">Titel*:</label>
                                <input type="text" name="title" required>
                            </div>
                        </div>

                        <!-- News article -->
                        <div class="news-form-section">
                            <div class="text-section">
                                <label for="news-text">Tekst:</label>
                                <textarea name="news-text" id="news-textarea" cols="auto" rows="15"></textarea>
                            </div>
                        </div>

                        <!-- news image upload -->
                        <div class="news-form-section">
                            <div class="files-section">
                                <label for="files">Afbeelding:</label>
                                <label class='upload-btn' for='files'>Bestanden kiezen</label>
                                <input class="input-file" type="file" accept="image/jpg, image/png, image/jpeg, image/gif, image/pjpeg" onchange="updateList()" name="input-files[]" id = "files" multiple hidden>
                                <div id=fileList></div>
                            </div>
                        </div>

                        <!-- Submit and cancel buttons -->
                        <div class="news-form-section">
                            <div class="submit-cancel-section">
                                <input type="submit" value="Toevoegen" name="nieuws">
                                <input type="button" value="Annuleren" onclick="closeForm()">
                            </div>
                        </div>

                    </form>

                </div>
                <!--endregion popup form-->

                <!-- function to show news from database (found in includes/database_connections/database_nieuws.php) -->
                <?php
                   getNews();
                ?>

            </main>

            <!-- right sidebar -->
            <div class='right-sidebar' id="right-sidebar">
                <?php getSidebarDestinations("right"); ?>
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
</html>