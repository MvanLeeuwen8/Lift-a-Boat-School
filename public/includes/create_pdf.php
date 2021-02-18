<?php

require '../../vendor/fpdf/fpdf.php';
if (!isset($_GET['departureplace'])) {
    $_GET['departureplace'] = "";
}

define('EURO',chr(128));

class PDF extends FPDF
{
    var $standardWidth = 60;
    var $standardHeight = 5;
    var $standardIndent = 15;

    //header
    function Header(){
        $this->SetFont('Arial', '', 10);

        //Logo
        $this->Image('../assets/logo.png', 26, 25, 70, '');

        //Create cell text: Lift a Boat, address and postal code + residence
        $this->Ln(15);
        $this->Cell(95);
        $this->MultiCell(120, 5.5, "LiftaBoat\nKadeweg 39\n3241SE Middelharnis");
        $this->Ln(0);
    }

    //Date and destination
    function DestinationDate($destination, $date){
        //Create cell text with date and destination
        $this->SetFont('Arial', '', 16);
        $this->Ln($this->standardHeight);
        //$this->Cell($this->standardIndent);
        $this->MultiCell(170,7,"Inschrijving " . $destination . " vertrek " . $date, 0, "L");
        $this->Ln(4);
    }

    //Address
    function Address($name, $street, $postal, $town) {
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(80,5, $name . "\n" . $street . "\n" . $postal . " " . $town, 0,"L");
        if ($_GET['business'] == "zakelijk") {
            $this->Ln(4);
            $this->Cell(85,-15);
        } else {
            $this->Ln(14);
        }
    }

    function Business($businessName, $businesskKvk, $businessBtw) {
        $this->Ln(-19);
        $this->Cell(80);
        $this->SetFont('Arial', '', 10);
        $this->MultiCell(85, 5, $businessName . "\n" . $businesskKvk . "\n" . $businessBtw, 0, "R" );
        $this->Ln(14);
    }

    //Thanks and confirmation text
    function ThankAndConfirmation(){
        $this->MultiCell(170,5,"Dank u wel voor uw inschrijving.\n\nHier is de bevestiging van uw inschrijving:",0,1);
        $this->Ln(10);
    }

    //region Table order-information
    function OrderInformation($orderNumber, $date, $destination, $price, $departurePlace = '') {
        //region Order number
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Ordernummer:");
        $this->Cell($this->standardWidth, $this->standardHeight, $orderNumber);
        $this->Ln();
        //endregion

        //region DepartureDate
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Vertrek datum:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $date);
        $this->Ln();
        //endregion

        //region Destination
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Bestemming:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $destination);
        $this->Ln();
        //endregion

        //region Place of departure
        if ($departurePlace != '') {
            $this->Cell($this->standardIndent);
            $this->Cell($this->standardWidth, $this->standardHeight, "Vertrek haven:" );
            $this->Cell($this->standardWidth, $this->standardHeight, $departurePlace);
            $this->Ln();
        }
        //endregion

        //region Price
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Prijs*:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $price);
        $this->Ln(10);
        //endregion
    }
    //endregion

    //region Table ship-information
    function ShipInformation($type, $brand, $length, $width, $depth, $minHeight, $draft, $weight, $material) {
        //region Ship type
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Soort schip:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $type);
        $this->Ln();
        //endregion

        //region Ship Brand
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Merk/Type:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $brand);
        $this->Ln();
        //endregion

        //region Ship Length
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Lengte:" );
        $this->Cell($this->standardWidth, $this->standardHeight, str_replace(".", "," , $length) . " meter");
        $this->Ln();
        //endregion

        //region Ship width
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Breedte:" );
        $this->Cell($this->standardWidth, $this->standardHeight, str_replace(".", "," , $width) . " meter");
        $this->Ln();
        //endregion

        //region Ship depth
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Diepgang:" );
        $this->Cell($this->standardWidth, $this->standardHeight, str_replace(".", "," , $depth) . " meter");
        $this->Ln();
        //endregion

        //region Ship minimal height above water
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Minimale hoogte boven water:" );
        $this->Cell($this->standardWidth, $this->standardHeight, str_replace(".", "," , $minHeight) . " meter");
        $this->Ln();
        //endregion

        //region Ship draft
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Type kiel:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $draft);
        $this->Ln();
        //endregion

        //region Ship weight
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Gewicht:" );
        $this->Cell($this->standardWidth, $this->standardHeight, str_replace(",", "." , number_format($weight * 1000)) . " Kg");
        $this->Ln();
        //endregion

        //region Ship hull material
        $this->Cell($this->standardIndent);
        $this->Cell($this->standardWidth, $this->standardHeight, "Bouwmateriaal romp:" );
        $this->Cell($this->standardWidth, $this->standardHeight, $material);
        $this->Ln(10);
        //endregion
    }
    //endregion

    //Taxes
    function Taxes(){
        $this->Cell(170,5,"* Al onze prijzen zijn inclusief belastingen en toeslagen.",0,1);
        $this->Ln();
    }

    //Information text
    function Information($orderNumber){
        $this->MultiCell(170,5,"U bent zelf verantwoordelijk voor de juistheid van de door u opgegeven informatie. Mochten bovenstaande gegevens niet kloppen, neem dan contact op met info@liftaboat.nl onder vermelding van het ordernummer " . $orderNumber . '.',0,1);
        $this->Ln();
        $this->MultiCell(170,5,"U vindt onze algemene voorwaarden op onze website: https://www.liftaboat.nl/algemenevoorwaarden.php",0,1);
        $this->Ln(10);
    }

    function Regards(){
        $this->Cell(170,5,"Met vriendelijke groet,",0,1);
        $this->Ln();
        $this->Cell(170,5,"Lift a Boat",0,1);
    }
}

//region Editing of received values

    // Add leading zeros before the id, to make them all 6 digits
    $idWithZeros = sprintf('%06d', $_GET['id']);

    //region date
        // 01-01-2020 format for the large text just below the header
        $DMY = date("d-m-Y",strtotime($_GET['date']));

        // 01 januari 2020 format for the text in the table

        //Get month
        $month = date('n', strtotime($_GET['date']));
        $fullMonth = "";

            //region Dutch months
            if ($month == 1) {
                $fullMonth = "januari";
            } else if ($month == 2) {
                $fullMonth = "februari";
            } else if ($month == 3) {
                $fullMonth = "maart";
            } else if ($month == 4) {
                $fullMonth = "april";
            } else if ($month == 5) {
                $fullMonth = "mei";
            } else if ($month == 6) {
                $fullMonth = "juni";
            } else if ($month == 7) {
                $fullMonth = "juli";
            } else if ($month == 8) {
                $fullMonth = "augusutus";
            } else if ($month == 9) {
                $fullMonth = "september";
            } else if ($month == 10) {
                $fullMonth = "oktober";
            } else if ($month == 11) {
                $fullMonth = "november";
            } else if ($month == 12) {
                $fullMonth = "december";
            }
            //endregion

        $day = date("d", strtotime($_GET['date']));
        $year = date("Y", strtotime($_GET['date']));

        $fullDate = $day . " " . $fullMonth . " " . $year;
    //endregion

    //region Change %20 back into spaces
        //destination
        $destDec = str_replace("%20", " ", $_GET['destination']);

        //port
        $portDec = str_replace("%20", " ", $_GET['departureplace']);

        //type
        $typeDec = str_replace("%20", " ", $_GET['type']);

        //brand
        $brandDec = str_replace("%20", " ", $_GET['brand']);

        //draft
        $draftDec = str_replace("%20", " ", $_GET['draft']);

        //materials
        $matsDec = str_replace("%20", " ", $_GET['material']);

        //name
        $nameDec = str_replace("%20", " ", $_GET['name']);

        //street
        $streetDec = str_replace("%20", " ", $_GET['street']);

        //postal code
        $postalDec = str_replace("%20", " ", $_GET['postal']);

        //residence
        $residenceDec = str_replace("%20", " ", $_GET['residence']);

        //price
        $priceDec = str_replace("%20", " ", $_GET['price']);

        //business
        $businessNameDec = str_replace("%20", " ", $_GET['businessname']);
    //endregion

    // If the price isn't "Op aanvraag", add a € before the price:
    if($priceDec != "Op aanvraag") {
        $priceDec = EURO . " " . str_replace(".", ",", $priceDec);
    }

//endregion


//Creation of pdf
$pdf = new PDF('P', 'mm', 'A4');
$pdf->AddPage();

//Set standard font
$pdf->SetFont('Arial', '', 10);
$pdf->SetMargins(25, 23);

//content, header is called automatically at the start of each page

// Destination and date just below the header
$pdf->DestinationDate($destDec, $DMY);

// The address block
$pdf->Address($nameDec, $streetDec, $postalDec, $residenceDec);

//business block if they are a business
if ($_GET['business'] == "zakelijk") {
    $pdf->Business($businessNameDec, $_GET['businesskvk'], $_GET['businessbtw']);
}

$pdf->ThankAndConfirmation();

//region order-table
if (isset($_GET['departureplace'])) {
    $pdf->OrderInformation($idWithZeros, $fullDate, $destDec, $priceDec, $portDec);
} else {
    $pdf->OrderInformation($idWithZeros, $fullDate, $destDec, $priceDec);
}
//endregion

//ship-table
$pdf->ShipInformation($typeDec, $brandDec, $_GET['length'], $_GET['width'], $_GET['depth'], $_GET['minheight'], $draftDec, $_GET['weight'], $matsDec);

$pdf->Taxes();
$pdf->Information($idWithZeros);
$pdf->Regards();

//pdf name
$timestamp = date("Ymdhi");
$pdf->Output('F', "../../invoices/" . $idWithZeros . "-" . strtolower($_GET['destination'] . "-" . $timestamp . ".pdf"));

header("Location: send_email.php?id=" . $_GET['id'] . "&pdfname=" . $idWithZeros . "-" . strtolower($_GET['destination'] . "-" . $timestamp . ".pdf"));

//header("Location: send_email.php?email=" . $_GET['appemail'] . "&name=" . $_GET['name'] . "&order=" . $idWithZeros . "&destination=" . $_GET['destination'] . "&date=" . $_GET['date'] . "&pdfname=" . $idWithZeros . "-" . strtolower($_GET['destination'] . "-" . $timestamp . ".pdf"));
?>