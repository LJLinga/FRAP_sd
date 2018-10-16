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

<div id="page-wrapper">

    <div class="container-fluid">

        <div class="row">

            <div class="col-lg-12">

                <h1 class="page-header">
                    Santinig Posts
                </h1>

            </div>

        </div>
        <!-- alert -->
        <div class="row">
            <div class="col-lg-12">

                <div class="row">

                    <div class="col-lg-12">

                        <form action="" method="POST"> <!-- SERVER SELF -->

                            <table id="table" class="table table-bordered table-striped">

                                <thead>

                                <tr>

                                    <td align="center" width="200px"><b>Title</b></td>
                                    <td align="center" width="500px"><b>Snippet</b></td>
                                    <td align="center" width="200px"><b>Author</b></td>
                                    <td align="center" width="200px"><b>Publisher</b></td>
                                    <td align="center" width="200px"><b>Actions</b></td>

                                </tr>

                                </thead>

                                <tbody>

                                <?php

                                $rows = $crud->getData("SELECT * FROM mydb.posts");
                                foreach ($rows as $key => $row){
                                    ?>
                                    <tr>

                                        <td align="center"><?php echo $row['title'];?></td>
                                        <td align="center"><?php echo $row['body'];?> </td>
                                        <td align="center"><?php echo $row['authorId'];?></td>
                                        <td align="center">"No Table Yet"</td>
                                        <td align="center"><button type="submit" name="details" class="btn btn-success" value=<?php echo $row['id'];?>>Details</button>&nbsp;&nbsp;&nbsp;</td>

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
<script>
    $("#example").DataTable();
</script>
