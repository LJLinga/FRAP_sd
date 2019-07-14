<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_CMS_ADMIN_CHECKING.php');

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
$userId = $_SESSION['idnum'];


$rows = $crud->getManualRevisionsFirstStep();
if(!empty($rows)){
    foreach((array) $rows AS $key => $row){
        $firstStepId = $row['id'];
    }
}

$query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
$rows = $crud->getData($query);
if(!empty($rows)){
    $revisions = 'open';
    foreach ((array) $rows as $key => $row){
        $revisionsOpened = $row['revisionsOpened'];
        $revisionsId = $row['id'];
    }
}else{
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_ManualRevisions.php");
}

if(isset($_POST['btnSubmit'])){
    $title = $crud->escape_string($_POST['sectionTitle']);
    $sectionNo = $crud->escape_string($_POST['sectionNo']);
    $content = $crud->escape_string($_POST['sectionContent']);
    //$parentSectionId = $_POST['section_parent'];
    //$siblingSectionId = $_POST['section_sibling'];

    $rows = $crud->getManualRevisionsFirstStep();
    if(!empty($rows)){
        foreach((array) $rows AS $key => $row){
            $nextStepId = $row['id'];
        }
    }

    $sectionId = $crud->executeGetKey("INSERT INTO sections (authorId, firstAuthorId, sectionNo, stepId, title, content) VALUES ('$userId','$userId','$sectionNo','$nextStepId', '$title', '$content')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/MANUAL_EditSection.php?secId=".$sectionId);
}

$page_title = 'Faculty Manual - Add Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Create New Section
                    </h3>
                    <div class="panel panel-default">
                        <form method="POST" action="">
                            <div class="panel-body">
                                <div class="form-group form-inline">
                                    <label for="sectionNo">Section Marker</label>
                                    <input id="sectionNo" name="sectionNo" type="text" class="form-control input-md" style="width:10%;" required>
                                    <label for="sectionTitle">Title</label>
                                    <input id="sectionTitle" name="sectionTitle" type="text" class="form-control input-md" style="width: 60%;" required>
                                </div>
                                <div class="form-group">
                                    <label for="sectionContent">Content</label>
                                    <textarea name="sectionContent" class="form-control" rows="20" id="sectionContent"></textarea>
                                </div>
                            </div>
                            <div class="panel-footer">
                                <button type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">Submit </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--Insert success page--
        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>