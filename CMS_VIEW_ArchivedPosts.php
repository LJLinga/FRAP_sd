<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$page_title = 'Santinig - Archived Posts';
include 'GLOBAL_TEMPLATE_Header.php';
include 'CMS_TEMPLATE_NAVIGATION_Editor.php';
?>

<script>
    $(document).ready(function () {
        $('#table').DataTable({
            "searching": true
            "paging": "simple_numbers" // false to disable pagination (or any other option)
        });
        $('.dataTable_length').addClass('bs-select');

    });
</script>

<div id="page-wrapper">

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header">
                    Archived Posts
                </h1>

            </div>

        </div>
        <!-- alert -->
        <div class="row">
            <div class="col-lg-12">

                <div class="row">

                    <div class="col-lg-12">

                        <form action="" method="POST"> <!-- SERVER SELF -->

                            <table id="table" name="table" class="table table-striped table-bordered table-sm">

                                <thead>

                                <tr>

                                    <th align="center" width="200px"><b>Title</b></th>
                                    <th align="center" width="500px"><b>Snippet</b></th>
                                    <th align="center" width="200px"><b>Author</b></th>
                                    <th align="center" width="200px"><b>Publisher</b></th>
                                    <th align="center" width="200px"><b>Status</b></th>
                                    <th align="center" width="200px"><b>Actions</b></th>

                                </tr>

                                </thead>

                                <tbody>

                                <?php

                                $rows = $crud->getData("SELECT p.id, p.title, p.body, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status FROM mydb.posts p JOIN mydb.authors a ON p.authorId = a.id JOIN mydb.post_status s ON s.id = p.statusId WHERE s.id = 3 ;");
                                foreach ($rows as $key => $row){
                                    ?>
                                    <tr>

                                        <td align="center"><?php echo $row['title'];?></td>
                                        <td align="center"><?php echo $row['body'];?> </td>
                                        <td align="center"><?php echo $row['name'] ;?></td>
                                        <td align="center">"No Table Yet"</td>
                                        <td align="center"><?php echo $row['status'] ;?></td>
                                        <td align="center">
                                            <button type="submit" name="restore" class="btn btn-default" value=<?php echo $row['id'];?>>Restore</button>
                                            <button type="submit" name="delete" class="btn btn-primary" value=<?php echo $row['id'];?>>Delete</button>&nbsp;&nbsp;
                                        </td>

                                    </tr>
                                <?php }?>

                                </tbody>

                            </table>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
<!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

