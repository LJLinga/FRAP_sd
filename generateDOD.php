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
   
        $query2="SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 1 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 2 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest where SERVICE_ID = 3 AND DATE(TXN_DATE) = DATE(latest.Date) group by member_id) f
on m.MEMBER_ID = f.member_id
join txn_reference t
on t.member_id = m.member_id
join (SELECT max(txn_date) as 'Date' from txn_reference where txn_type = 2) latest
where DATE(latest.Date) = date(TXN_DATE) group by m.member_ID";

}
else {
         $date = $_SESSION['event_start'];
        $dayStart = substr($date,0,strpos($date,"-"));
        $monthStart = substr($date,(strpos($date,"-")+1),strpos($date,"- ")-3);
        $yearStart = substr($date,strpos($date,"- ")+1);
            if(!empty($_SESSION['event_end'])){
                $date = $_SESSION['event_end'];

                $dayEnd = substr($date,0,strpos($date,"-"));
                $monthEnd = substr($date,(strpos($date,"-")+1),strpos($date,"- ")-3);
                $yearEnd = substr($date,strpos($date,"- ")+1);
            }
        if(!isset($yearEnd)){
        $query2 = "SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 1 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2 group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 2 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2 group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 4 AND $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date) AND txn_type = 2  group by member_id) f
on m.MEMBER_ID = f.member_id

join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and $monthStart = Month(txn_date) AND $yearStart = Year(txn_date) AND $dayStart = DAY(txn_date)
group by m.member_ID";
        }
        else{
             $query2 = "SELECT m.member_ID as 'ID', firstname as 'FIRST',lastname as 'LAST',middlename as 'MIDDLE',DEPT_NAME,mf.amount  as 'MFee',ha.amount as 'HAFee',f.amount as 'FFee'
from member m
join ref_department d
on m.dept_id = d.dept_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 1 AND (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id) mf
on m.MEMBER_ID = mf.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 2 AND (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id) ha
on m.MEMBER_ID = ha.member_id
left join (SELECT sum(amount) as 'Amount',member_id from txn_reference where SERVICE_ID = 4 AND(txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59') AND TXN_TYPE =2 group by member_id)  f
on m.MEMBER_ID = f.member_id

join txn_reference t
        on t.MEMBER_ID = m.MEMBER_ID
        where TXN_TYPE =2 and (txn_date between '$yearStart-$monthStart-$dayStart 00:00:00' AND '$yearEnd-$monthEnd-$dayEnd 23:59:59')
group by m.member_ID";
        }
    
}


// Instanciation of inherited class
$pdf = new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','B',10);
$pdf->Cell(0	,5,"Detailed Overall Deductions"	,0,1,'C');
date_default_timezone_set('Singapore');
$pdf->SetFont('Times','',10);
$range = "For ".date('d F Y', mktime(0, 0, 0, $monthStart, $dayStart,$yearStart));
if(isset($yearEnd)){
    $range.="-".date('d F Y', mktime(0, 0, 0, $monthEnd, $dayEnd,$yearEnd));
}
$pdf->Cell(0    ,5,$range,0,1,'C');
$pdf->Cell(0	,5,"Generated by Melton at ".date("m/d/Y")." ".date("h:i:sa")	,0,1,'C');
$pdf->ln();
$pdf->SetFont('Times','B',10);
$pdf->Cell(40);
$pdf->Cell(20,5,''	,'L,T,R',0);
$pdf->Cell(70	,5,' '	,'L,T,R',0);
$pdf->Cell(30	,5,''	,'L,T,R',0);
$pdf->Cell(25	,5,'','L,T,R',0);
$pdf->Cell(25	,5,''	,'L,T,R',0);


$pdf->Cell(30	,5,'Total Salary '	,'L,T,R',0,'R');
$pdf->ln();
$pdf->Cell(40);
$pdf->Cell(20,5,'ID Number '	,'L,B,R',0,'C');
$pdf->Cell(70	,5,'Full Name'	,'L,B,R',0,'L');
$pdf->Cell(30	,5,'Membership Fee'	,'L,B,R',0,'R');
$pdf->Cell(25	,5,'FALP','L,B,R',0,'R');
$pdf->Cell(25	,5,'Health Aid'	,'L,B,R',0,'R');

$pdf->Cell(30	,5,'Deduction(P)'	,'L,B,R',0,'R');
$pdf->ln();
$pdf->SetFont('Times','',10);

$result=mysqli_query($dbc,$query2);
$total1=0;
$totalMFee=0;
$totalFFee=0;
$totalHAFee=0;
while($row=mysqli_fetch_assoc($result)){
$last = $row['LAST'];
$first = $row['FIRST'];
$middle = $row['MIDDLE'];

$falp =	0;
$bank = 0;
$health = 0;

$total= 0.00;


$pdf->Cell(40);
$pdf->Cell(20,5,$row['ID']	,'L,B,R',0,'C');
$pdf->Cell(70	,5,"$last, $first $middle"	,'L,B,R',0,'L');
if(!empty($row['MFee'])){
    $mfee = (float)$row['MFee'];
    $pdf->Cell(30	,5, number_format($mfee,2)	,'L,B,R',0,'R');
    $totalMFee +=$mfee;
    	
}
else
    $pdf->Cell(30   ,5,'0.00','L,B,R',0,'R');
if(!empty($row['FFee'])){
	$falp =	(float)$row['FFee'];
	$pdf->Cell(25	,5, number_format($falp,2),'L,B,R',0,'R');
    $totalFFee +=$falp;
}
else
	$pdf->Cell(25	,5,'0.00','L,B,R',0,'R');

if(!empty($row['HAFee'])){
	$health =(float)$row['HAFee'];
	$pdf->Cell(25	,5,number_format($health,2)	,'L,B,R',0,'R');
    $totalHAFee +=$health;
}
else
	$pdf->Cell(25	,5,'0.00'	,'L,B,R',0,'R');




$total = (float)$mfee+(float)$falp+(float)$health;


$total = $total;
$total1 = $total1+$total;
$pdf->Cell(30	,5,number_format($total,2)	,'L,B,R',0,'R');
$pdf->ln();



}
$pdf->Cell(40);
$pdf->SetFont('Times','B',10);
$pdf->Cell(90   ,5, 'Totals:' ,'L,B',0,'R');
$pdf->SetFont('Times','',10);
$pdf->Cell(30   ,5, number_format($totalMFee,2) ,'B',0,'R');
$pdf->Cell(25   ,5, number_format($totalFFee,2),'B',0,'R');
$pdf->Cell(25   ,5, number_format($totalHAFee,2),'B',0,'R');
$pdf->Cell(30   ,5, number_format($total1,2) ,'B,R',0,'R');
$pdf->ln();

$pdf->SetFont('Times','B',12);


$pdf->Cell(0	,5,"--END OF REPORT--"	,0,0,'C');
$pdf->Output();
?>