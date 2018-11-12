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
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6">

                <?php

                $rows = $crud->getData("SELECT p.id, p.title, p.body, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status, p.lastUpdated FROM posts p JOIN users a ON p.authorId = a.id JOIN post_status s ON s.id = p.statusId WHERE s.id=3 ORDER BY p.firstCreated DESC;");
                foreach ((array) $rows as $key => $row){
                    ?>

                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><b><?php echo $row['title'];?></b></h3>
                                <h5 class="card-subtitle"><i>By: <?php echo $row['name'];?> <br> Posted: <?php echo $row['lastUpdated'] ;?></i>
                                <div class="collapse" id="collapse<?php echo $row['id']?>">
                                    <p class="card-text" ><?php echo $row['body'] ?></p>
                                </div>
                                <p>
                                    <a class="card-link" data-toggle="collapse" href="#collapse<?php echo $row['id']?>" role="button" aria-expanded="false" aria-controls="collapse<?php echo $row['id']?>">Read More</a>
                                </p>
                            </div>
                        </div>

                        <p></p>
                <?php }?>
            </div>

            <div class="=">

            </div>
        </div>



    </div>

</div>

<?php include 'GLOBAL_FOOTER.php';?>
