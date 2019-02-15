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
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];

$page_title = 'Santinig - Events Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Upcoming Events
                    <a class="btn btn-primary" href="CMS_ADMIN_AddEvent.php"> Add New Event </a>
                </h3>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header btn-group" data-toggle="buttons">
                <a type="button" class="btn btn-default" id="btnAll">All</a>
                <a type="button" class="btn btn-success" id="btnPublished">Ongoing</a>
                <a type="button" class="btn btn-warning" id="btnPending">Upcoming</a>
                <a tye="button" class="btn btn-primary" id="btnDraft">Finished</a>
                <a type="button" class="btn btn-danger" id="btnArchived">Cancelled</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <?php if($cmsRole == 3) echo '<th align="left" width="200px"><b>Poster</b></th>' ?>
                            <th aligh="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Time</b></th>
                            <th align="left" width="50px"><b>Attendees</b></th>
                            <th align="left" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <?php if($cmsRole == 3) echo '<th align="left" width="200px"><b>Poster</b></th>' ?>
                            <th aligh="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Time</b></th>
                            <th align="left" width="50px"><b>Attendees</b></th>
                            <th align="left" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                        <tbody>

                        <?php

                        if($cmsRole == 3){
                            // Editor can see all his posts and drafts, and all "pending","published",and "archived" posts that are not his but not other's drafts
                            $query = "SELECT e.id,
                                                                  e.title,
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name,
                                                                  e.startTime, e.endTime,
                                                                  s.description AS status,
                                                                  e.lastUpdated
                                                                  FROM events e JOIN employee a ON e.posterId = a.EMP_ID
                                                                  JOIN event_status s ON s.id = e.statusId
                                                                  WHERE s.id = 2 || s.id=3 || s.id=4
                                                                  OR s.id = 1 AND e.posterId = '$userId'
                                                                  ORDER BY e.lastUpdated DESC;";
                        }else{
                            // Non-editors can only view their posts, can also see their "published" and "archived" but would not be able to modify them.
                            $query = "SELECT e.id,
                                                                  e.title,
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name,
                                                                  e.startTime, e.endTime,
                                                                  s.description AS status,
                                                                  e.lastUpdated
                                                                  FROM events e JOIN employee a ON e.posterId = a.EMP_ID
                                                                  JOIN event_status s ON s.id = e.statusId
                                                                  WHERE e.posterId = '$userId'
                                                                  ORDER BY e.lastUpdated DESC;";
                        }

                        $rows = $crud->getData($query);
                        foreach ((array) $rows as $key => $row){
                            ?>
                            <tr>

                                <td align="left"><?php echo $row['title'];?></td>
                                <?php if($cmsRole == 3) echo '<td align="left">'.$row['name'].'</td>' ?>
                                <td align="left"><?php echo $row['status'] ;?></td>
                                <td align="left"><?php
                                    $text1 = trim($row['startTime'], 'T');
                                    $text2 =  trim($row['endTime'], 'T');
                                    //$date = date_create('2000-01-01');
                                    //echo date_format($date, 'Y-m-d H:i:s');
                                    echo $text1." to ".$text2;
                                    ?></td>
                                <td align="left"><?php echo "-" ;?></td>
                                <td align="right" class="nowrap">
                                    <form method="GET" action="CMS_ADMIN_EditPost.php">
                                        <button type="submit" name="postId" class="btn btn-default" value=<?php echo $row['id'];?>>Edit</button>&nbsp;&nbsp;
                                        <button type="button" name="archive" class="archive btn btn-danger" value="<?php echo $row['id']?>">Archive</button>
                                    </form>
                                </td>

                            </tr>
                        <?php }?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
        </div>

    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->
