<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 7:19 AM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();


if(isset($_POST['btnPublish'])){
    $year = $_POST['year'];
    $title = $_POST['title'];
    $publishedById = $_POST['userId'];

    $manualId = $crud->executeGetKey("INSERT INTO faculty_manual (year, title, publishedById) VALUES ('$year','$title','$publishedById');");

//    $rows = $crud->getData("SELECT v.timeCreated, v.sectionId FROM facultyassocnew.section_versions v
//                                    WHERE v.timeCreated = (SELECT MAX(v2.timeCreated) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
//                                    AND v.statusId = 2");

    $rows = $crud->getData("SELECT v.timeCreated, v.sectionId
                                    FROM facultyassocnew.section_versions v
                                    JOIN sections s ON s.id = v.sectionId
                                    WHERE v.timeCreated = (SELECT MAX(v2.timeCreated) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
                                    AND s.statusId = 2 ;");

    foreach((array) $rows AS $key=>$row){
        $sectionId = $row['sectionId'];
        $timeCreated = $row['timeCreated'];
        $crud->execute("INSERT INTO published_versions (manualId, sectionId, timeCreated) VALUES ('$manualId','$sectionId','$timeCreated');");
    }

    echo $sectionId;

}


