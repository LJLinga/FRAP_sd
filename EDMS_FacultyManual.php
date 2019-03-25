<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_SIDEBAR.php';

$edmsRole = $_SESSION['EDMS_ROLE'];
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Faculty Manual
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
            <?php
                $rows = $crud->getData("SELECT v.sectionNo, v.title, v.content FROM facultyassocnew.published_versions pub
                JOIN section_versions v ON pub.timeCreated = v.timeCreated
                JOIN faculty_manual m ON m.id = pub.manualId
				WHERE m.id = (SELECT MAX(m2.id) FROM faculty_manual m2 )
				ORDER BY v.sectionNo; ");

                $count = 0;
                if(!empty($rows)){
                    foreach((array) $rows as $key => $row){
                        $count++;
            ?>
                <div class="panel panel-default" style="margin-bottom: 1rem;">
                    <div class="panel-heading">
                        <a class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo $count;?>" aria-expanded="false" aria-controls="collapse<?php echo $count;?>">
                            <div class="panel-title">Section <?php echo $row['sectionNo'].' - '.$row['title'];?></div>
                        </a>
                    </div>
                    <div id="collapse<?php echo $count;?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                        <div class="panel-body" >
                            <?php echo $row['content']; ?>
                        </div>
                    </div>
                </div>
            <?php }}?>
            </div>
            <div class="col-lg-4" >
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Published Manual Editions
                    </div>
                    <div class="panel-body">
                        <?php
                        $rows = $crud->getData("SELECT id, year, title, timePublished, publishedById 
                                        FROM facultyassocnew.faculty_manual ORDER BY id DESC;");
                        if(!empty($rows)){
                            foreach((array)$rows AS $key => $row){
                                echo '<div class="card" style="position: relative;">';
                                echo '<div class="card-body">';
                                echo $row['title'].' ('.$row['year'].')<br>';
                                echo '<a href="EDMS_PublishSections.php?id='.$row['id'].'" target="_blank" class="btn btn-primary btn-sm" style="position: absolute; right: 2rem; top: 0.5rem;"><i class="fa fa-print"></i></a>';
                                echo '</div></div>';
                            }
                        }else{
                            echo 'You have no published manuals editions.';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
