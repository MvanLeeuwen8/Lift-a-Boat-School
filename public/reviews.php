<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    include 'includes/database_connections/database_reviews.php';
    ?>
</head>

<body>
<div class="main-area">
    <div class="main-no-footer">

        <!-- navigation -->
        <?php include "includes/nav.php"; ?>

        <div class='left-sidebar' id="left-sidebar">
            <?php getSidebarDestinations("left"); ?>
        </div>

        <!-- main body -->
        <main class="reviews" id="main">
            <h2>Wat vinden onze klanten?</h2>

            <!-- Button leave review -->
            <button class="form-button" onClick="openForm()">Laat een review achter</button>

            <!-- Start popup-form -->
            <div class="popup-form">
                <form action="" class="form-review" id="popupForm" method="post">
                    <h2>Mijn review</h2>

                    <!-- naam & rating -->
                    <div class="reviewForm-section">

                        <!-- naam -->
                        <div class="review-naam">
                            <label for="name">Uw naam*</label>
                            <input type="text" name="name" required>
                        </div>

                        <!-- rating -->
                        <div class="review-rating">
                            <label for="rating">Rating*</label>
                            <select name="rating" id="rating" <!--form="reviewForm"--> required>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                    </div>

                    <!-- commentaar -->
                    <div class="reviewForm-section">
                        <label class="commentaarlabel" for="commentaar">Commentaar:</label>
                        <textarea name="commentaar" id="commentaar" rows="6" cols="auto"></textarea>
                    </div>

                    <!-- Submit & cancel buttons -->
                    <div class="reviewForm-section">
                        <input type="submit" value="Review opslaan" name="review">
                        <!-- closeForm(): config.js -->
                        <input type="button" value="Annuleren" onclick="closeForm()">
                    </div>

                <!-- end form -->
                </form>

                <div class="sentError" id="<?= $sentId ?>">
                    <p><?= $sentReview ?></p>
                </div>
            </div>

            <!-- show reviews from database -->
            <div class="old-reviews">
                <!-- getReviews is from includes/database_connections/database_reviews.php -->
                <?php
                getReviews();
                ?>
            </div>

        </main>

        <div class='right-sidebar' id="right-sidebar">
            <?php getSidebarDestinations("right"); ?>
        </div>

        <div class="bottom-sidebar-mobile" id="bottom-sidebar">
            <?php getSidebarDestinations("center"); ?>
        </div>

    </div>

    <!-- footer -->
    <?php include "includes/footer.php"; ?>
</div>

</body>
</html>