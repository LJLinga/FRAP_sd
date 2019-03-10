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

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR_Admin.php';

$userId = $_SESSION['idnum'];

?>

<script>
    $(document).ready(function() {

        table = $('#dataTable').DataTable({
            rowReorder: true
        });

        let idnum = <?php echo $_SESSION['idnum']?>;

        setInterval(function(){
            load_cms_notifications(idnum);
        },1000);

        let cmsRole = "<?php echo $cmsRole; ?>";
        let s = 2;
        let d = 3;
        let forMe = '';

        if(cmsRole==='3' || cmsRole==='4'){
            if(cmsRole === '4'){
                forMe = 'Pending Publication';
            }else if(cmsRole === '3'){
                forMe = 'Pending Review';
            }
        }else{
            s = 1;
            d = 2;
        }

        displayTable(table,'');
        $('.card-footer').html('Updated on '+table.cell(0,d).data());
        displayTable(table,forMe,s, d);


        $('#tbody').on('click','.archive', function(){
            $.ajax({
                type: 'POST',
                url: 'ajax/CMS_POST_ARCHIVE.php',
                data: {
                    'id': $('.archive').val(),
                },
                success: function(msg){
                    alert("Post archived!");
                    //$('.status').html("Trashed");
                }
            });

        });

        $('#btnAll').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, '',s, d);
        });
        $('#btnMine').on('click', function(){
            displayTable(table, '',s, d);
            displayTable(table, '(Me)',s-1, d);
        });
        $('#btnPublished').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, 'Published',s, d);
        });
        $('#btnPendingReview').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, 'Pending Review', s, d)
        });
        $('#btnReviewed').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, 'Pending Publication', s, d)
        });
        $('#btnDraft').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, 'Draft', s, d);
        });
        $('#btnForMe').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, forMe, s, d);
        });
        $('#btnArchived').on('click', function(){
            displayTable(table, '',s-1, d);
            displayTable(table, 'Trashed', s, d);
        });


    });

    function displayTable(table, searchText, statusColumn, dateColumn){
        table.column(statusColumn).search(searchText).column(dateColumn).order('desc').draw();
    }

    function load_cms_notifications(idnum)
    {
        $.ajax({
            url:"CMS_AJAX_Notifications.php",
            method:"POST",
            data:{userId:idnum, limit: 50},
            dataType:"json",
            success:function(data)
            {
                $('#activityStream').html(data.notification);
            }
        });
    }
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header">
                    Santinig Posts
                    <a class="btn btn-primary" href="CMS_AddPost.php"> Add New Post </a>
                </h3>
            </div>

        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="card mb-3">
                    <div class="card-header btn-group" data-toggle="buttons">

                        <?php if($cmsRole == '3' || $cmsRole == '4') echo '<a type="button" class="btn btn-primary" id="btnForMe">Needs My Attention</a>'?>
                        <a type="button" class="btn btn-default" id="btnAll">All</a>
                        <?php if($cmsRole == '3' || $cmsRole == '4') echo '<a type="button" class="btn btn-default" id="btnMine">Mine</a>'?>
                        <a type="button" class="btn btn-success" id="btnDraft">My Drafts</a>
                        <?php if($cmsRole == '2') echo '<a type="button" class="btn btn-warning" id="btnPendingReview">Pending Review</a>'?>
                        <?php if($cmsRole == '2' || $cmsRole == '3') echo '<a type="button" class="btn btn-warning" id="btnReviewed">Pending Publication</a>'?>
                        <?php if($cmsRole == '2' || $cmsRole == '3' || $cmsRole == '4') echo '<a type="button" class="btn btn-warning" id="btnPublished">Published</a>'?>
                        <a type="button" class="btn btn-danger" id="btnArchived">Archived</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>

                                    <th align="left" width="200px"><b>Title</b></th>
                                    <?php if($cmsRole == '3' || $cmsRole == '4') echo '<th align="left" width="200px"><b>Author</b></th>'?>
                                    <th align="left" width="100px"><b>Status</b></th>
                                    <th align="left" width="200px"><b>Last Updated</b></th>
                                    <th align="right" width="200px"><b>Actions</b></th>

                                </tr>
                                </thead>
                                <tfoot>
                                <tr>

                                    <th align="left" width="200px"><b>Title</b></th>
                                    <?php if($cmsRole == '3' || $cmsRole == '4') echo '<th align="left" width="200px"><b>Author</b></th>'?>
                                    <th align="left" width="100px"><b>Status</b></th>
                                    <th align="left" width="200px"><b>Last Updated</b></th>
                                    <th align="right" width="200px"><b>Actions</b></th>

                                </tr>
                                </tfoot>
                                <tbody id="tbody">
                                <?php

                                if($cmsRole == '4'){
                                    // Editor can see all his posts and drafts, and all "pending","published",and "archived" posts that are not his but not other's drafts
                                    $query = "SELECT p.id,
                                                                  p.title, p.authorId,
                                                                  CONCAT(a.firstName,' ', a.lastName) AS name,
                                                                  s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE s.id = 3 OR s.id = 4
                                                                  OR p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
                                }else if($cmsRole == '3'){
                                    // Non-editors can only view their posts, can also see their "published" and "archived" but would not be able to modify them.
                                    $query = "SELECT p.id,
                                            p.title, p.authorId,
                                            CONCAT(a.firstName,' ', a.lastName) AS name,
                                            s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE s.id = 2 
                                                                  OR p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
                                }else if($cmsRole == '2'){
                                    $query = "SELECT p.id,
                                            p.title, p.authorId,
                                            CONCAT(a.firstName,' ', a.lastName) AS name,
                                            s.description AS status,
                                                                  p.lastUpdated
                                                                  FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                                                  JOIN post_status s ON s.id = p.statusId
                                                                  WHERE p.authorId = '$userId'
                                                                  OR p.archivedById = '$userId'
                                                                  ORDER BY p.lastUpdated DESC;";
                                }

                                $rows = $crud->getData($query);
                                foreach ((array) $rows as $key => $row){
                                    ?>
                                    <tr>

                                        <td align="left"><?php echo $row['title'];?></td>
                                        <?php
                                        if(($cmsRole == '3' || $cmsRole == '4') && $userId == $row['authorId']){
                                            echo '<td align="left">' . $row['name'] . ' <b>(Me)</b></td>';
                                        }else if(($cmsRole == '3' || $cmsRole == '4') && $userId != $row['authorId']) {
                                            echo '<td align="left">' . $row['name'] . '</td>';
                                        }
                                        ?>
                                        <td align="left" class="status"><?php echo $row['status'] ;?></td>
                                        <td align="left"><?php echo $row['lastUpdated'] ;?></td>
                                        <td align="right" class="nowrap">
                                            <form method="GET" action="CMS_EditPost.php">
                                                <button type="submit" name="postId" class="btn btn-default" value=<?php echo $row['id'];?>>Edit</button>
                                                <?php if($row['status']!='4') { ?>
                                                    <button type="button" name="archive" class="archive btn btn-danger" value="<?php echo $row['id']?>">Archive</button>
                                                <?php } elseif($row['status']=='4') { ?>
                                                    <button type="button" name="restore" class="restore btn btn-success" value="<?php echo $row['id']?>">Restore</button>
                                                <?php } ?>
                                            </form>
                                        </td>

                                    </tr>
                                <?php }?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer small text-muted"></div>
                </div>

            </div>
            <div class="row">

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <b>In Process by Me</b>
                    </div>
                    <div class="card-body" style="max-height: 20rem; overflow: auto;">
                        <?php
                            $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
                                      s.description AS status,p.lastUpdated FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                      JOIN post_status s ON s.id = p.statusId
                                      WHERE p.availabilityId = '1' AND p.lockedById = '$userId'
                                      ORDER BY p.firstCreated DESC LIMIT 10;";
                            $rows = $crud->getData($query);
                            if(!empty($rows)){
                                foreach ((array) $rows as $key => $row){
                                    echo '<div class="card-body" style="position: relative;">';
                                    echo ''.$row['title'];
                                    echo '<a href="CMS_EditPost.php?postId='.$row['id'].'" class="btn btn-sm" style="position: absolute;right: 10px;top: 5px;">Continue</a>';
                                    echo '</div>';
                                }
                            }else{
                                echo 'Nothing to show';
                            }
                        ?>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Activity Stream </b>
                    </div>
                    <div class="card-body" style="max-height: 20rem; overflow: auto;">
                        <div id="activityStream"></div>
                    </div>
                </div>

                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Post Activity (Comments, Status Changes)</b>
                    </div>
                    <div class="card-body" style="max-height: 20rem; overflow: auto;">
                        <?php
                        $query = "SELECT p.id,p.title, p.authorId,CONCAT(a.firstName,' ', a.lastName) AS name,
                                      s.description AS status,p.lastUpdated FROM posts p JOIN employee a ON p.authorId = a.EMP_ID
                                      JOIN post_status s ON s.id = p.statusId
                                      WHERE p.availabilityId = '1' AND p.lockedById = '$userId'
                                      ORDER BY p.firstCreated DESC LIMIT 10;";

                        $rows = $crud->getData($query);
                        foreach ((array) $rows as $key => $row){
                            echo '<div class="card-body" style="position: relative;">';
                            echo ''.$row['title'];
                            echo '<a href="CMS_EditPost.php?postId='.$row['id'].'" class="btn btn-sm" style="position: absolute;right: 10px;top: 5px;">Continue</a>';
                            echo '</div>';
                        }

                        ?>
                    </div>
                </div>
            </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->
