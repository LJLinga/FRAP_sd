<?php
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */
$userId = $_SESSION['idnum'];


if(isset($_POST['btnRoute'])){
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    $nextStepId = $_POST['btnRoute'];
    //$parentSectionId = $_POST['section_parent'];
    //$siblingSectionId = $_POST['section_sibling'];
    $sectionId = $crud->executeGetKey("INSERT INTO sections (authorId, sectionNo, stepId, title, content) VALUES ('$userId', '$sectionNo','$nextStepId', '$title', '$content')");
    header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_EditSection.php?secId=".$sectionId);
}

$rows = $crud->getData("SELECT sa.* FROM step_author sa WHERE stepId = 12;");
if(!empty($rows)){
    foreach((array) $rows as $key => $row){
        $read= $row['read'];
        $write= $row['write'];
        $route= $row['route'];
        $comment = $row['comment'];
    }
}

$page_title = 'Faculty Manual - Add Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <script>
        $(document).ready( function(){
            $('#section_parent').on('change', function(){
                alert('100');
                // AJAX query the latest section #
                // Based on looping
            });
        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Create New Section
                    </h3>

                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="section_number">Section Number</label>
                            <input id="section_number" name="section_number" type="text" placeholder="Put your section number here..." class="form-control input-md"  required>
                        </div>
                        <div class="form-group">
                            <label for="section_title">Title</label>
                            <input id="section_title" name="section_title" type="text" placeholder="Put your section title here..." class="form-control input-md" required>
                        </div>
                        <div class="form-group">
                            <label for="section_content">Content</label>
                            <textarea name="section_content" class="form-control" rows="20" id="section_content"></textarea>
                        </div>
                    </div>

                    <div id="publishColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 1rem;">
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header"><b>Document References</b></div>
                            <div class="card-body" style="max-height: 20rem; overflow-y: scroll;">
                                <span id="noRefsYet">No References</span>
                                <span id="refDocuments" style="font-size: 12px;">
                                </span>
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default"><i class="fa fa-fw fa-plus"></i>New</button>
                                <button id="btnRefModal" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Existing</button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Unsaved
                            </div>
                            <div class="card-footer">
                                <?php if(isset($write) && $write == '2') { ?>
                                    <button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit">Save as Draft</button>
                                <?php } ?>
                                <?php
                                    if(isset($route) && $route == '2') {
                                        $rows = $crud->getData("SELECT sr.* FROM step_routes sr WHERE sr.currentStepId = 12;");
                                        if(!empty($rows)){
                                            foreach((array) $rows as $key => $row){
                                                echo '<button type="submit" class="btn btn-primary" name="btnRoute" value="'.$row['nextStepId'].'">'.$row['routeName'].'</button>';
                                            }
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <!-- Button -->
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>