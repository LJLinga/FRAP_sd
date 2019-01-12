<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
//require_once ("mysql_connect_FA.php");

$userType = 'editor';
// editor can edit all published posts
// author can only edit unpublished ones

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';
?>

<script>


    $(document).ready(function() {

        table = $('#dataTable').DataTable();
        displayTable(table,'')
        $('.card-footer').html('Updated on '+table.cell(0,3).data());

        $('#tbody').on('click','.archive', function(){
            $.ajax({
                type: 'POST',
                url: 'ajax/CMS_POST_ARCHIVE.php',
                data: {
                    'id': $('.archive').val(),
                },
                success: function(msg){
                    alert(msg);
                }

            });
            table.row($(this).parents('tr')).remove().draw();
        });

        $('#btnAll').on('click', function(){
            displayTable(table, '');
        });
        $('#btnPublished').on('click', function(){
            displayTable(table, 'Published');
        });
        $('#btnPending').on('click', function(){
            displayTable(table, 'Pending')
        });
        $('#btnDraft').on('click', function(){
            displayTable(table, 'Draft');
        });
        $('#btnArchived').on('click', function(){
            displayTable(table, 'Archived');
        });


    });

    function displayTable(table, searchText){
        table.column(2).search(searchText).column(3).order('desc').draw();
    }
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Santinig Posts
                    <a class="btn btn-primary" href="CMS_ADMIN_AddPost.php"> Add New Post </a>
                </h3>
            </div>
            <div class="col-lg-12">



            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header btn-group" data-toggle="buttons">
                <a type="button" class="btn btn-default" id="btnAll">All</a>
                <a type="button" class="btn btn-success" id="btnPublished">Published</a>
                <a type="button" class="btn btn-warning" id="btnPending">Pending Review</a>
                <a type="button" class="btn btn-primary" id="btnDraft">Drafts</a>
                <a type="button" class="btn btn-danger" id="btnArchived">Archived</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </thead>
                        <tfoot>
                        <tr>

                            <th align="left" width="200px"><b>Title</b></th>
                            <th align="left" width="200px"><b>Author</b></th>
                            <th align="left" width="100px"><b>Status</b></th>
                            <th align="left" width="200px"><b>Last Updated</b></th>
                            <th align="right" width="200px"><b>Actions</b></th>

                        </tr>
                        </tfoot>
                        <tbody id="tbody">
                        <?php

                            $rows = $crud->getData("SELECT p.id, 
                                                                  p.title, 
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name, 
                                                                  s.description AS status, 
                                                                  p.lastUpdated 
                                                                  FROM posts p JOIN users a ON p.authorId = a.id 
                                                                  JOIN post_status s ON s.id = p.statusId 
                                                                  WHERE s.id = 1 || s.id = 2 || s.id=3 || s.id=4
                                                                  ORDER BY p.lastUpdated DESC;");
                            foreach ((array) $rows as $key => $row){
                                ?>
                            <tr>

                                <td align="left"><?php echo $row['title'];?></td>
                                <td align="left"><?php echo $row['name'] ;?></td>
                                <td align="left"><?php echo $row['status'] ;?></td>
                                <td align="left"><?php echo $row['lastUpdated'] ;?></td>
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
            <div class="card-footer small text-muted"><b>Updated yesterday at 11:59 PM</b>5</div>
        </div>
    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

