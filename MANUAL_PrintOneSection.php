<?php

//require('GLOBAL_PRINT_FPDF.php');
require('GLOBAL_PRINT_HTML_FPDF.php');
require('GLOBAL_CLASS_CRUD.php');


$crud = new GLOBAL_CLASS_CRUD();
//$pdf= new PDF();
$pdf=new PDF_HTML();

if(isset($_GET['versionId'])){
    $versionId = $_GET['versionId'];
    $rows = $crud->getData("SELECT s.* FROM section_versions s WHERE s.versionId = '$versionId' LIMIT 1");
    if(!empty($rows)){
        foreach((array) $rows AS $key => $row){
            $pdf->addPage();
            $pdf->setTitle($row['sectionNo'].' - '.$row['title']);
            $pdf->writeSection($row['sectionNo'], $row['title'], $row['content']);

        }
        $pdf->Output();
    }
}else if(isset($_GET['sectionId'])){
    $sectionId = $_GET['sectionId'];
    $rows = $crud->getData("SELECT s.* FROM sections s WHERE s.id = '$sectionId' LIMIT 1");
    if(!empty($rows)){
        foreach((array) $rows AS $key => $row){
            $pdf->addPage();
            $pdf->setTitle($row['sectionNo'].' - '.$row['title']);
            $pdf->writeSection($row['sectionNo'], $row['title'], $row['content']);
        }
        $pdf->Output();
    }
}

?>