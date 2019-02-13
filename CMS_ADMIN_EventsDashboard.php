1<?php
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
                    <a class="btn btn-primary" href="CMS_ADMIN_EditPost.php"> Add New Post </a>
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
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Time</b></th>
                            <th align="left" width="100px"><b>Attendees</b></th>
                            <th align="left" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Time</b></th>
                            <th align="left" width="50px"><b>Attendees</b></th>
                            <th align="left" width="100px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                        <tbody>
                        <?php

                        //$row = array('Suntukan sa LRT', '2018 Oct 20 09:00 AM to 2018 Oct 21 09:00 PM', '31');

                        $row['title'] = 'Suntukan sa LRT';
                        $row['time']= '2018 Oct 20 09:00 AM to 2018 Oct 21 09:00 PM';
                        $row['going'] = '31';
                        $row['id'] = 0;
                        $rows = array($row);
                        //$rows = $crud->getData("SELECT p.id, p.title, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status FROM mydb.posts p JOIN mydb.authors a ON p.authorId = a.id JOIN mydb.post_status s ON s.id = p.statusId WHERE s.id = 1 || s.id = 2;");
                        foreach ($rows as $key => $row){
                            ?>
                            <tr>

                                <td align="left"><?php echo $row['title'];?></td>
                                <td align="left"><?php echo $row['time'] ;?></td>
                                <td align="left"><?php echo $row['going'] ;?></td>
                                <td align="right" class="nowrap">
                                    <form method="GET" action="CMS_ADMIN_EditPost.php">
                                        <button type="submit" name="postId" class="btn btn-default" value=<?php echo $row['id'];?>>Edit</button>&nbsp;&nbsp;
                                        <button type="button" name="archive" class="btn btn-danger" value=<?php echo $row['id'];?>>Archive</button>&nbsp;&nbsp;
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
