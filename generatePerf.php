<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


require('fpdf/fpdf.php');




class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('images/AFED Logo - Copy.jpg',20,10,20);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Move to the right
	$this->Cell(25);
	 $this->Cell(55,10,'AFED Inc.',0,0,'C');
	 $this->Ln(5);
	 $this->Cell(29);
	 $this->SetFont('Arial','',10);
	$this->Cell(55,10,'De La Salle University - Manila',0,0,'C');
    $this->Cell(80);
    // Title
   
    $this->Ln(20);
}

// Page footer
function Footer()
{
     $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,5,'Page '.$this->PageNo().' of {nb}',0,0,'C');
	$this->ln();
	 $this->SetFont('Arial','',8);
	$this->Cell(0,5,'AFED INC.',0,1,'C');
	$this->Cell(0,5,'2401 Taft Avenue, Malate, Manila Philippines',0,1,'C');
	$this->Cell(0,5,'(632) 524-4611 Ext. 332',0,1,'C');
}
}
if(!isset($_SESSION['event_start'])){
   
      

}
else {
    
        $dateStart = $_SESSION['event_start'];
        $yearStart = substr($dateStart,0,strpos($dateStart," "));
            $monthStart = date('m', strtotime(substr($dateStart,strpos($dateStart," ")+1)));
            if(isset($_SESSION['event_end'])){
                $dateEnd = $_SESSION['event_end'];
                $yearEnd = substr($dateEnd,0,strpos($dateEnd," "));
                $monthEnd = date('m', strtotime(substr($dateEnd,strpos($dateEnd," ")+1)));
            }
        
    }
    
    
        
    

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0	,5,"Performance Report"	,0,1,'C');
$pdf->SetFont('Times','',10);
$range = "For ".date('M', strtotime(substr($dateStart,strpos($dateStart," ")+1)))." ".$yearStart;
if(isset($yearEnd)){
    $range.=" - ".date('M', strtotime(substr($dateEnd,strpos($dateEnd," ")+1)))." ".$yearEnd;
}
$pdf->Cell(0    ,5,$range,0,1,'C');
date_default_timezone_set('Singapore');
$pdf->Cell(0	,5,"Generated by Melton at ".date("m/d/Y")." ".date("h:i:sa")	,0,1,'C');
$pdf->ln();
$pdf->Cell(15);
$pdf->SetFont('Times','B',10);


$pdf->Cell(20,5,''	,'L,T,R',0,'C');
$pdf->Cell(40	,5,'New Applicants'	,'L,T,R,B',0,'R');
$pdf->Cell(50	,5,'Total Fees Collected'	,'L,T,R,B',0,'R');
$pdf->Cell(60	,5,'Total Amount Collected(P)'	,'L,T,R,B',0,'R');
$pdf->ln();
$pdf->SetFont('Times','',10);









$pdf->Cell(15);
$pdf->Cell(20,5,'Membership'	,1,0,'L');
$pdf->Cell(40	,5,$_SESSION['passMem'][0]	,1,0,'R');



$pdf->Cell(50	,5,$_SESSION['passMem'][1]	,'L,B,R',0,'R');
$pdf->Cell(60	,5,number_format($_SESSION['passMem'][2],2)	,1,0,'R');
$pdf->ln();

$pdf->Cell(15);
$pdf->Cell(20,5,'FAP'    ,1,0,'L');
$pdf->Cell(40   ,5,$_SESSION['passFALP'][0]  ,1,0,'R');



$pdf->Cell(50   ,5,$_SESSION['passFALP'][1]  ,'L,B,R',0,'R');
$pdf->Cell(60   ,5,number_format($_SESSION['passFALP'][2],2)  ,1,0,'R');
$pdf->ln();

$pdf->Cell(15);
$pdf->Cell(20,5,'Health Aid'    ,1,0,'L');
$pdf->Cell(40   ,5,$_SESSION['passHA'][0]  ,1,0,'R');



$pdf->Cell(50   ,5,$_SESSION['passHA'][1]  ,'L,B,R',0,'R');
$pdf->Cell(60   ,5,number_format($_SESSION['passHA'][2],2)  ,1,0,'R');
$pdf->ln();
$pdf->Cell(15);
$pdf->SetFont('Times','B',10);
$pdf->Cell(20,5,'Total'    ,1,0,'R');
$pdf->SetFont('Times','',10);
$pdf->Cell(40   ,5,$_SESSION['passMem'][0]+$_SESSION['passFALP'][0]+$_SESSION['passHA'][0]  ,1,0,'R');



$pdf->Cell(50   ,5,$_SESSION['passMem'][1]+$_SESSION['passFALP'][1]+$_SESSION['passHA'][1]  ,'L,B,R',0,'R');
$pdf->Cell(60   ,5,number_format($_SESSION['passMem'][2]+$_SESSION['passFALP'][2]+$_SESSION['passHA'][2],2)  ,1,0,'R');
$pdf->ln();

$pdf->ln();
$pdf->SetFont('Times','B',12);

$pdf->Cell(0	,5,"--END OF REPORT--"	,0,0,'C');
$pdf->Output();
?>