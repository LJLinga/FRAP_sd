<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 11/9/2018
 * Time: 9:51 AM
 */

include 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

include 'GLOBAL_HEADER.php';
include 'GLOBAL_NAV_TopBar.php';

//change into USER sidebar
include 'CMS_ADMIN_NAV_Sidebar.php';

?>
<div id="page-wrapper">
    <div class="container-fluid" style="padding-top: 2rem;">

        <div class="row">
            <div class="col-lg-6">

                <?php

                $rows = $crud->getData("SELECT p.id, p.title, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status, p.lastUpdated FROM mydb.posts p JOIN mydb.authors a ON p.authorId = a.id JOIN mydb.post_status s ON s.id = p.statusId WHERE s.id = 2;");
                foreach ((array) $rows as $key => $row){
                    ?>

                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><?php echo $row['title'];?></h3>
                                <h5 class="card-subtitle"><i>By: <?php echo $row['name'];?> <br> Posted: <?php echo $row['lastUpdated'] ;?></i></h5>
                                <p class="card-text">Some example text. Some example text.</p>
                                <a href="#" class="card-link">Card link</a>
                                <a href="#" class="card-link">Another link</a>
                            </div>
                        </div>

                        <p></p>
                <?php }?>


                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Post Title</h4>
                        <p class="card-text">Some example text. Some example text.</p>
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>

                <p></p>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Post Title</h4>
                        <p class="card-text">Some example text. Some example text.</p>
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
        </div>



    </div>

</div>

<?php include 'GLOBAL_FOOTER.php';?>

