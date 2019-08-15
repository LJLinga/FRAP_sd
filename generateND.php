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
    $this->Image('images/AFED Logo - Copy.jpg',10,10,20);
    // Arial bold 15
    $this->SetFont('Arial','B',10);
    // Move to the right
	$this->Cell(15);
	 $this->Cell(55,10,'AFED Inc.',0,0,'C');
	 $this->Ln(5);
	 $this->Cell(19);
	 $this->SetFont('Arial','',10);
	$this->Cell(55,10,'De La Salle University - Manila',0,0,'C');
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
	$this->Cell(0,5,'AFED INC.',0,1,'C');
	$this->Cell(0,5,'2401 Taft Avenue, Malate, Manila Philippines',0,1,'C');
	$this->Cell(0,5,'(632) 524-4611 Ext. 332',0,1,'C');
}


}
if(!isset($_SESSION['event_start'])){
   
        $query="SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type', l.per_payment as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
                    where $monthStart = Month(t.txn_date) AND $yearStart = Year(t.txn_date) && (t.txn_desc = 'Membership Application Approved'||t.txn_desc ='Loan has been Picked up! Deductions will start now.'||t.txn_type = '3')
                    
                    order by m.lastname,m.middlename,m.firstname";

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
    if(!isset($yearEnd)){
        $query = "SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type', l.per_payment as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
                    where $monthStart = Month(t.txn_date) AND $yearStart = Year(t.txn_date) && (t.txn_desc = 'Membership Application Approved'||t.txn_desc ='Loan has been Picked up! Deductions will start now.'||t.txn_type = '3')
                    
                    order by m.lastname,m.middlename,m.firstname;";
    }
    else{
        $query = "SELECT m.member_ID as 'ID', m.firstname as 'First',m.lastname as 'Last',m.middlename as 'Middle',t.txn_desc as 'Description',t.txn_type as 'Type',t.loan_ref as 'Ref',m.emp_type as 'Employee Type',l.PER_PAYMENT as 'Per Deduction'
        from member m
        join txn_reference t
        on t.member_id = m.member_id
        left join loans l
        on l.loan_id = t.loan_ref
                    where t.txn_date between '$yearStart-$monthStart-01 00:00:00' AND '$yearEnd-$monthEnd-31 23:59:59' && (t.txn_desc = 'Membership Application Approved'||t.txn_desc ='Loan has been Picked up! Deductions will start now.'||t.txn_type = '3')
        
                    order by m.lastname,m.middlename,m.firstname;";
    }

}
// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0	,5,"New Deductions"	,0,1,'C');
date_default_timezone_set('Singapore');
$pdf->SetFont('Times','',10);
$range = "For ".date('M', strtotime(substr($dateStart,strpos($dateStart," ")+1)))." ".$yearStart;
if(isset($yearEnd)){
    $range.=" - ".date('M', strtotime(substr($dateEnd,strpos($dateEnd," ")+1)))." ".$yearEnd;
}
$pdf->Cell(0    ,5,$range,0,1,'C');
$pdf->Cell(0	,5,"Generated by Melton at ".date("m/d/Y")." ".date("h:i:s a")	,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(15);
$pdf->Cell(20,5,''	,'L,T,R',0);
$pdf->Cell(50	,5,' '	,'L,T,R',0);
$pdf->Cell(30	,5,''	,'L,T,R',0);
$pdf->Cell(30   ,5,''   ,'L,T,R',0);
$pdf->Cell(35   ,5,''   ,'L,T,R',0);
$pdf->ln();
$pdf->Cell(15);
$pdf->Cell(20,5,'ID Number '	,'L,B,R',0,'C');
$pdf->Cell(50	,5,'Full Name'	,'L,B,R',0,'L');
$pdf->Cell(30	,5,'Loan Type'	,'L,B,R',0,'L');
$pdf->Cell(30   ,5,'Deduction Amount' ,'L,B,R',0,'R');
$pdf->Cell(35   ,5,'Deduction Frequency' ,'L,B,R',0,'L');

$pdf->ln();
$pdf->SetFont('Times','',10);
require_once('mysql_connect_FA.php');
$flag=0;

	
$result=mysqli_query($dbc,$query);
$total=0;

while($row=mysqli_fetch_assoc($result)){
$last = $row['Last'];
$first = $row['First'];
$middle = $row['Middle'];



if($row['Description']=='Membership Application Approved'){

$pdf->Cell(15);
$pdf->Cell(20,5,$row['ID']	,'L,B,R',0,'C');
$pdf->Cell(50	,5,"$last, $first $middle"	,'L,B,R',0,'L');
$pdf->Cell(30   ,5,"Membership",'L,B,R',0,'L');
if($row['Type']==1) 
    $cost = 183.00; 
else 
    $cost = 91.67;
$total+=$cost;
$pdf->Cell(30  ,5,number_format((float)$cost,2),'L,B,R',0,'R');
$pdf->Cell(35   ,5,"Per Term" ,'L,B,R',0,'L');
}


if($row['Description']=='Loan has been Picked up! Deductions will start now.'){
$pdf->Cell(15);
$pdf->Cell(20,5,$row['ID']  ,'L,B,R',0,'C');
$pdf->Cell(50   ,5,"$last, $first $middle"  ,'L,B,R',0,'L');
$pdf->Cell(30   ,5,"FALP",'L,B,R',0,'L');
$total+=(float)$row['Per Deduction'];
$pdf->Cell(30  ,5,number_format((float)$row['Per Deduction'],2),'L,B,R',0,'R');
$pdf->Cell(35   ,5,"Per Payday" ,'L,B,R',0,'L');
}

if($row['Type']=='3'){
$pdf->Cell(15);
$pdf->Cell(20,5,$row['ID']  ,'L,B,R',0,'C');
$pdf->Cell(50   ,5,"$last, $first $middle"  ,'L,B,R',0,'L');
$pdf->Cell(30   ,5,"FAP",'L,B,R',0,'L');
$total+=100;
$pdf->Cell(30  ,5,'100.00','L,B,R',0,'R');
$pdf->Cell(35   ,5,"Per Term" ,'L,B,R',0,'L');
}

$pdf->ln();




}
$pdf->Cell(15);
$pdf->Cell(20,5,''  ,'L,B,R',0);
$pdf->Cell(50   ,5,' '  ,'L,B,R',0);
$pdf->Cell(30   ,5,''   ,'L,B,R',0);
$pdf->Cell(30   ,5,number_format($total,2)   ,'L,B,R',0,'R');
$pdf->Cell(35   ,5,''   ,'L,B,R',0);
$pdf->ln();
$pdf->SetFont('Times','B',12);


$pdf->Cell(0	,5,"--END OF REPORT--"	,0,0,'C');
$pdf->Output();
?>