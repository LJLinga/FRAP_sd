<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/24/2019
 * Time: 9:43 PM
 */

require('GLOBAL_PRINT_FPDF.php');
require('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
$pdf= new PDF();

$manualId = $_GET['id'];

$rows  = $crud->getData("SELECT year, title, timePublished FROM facultyassocnew.faculty_manual WHERE id = '$manualId';");
foreach((array)$rows AS $key => $row){
    $title = $row['title'].' ('.$row['year'].')';
    $timePublished = $row['timePublished'];
}

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->setTitle($title);

$pdf->SetFont('Times','B',24);
$pdf->Cell(0, 64, $title, 1, 0, 'C');
$pdf->Ln(10);

$rows= $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name, r.roleName FROM employee e 
JOIN edms_roles r ON r.id = e.EDMS_ROLE
WHERE e.EDMS_ROLE = 3 OR e.EDMS_ROLE = 4 OR e.EDMS_ROLE = 5 OR e.EDMS_ROLE = 6
ORDER BY name;");

$pdf->SetFont('Times','I',12);
$pdf->Cell(0, 140, 'Prepared by: ', 0, 0, 'C');
$pdf->Ln(5);

foreach ((array)$rows AS $key => $row){
    $pdf->Cell(0, 160, $row['name'].' ('.$row['roleName'].')', 0, 0, 'C');
    $pdf->Ln(5);
}

$pdf->AddPage();
$rows = $crud->getData("SELECT v.sectionNo, v.title, v.content FROM facultyassocnew.published_versions pub
JOIN section_versions v ON pub.timeCreated = v.timeCreated
WHERE manualId = '$manualId'");
foreach((array)$rows AS $key => $row){
    $pdf->writeSection($row['sectionNo'],$row['title'],$row['content']);
}
$pdf->Output();
?>