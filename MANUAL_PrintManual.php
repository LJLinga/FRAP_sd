<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 9:43 PM
 */

require('GLOBAL_PRINT_HTML_FPDF.php');
require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
$pdf=new PDF_HTML();

$manualId = $_GET['id'];

$rows  = $crud->getData("SELECT year, title, timePublished FROM facultyassocnew.faculty_manual WHERE id = '$manualId';");

if(!empty($rows)){
    foreach((array)$rows AS $key => $row){
        $title = $row['title'].' ('.$row['year'].')';
        $timePublished = $row['timePublished'];
    }
}


$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle($title);

$pdf->SetFont('Times','B',24);
$pdf->Cell(0, 64, $title, 1, 0, 'C');
$pdf->Ln(10);

$pdf->SetFont('Times','I',12);
$pdf->Cell(0, 140, 'Prepared by the following: ', 0, 0, 'C');
$pdf->Ln(5);

//President (SIX)
$rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ', e.FIRSTNAME) as name 
                                FROM employee e 
                                JOIN user_groups ug 
                                ON e.EMP_ID = ug.userId 
                                WHERE ug.groupId = 6");
if(!empty($rows)){
    foreach((array)$rows AS $key => $row){
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0, 160, $row['name'], 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times','I',12);
        $pdf->Cell(0, 160, 'President', 0, 0, 'C');
    }
    $pdf->Ln(10);
}

//Negotation Head (FOUR)
$rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ', e.FIRSTNAME) as name 
                                FROM employee e 
                                JOIN user_groups ug 
                                ON e.EMP_ID = ug.userId 
                                WHERE ug.groupId = 4");
if(!empty($rows)){
    foreach((array)$rows AS $key => $row){
        $pdf->SetFont('Times','B',12);
        $pdf->Cell(0, 160, $row['name'], 0, 0, 'C');
        $pdf->Ln(5);
        $pdf->SetFont('Times','I',12);
        $pdf->Cell(0, 160, 'Negotiation Head', 0, 0, 'C');
    }
}


//Executive Board (ELEVEN)
$rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ', e.FIRSTNAME) as name 
                                FROM employee e 
                                JOIN user_groups ug 
                                ON e.EMP_ID = ug.userId 
                                WHERE ug.groupId = 11");
if(!empty($rows)){
    $pdf->AddPage();
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(0, 20, 'The Executive Board', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Times','',12);
    foreach((array)$rows AS $key => $row){
        $pdf->Cell(0, 20, $row['name'], 0, 0, 'C');
        $pdf->Ln(5);
    }
    $pdf->Ln(10);
}

//Technical Panel (TWELVE)
$rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ', e.FIRSTNAME) as name 
                                FROM employee e 
                                JOIN user_groups ug 
                                ON e.EMP_ID = ug.userId 
                                WHERE ug.groupId = 12");
if(!empty($rows)){
    $pdf->SetFont('Times','B',12);
    $pdf->Cell(0, 20, 'The Technical Panel', 0, 0, 'C');
    $pdf->Ln(10);
    $pdf->SetFont('Times','',12);
    foreach((array)$rows AS $key => $row){
        $pdf->Cell(0, 20, $row['name'], 0, 0, 'C');
        $pdf->Ln(5);
    }
}

$rows = $crud->getData("SELECT v.sectionNo, v.title, v.content FROM facultyassocnew.published_versions pub
JOIN section_versions v ON pub.versionId = v.versionId
WHERE pub.manualId = '$manualId'");


if(!empty($rows)){
    foreach((array)$rows AS $key => $row){
        $pdf->AddPage();
        $pdf->writeSection($row['sectionNo'],$row['title'],$row['content']);
    }
    $pdf->Output();
}else{
    echo 'Database error.';
}


?>