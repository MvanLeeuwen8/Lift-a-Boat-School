<!doctype html>
<html lang="en">
<head>
    <?php
    require 'includes/head.php';
    ?>
</head>

<body>
<div class="main-area">

    <div class="main-no-footer">

        <!-- navigation -->
        <?php include "includes/nav.php"; ?>

        <!-- left sidebar -->
        <div class='left-sidebar' id="left-sidebar">
            <?php getSidebarDestinations("left") ?>
        </div>

        <!-- main body -->
        <main class="buitenland" id="main">

            <!-- region Vaarregels en voorschriften Duitsland -->
            <div class="full">
                <h2 class="small-vaarregels" style="margin-block-start:0">Vaarregels en voorschriften Duitsland</h2>
                <div class="half">
                    <h2 class="large-vaarregels" style="margin-block-start: 0">Vaarregels en voorschriften Duitsland</h2>
                    <p>Duitsland is voor de watersport een prachtig land, met een groot kanalenstelsel kunt u eigenlijk vrijwel overal komen. Voordat u vertrekt naar Duitsland dient u een aantal zaken te regelen. In tegenstelling tot Nederland waar nauwelijks gecontroleerd wordt op papieren is de controle in Duitsland een stuk strenger.</p>
                    <a class=btn href="http://www.watersportalmanak.nl/artikel/voorschriften-duitsland" target="_blank"><button>Meer informatie</button></a>
                </div>

                <div class="half right">
                    <img src="assets/vaarregels-voorschriften-duitsland.jpg">
                </div>

            </div>
            <!-- endregion -->

            <!-- region Rivier de Rijn in Duitsland en Frankrijk -->
            <div class="full">

                <h2 class="small-rijn" style="margin-block-start: 0">Rivier de Rijn in Duitsland en Frankrijk</h2>
                <div class="width-3-4 right">
                    <h2 class="large-rijn" style="margin-block-start: 0">Rivier de Rijn in Duitsland en Frankrijk</h2>
                    <p>De rivier de <b>Rijn</b> (Duits: <i>Rhein</i>, Frans: <i>Rhin</i>) is met 1233 kilometer een van de langste <a href="https://nl.wikipedia.org/wiki/Rivier" target="_blank">rivieren</a> van Europa. 800 kilometer ervan ligt in Duitsland, waar ook het grootste deel van het stroomgebied ligt, ongeveer 200 kilometer ligt in Zwitserland en 160 in Nederland.Tussen Bazel en Breisach am Rhein is de rivier niet bevaarbaar, maar ten westen van de Rijn, op Frans grondgebied, is het Grand Canal d'Alsace (de “nieuwe Rijn”) parallel aan de Rijn (de “oude Rijn”) gegraven. Daartussen ligt een eiland dat ruim 50 km lang is. Het deel van de rivier vanaf de Rijnknie in het centrum van Bazel tot aan Bingen wordt de Oberrhein (BovenRijn) genoemd. De Rijn vormt hier de Duits-Franse grens.<br>(© Wikipedia)</p>
                    <a class="btn" href="assets/rivier%20de%20Rijn%20in%20Duitsland%20en%20Frankrijk%20©Wikipedia.pdf" target="_blank"><button>Download de PDF</button></a>
                </div>

                <div class="quarter">
                    <img src="assets/kaart-rijn.jpg">
                </div>
            </div>
            <!--endregion-->

            <!-- region Met een klein schip op de Rijn -->
            <div class="full">

                <h2 class="small-schip" style="margin-block-start: 0">Met een klein schip op de Rijn</h2>
                <div class="width-2-3">
                    <h2 class="large-schip" style="margin-block-start: 0">Met een klein schip op de Rijn</h2>
                    <p>Dit document is bedoeld om de recreatie schipper wat suggesties aan te reiken voor het varen op de Rijn tussen Spijksche veer en Mannheim, in de opvaart of in de afvaart. Het is zeker niet geschikt voor de beroeps binnenschipper. Alles wat hier besproken wordt is een weergave van mijn eigen mening, een ander zal daar waarschijnlijk anders over denken. Doe ermee wat je wilt.<br>Béthune, France 08-05-2020<br><b>© Roeland van Basten Batenburg</b></p>
                    <a class="btn" href="assets/met-een-klein-schip-op-de-rijn.pdf" target="_blank"><button>Download de PDF</button></a>
                </div>

                <div class="third right">
                    <img src="assets/met-een-klein-schip-op-de-rijn.png">
                </div>

            </div>
            <!-- endregion -->

            <!--region ICP - eigendomsbewijs -->
            <div class="full">

                <h2 class="small-icp" style="margin-block-start: 0">ICP - eigendomsbewijs</h2>
                <div class="width-2-3 right">
                    <h2 class="large-icp" style="margin-block-start: 0">ICP - eigendomsbewijs</h2>
                    <p>Internationaal eigendomsbewijs van uw boot (ICP)<br>In Nederland is het niet verplicht, maar in het buitenland dient u in het bezit te zijn van het eigendomsbewijs van uw boot. Het ICP is een officieus eigendomsbewijs hiervoor dat in de praktijk uitstekend voldoet. In vrijwel heel Europa wordt het document geaccepteerd als identiteitsbewijs van de boot. Dit geldt ook voor kano’s, roeiboten, zeilplanken en jetski's.</p>

                    <a class="btn" href="http://www.watersportalmanak.nl/artikel/icp-watersport" target="_blank"><button>Meer informatie</button></a>
                </div>

                <div class="third">
                    <img src="assets/icp-eigendomsbewijs-schip.jpg">
                </div>

            </div>
            <!--endregion -->

        </main>

        <!-- right sidebar -->
        <div class='right-sidebar' id="right-sidebar">
            <?php getSidebarDestinations("right") ?>
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