<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/25/2019
 * Time: 7:19 AM
 */

if(isset($_POST['btnPublish'])){

    $rows = $crud->execute("SELECT v.* FROM facultyassocnew.section_versions v 
                    WHERE v.timeCreated = (SELECT MAX(v2.timeCreated) FROM section_versions v2 WHERE v.sectionId = v2.sectionId)
                    AND v.statusId = 2");
    //$crud->execute("INSERT INTO revisions (initiatedById, statusId) VALUES ('$userId','2')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_PublishSections.php");
}