<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];
//Buttons here

if(isset($_POST['btnSubmit'])){
    $title = $crud->escape_string($_POST['section_title']);
    $sectionNo = $crud->escape_string($_POST['section_number']);
    $content = $crud->escape_string($_POST['section_content']);
    //$parentSectionId = $_POST['section_parent'];
    //$siblingSectionId = $_POST['section_sibling'];
    $sectionId = $crud->executeGetKey("INSERT INTO sections (authorId, sectionNo, title, content) VALUES ('$userId', '$sectionNo', '$title', '$content')");
}

if(isset($_GET['secId'])){
    $sectionId = $_GET['secId'];

    $rows = $crud->getData("SELECT authorId, stepId, statusId, availabilityId, approvedById, lockedById, sectionNo, title, content, timeCreated FROM facultyassocnew.sections;");
    if(!empty($rows)){
        foreach((array) $rows as $key => $row){
            $authorId = $row['authorId'];
            $stepId = $row['stepId'];
            $statusId = $row['statusId'];
            $availabilityId = $row['availabilityId'];
            $approvedById = $row['approvedById'];
            $lockedById = $row['lockedById'];
            $sectionNo = $row['sectionNo'];
            $title = $row['title'];
            $content = $row['content'];
            $timeCreated = $row['timeCreated'];
        }
    }else{
        //header redirect back to Manual Revisions
        header("Location: http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/EDMS_ManualRevisions.php");
        echo 'nothing found';
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

    if($userId == $authorId){
        $query = "SELECT su.read, su.write, su.route, su.comment FROM step_author su
                WHERE su.stepId='$currentStepId' LIMIT 1;";
        $rows = $crud->getData($query);
        if(!empty($rows)){
            foreach((array) $rows as $key => $row){
                $read= $row['read'];
                $write= $row['write'];
                $route= $row['route'];
                $comment = $row['comment'];
            }
        }else{
            $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
            $rows = $crud->getData($query);
            if(!empty($rows)){
                //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
                foreach((array) $rows as $key => $row){
                    $read= $row['read'];
                    $write= $row['write'];
                    $route= $row['route'];
                    $comment = $row['comment'];
                }
            }
        }
    }else{
        $query = "SELECT su.read, su.write, su.route, su.comment FROM step_roles su
                WHERE su.stepId='$currentStepId' AND su.roleId='$edmsRole' LIMIT 1;";
        $rows = $crud->getData($query);
        if(!empty($rows)){
            //header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/EDMS_Workspace.php");
            foreach((array) $rows as $key => $row){
                $read= $row['read'];
                $write= $row['write'];
                $route= $row['route'];
                $comment = $row['comment'];
            }
        }
    }
}

$page_title = 'Faculty Manual - Edit Section';
include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';
?>
    <script>
        $(document).ready( function(){

        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row" style="margin-top: 2rem;">
                    <div class="column col-lg-7">
                        <!-- Text input-->
                        <div class="form-group">
                            <label for="section_number">Section Number</label>
                            <input id="section_number" name="section_number" type="text" class="form-control input-md" value="<?php echo $sectionNo;?>"required>
                        </div>
                        <div class="form-group">
                            <label for="section_title">Title</label>
                            <input id="section_title" name="section_title" type="text" class="form-control input-md" value="<?php echo $title;?>" required>
                        </div>
                        <div class="form-group">
                            <label for="section_content">Content</label>
                            <textarea name="section_content" class="form-control" rows="25" id="section_content"><?php echo $content;?></textarea>
                        </div>
                        <div class="card" style="margin-top: 1rem;">
                            <div class="card-header"><b>Comments</b></div>
                            <div class="card-body">
                                <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                                <span id="comment_message"></span>
                                <div id="display_comment"></div>
                            </div>
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
                                <button id="btnRefModal" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                        </div>
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header"><b>Referenced Minutes</b></div>
                            <div class="card-body" style="max-height: 20rem; overflow-y: scroll;">
                                <span id="noRefsYet">No References</span>
                                <span id="refDocuments" style="font-size: 12px;">
                                </span>
                            </div>
                            <div class="card-footer">
                                <button id="btnRefModal" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalRED"><i class="fa fa-fw fa-link"></i>Add</button>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                Unsaved
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit">Save as Draft</button>
                                <?php
                                    if(isset($write) && $write == '2') {
                                        echo '<button type="submit" class="btn btn-default" name="btnSubmit" id="btnSubmit">Save as Draft</button>';
                                    }
                                    if(isset($route) && $route == '2') {
                                        $rows = $crud->getData("SELECT sr.* FROM step_routes sr WHERE sr.currentStepId = '$stepId';");
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
    <!-- Modal by xtian pls dont delete hehe -->
    <div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    Confirm Action
                </div>
                <div class="modal-body">
                    Are you sure you want to <b id="changeText"></b> ?
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <a href="#" id="submit" class="btn btn-success success">Yes, I'm sure</a>
                </div>
            </div>
        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>