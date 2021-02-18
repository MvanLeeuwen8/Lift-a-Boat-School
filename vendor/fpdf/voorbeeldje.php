<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    error_reporting(1);
require '../CLASS/data.class.php';

require '../PDF/fpdf.php';

function getDateFromWeekNumber($day, $week, $year, $type = "input") 
{
	$dto = new DateTime();
	$dto->setISODate($year, $week);
	if($type == "input")
	{
	  $format = 'Y-m-d';
	}
	else
	{
	  $format = 'd-m-Y';

	}
	
	$ret['week_start'] = $dto->format($format);
	$dto->modify('+'.$day.' days');
	$ret['week_day'] = $dto->format($format);
  
  return $ret;
}

	
$data = new data();
$data->dataSelect('xlsx', null, null, 'comma');
	
$dateFrom = $_GET['from'];
$dateTo = $_GET['to'];



$allData = $data->draw;

	$data = array();
	$lines = explode('.',trim($allData));

	foreach($lines as $line)
	{
		$data[] = explode(',',trim($line));
	}
			
	$i = 0;
	
		$newData = array();
	foreach($data as $elements)
	{

		$thisDay = $elements[7];
		$thisWeek = $elements[1];
		$thisYear = $elements[8];
		
	
		
		$thisDate = getDateFromWeekNumber($thisDay, $thisWeek, $thisYear);
		
		$elements[0] = $elements[2];
		$elements[1] = $thisDate['week_day'];
		$elements[2] = $elements[3];
		$elements[3] = $elements[4];
		$elements[4] = $elements[5];
		$elements[5] = $elements[6];
		
		unset($elements[6]);
		unset($elements[7]);
		unset($elements[8]);
		unset($elements[9]);

		$findDate = date('Y-m-d');
		$findDate = date('Y-m-d', strtotime($thisDate['week_day']));
		
		$findBeginDate = date('Y-m-d', strtotime($dateFrom));
		$findEndDate = date('Y-m-d', strtotime($dateTo));
		
		
		
		if (($findDate >= $findBeginDate) && ($findDate <= $findEndDate)){
			//print
			
			
			$newData[$i] = $elements;
			$i++;
		}else{
			
		}
	
		
		
		
	}
	
	
class PDF extends FPDF
{
// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

// Better table
function ImprovedTable($header, $data)
{
    // Column widths
    $w = array(40, 35, 40, 45);
    // Header
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C');
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR');
        $this->Cell($w[1],6,$row[1],'LR');
        $this->Cell($w[2],6,number_format($row[2]),'LR',0,'R');
        $this->Cell($w[3],6,number_format($row[3]),'LR',0,'R');
        $this->Ln();
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}

// Colored table
function FancyTable($header, $data)
{
    // Colors, line width and bold font
    $this->SetFillColor(2,46,95);
    $this->SetTextColor(255);
    $this->SetDrawColor(255,255,255);
    $this->SetLineWidth(.1);
	 $this->SetFontSize(10);
    $this->SetFont('','B');
    // Header
    $w = array(15, 20, 40, 40, 15, 40);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'L',true);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFontSize(6);
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],6,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],6,$row[2],'LR',0,'L',$fill);
        $this->Cell($w[3],6,$row[3],'LR',0,'L',$fill);
        $this->Cell($w[4],6,$row[4],'LR',0,'L',$fill);
        $this->Cell($w[5],6,$row[5],'LR',0,'L',$fill);
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

$pdf = new PDF();
// Column headings
$header = array('SE nr', 'Datum', 'Van', 'Naar', 'Tijd', 'Uitvoerder');
$pdf->AddPage();

	
  // center the image horizontally
$pageWidth = $pdf->getPageWidth();
$imgWidth = 32;
$midX = ($pageWidth - $imgWidth) / 2;
		
$pdf->Image('../../img/sys_logo.png', $midX, 10, $imgWidth);

$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,80,'Planning');


	

$pdf->SetFont('Arial','',14);
$pdf->SetY(60);
$pdf->FancyTable($header,$newData);
//$pdf->Output();
	
	$timeStamp = date("Ymdhi");
	$pdf->Output($timeStamp .".pdf", "D");	  ?> 