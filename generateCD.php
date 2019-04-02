<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


require('fpdf/fpdf.php');

class PDF extends FPDF
{
	var $row = 0;
// Page header
function Header()
{
    // Logo
    $this->Image('FA Logo.jpg',0,10,20);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Move to the right
	$this->Cell(15);
	 $this->Cell(80,10,'Faculty Association,Inc.',0,0,'C');
	 $this->Ln(5);
	 $this->Cell(19);
	 $this->SetFont('Arial','',10);
	$this->Cell(80,10,'De La Salle University - Manila',0,0,'C');
    $this->Cell(80);
    // Title
   
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,5,'Page '.$this->PageNo().' of {nb}',0,0,'C');
	$this->ln();
	 $this->SetFont('Arial','',8);
	$this->Cell(0,5,'FACULTY ASSOCIATION',0,1,'C');
	$this->Cell(0,5,'2401 Taft Avenue, Malate, Manila Philippines',0,1,'C');
	$this->Cell(0,5,'(632) 524-4611 Ext. 332',0,1,'C');
}


}
if(!isset($_SESSION['event_start'])){
   
        $query="SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
join (SELECT max(date_matured) as 'Date' from loans) latest
        where  l.LOAN_STATUS = 3 AND date(latest.Date) = date(l.date_matured)
        group by m.member_ID";

}
else {
   $dateStart = $_SESSION['event_start'];
            
            $yearStart = substr($dateStart,0,strpos($dateStart,"-"));
            $monthStart = substr($dateStart,strpos($dateStart,"-")+1);
            if(!empty($_SESSION['event_end'])){
                $dateEnd = $_SESSION['event_end'];
                $yearEnd = substr($dateEnd,0,strpos($dateEnd,"-"));
                $monthEnd = substr($dateEnd,strpos($dateEnd,"-")+1);
            }
    if(!isset($yearEnd)){
        $query = "SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
                    where  l.LOAN_STATUS = 3 AND $monthStart = Month(l.date_matured) AND $yearStart = Year(l.date_matured) 
                    group by l.loan_id";
    }
    else{
        $query = "SELECT m.member_id as 'ID',m.firstName as 'First',m.middlename as 'Middle', m.lastname as 'Last',l.LOAN_ID
from loans l  

join member m
on l.member_id = m.member_id
                    where l.LOAN_STATUS = 3 AND (l.date_matured between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59') 
                    group by l.loan_id ";
    }

}
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0	,5,"Completed Deductions"	,0,1,'C');
date_default_timezone_set('Singapore');
$pdf->SetFont('Times','',10);
$range = "For ".date('F Y', mktime(0, 0, 0, $monthStart+1, 0,$yearStart));
if(isset($yearEnd)){
    $range.="-".date('F Y', mktime(0, 0, 0, $monthEnd+1, 0,$yearEnd));
}
$pdf->Cell(0    ,5,$range,0,1,'C');
$pdf->Cell(0	,5,"Generated by Melton at ".date("m/d/Y")." ".date("h:i:sa")	,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(45);
$pdf->Cell(20,5,''	,'L,T,R',0);
$pdf->Cell(50	,5,' '	,'L,T,R',0);
$pdf->Cell(30	,5,''	,'L,T,R',0);
$pdf->ln();
$pdf->Cell(45);
$pdf->Cell(20,5,'ID Number '	,'L,B,R',0,'C');
$pdf->Cell(50	,5,'Full Name'	,'L,B,R',0,'L');
$pdf->Cell(30	,5,'Loan Completed'	,'L,B,R',0,'C');
$pdf->ln();
$pdf->SetFont('Times','',10);

	
$result=mysqli_query($dbc,$query);


while($row=mysqli_fetch_assoc($result)){
$last = $row['Last'];
$first = $row['First'];
$middle = $row['Middle'];





$pdf->Cell(45);
$pdf->Cell(20,5,$row['ID']	,'L,B,R',0,'C');
$pdf->Cell(50	,5,"$last, $first $middle"	,'L,B,R',0,'L');

    $pdf->Cell(30   ,5,"FALP Loan"   ,'L,B,R',0,'C');




$total= 0.00;	

$pdf->ln();



}

$pdf->SetFont('Times','B',12);


$pdf->Cell(0	,5,"--END OF REPORT--"	,0,0,'C');
$pdf->Output();
?>