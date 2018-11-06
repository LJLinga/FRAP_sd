<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_TEMPLATE_Header.php';
include 'CMS_TEMPLATE_NAVIGATION_Editor.php';
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
                    Santinig Posts
                </h3>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="center" width="200px"><b>Title</b></th>
                            <th align="center" width="200px"><b>Author</b></th>
                            <th align="center" width="100px"><b>Status</b></th>
                            <th align="center" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="center" width="200px"><b>Title</b></th>
                            <th align="center" width="200px"><b>Author</b></th>
                            <th align="center" width="100px"><b>Status</b></th>
                            <th align="center" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                        <tbody>
                        <?php

                            $rows = $crud->getData("SELECT p.id, p.title, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status FROM mydb.posts p JOIN mydb.authors a ON p.authorId = a.id JOIN mydb.post_status s ON s.id = p.statusId WHERE s.id = 1 || s.id = 2;");
                            foreach ($rows as $key => $row){
                                ?>
                            <tr>

                                <td align="center"><?php echo $row['title'];?></td>
                                <td align="center"><?php echo $row['name'] ;?></td>
                                <td align="center"><?php echo $row['status'] ;?></td>
                                <td align="center" class="nowrap">
                                    <form method="post" action="CMS_VIEW_AddPost.php">
                                        <button type="submit" name="edit" class="btn btn-default" value=<?php echo $row['id'];?>>Edit</button>&nbsp;&nbsp;
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

