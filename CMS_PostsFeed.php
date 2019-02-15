<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 11/9/2018
 * Time: 9:51 AM
 */

include 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';

?>
<style>
    .googleCalendar{
        position: fixed;
        height: 0;
        width: 25%;
        padding-bottom:25%;
    }

    .googleCalendar iframe{
        position: absolute;
        top: 10px;
        right: 10px;
        width: 100%;
        height: 100%;
    }
</style>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6" style="top: 1rem;">

                <?php

                $rows = $crud->getData("SELECT p.id, p.title, p.body, CONCAT(a.firstName,' ', a.lastName) AS name, s.description AS status, p.lastUpdated FROM posts p JOIN employee a ON p.authorId = a.EMP_ID JOIN post_status s ON s.id = p.statusId WHERE s.id=3 ORDER BY p.firstCreated DESC;");
                foreach ((array) $rows as $key => $row){
                    ?>

                        <div class="card">
                            <div class="card-body">
                                <h3 class="card-title"><b><?php echo $row['title'];?></b></h3>
                                <h5 class="card-subtitle"><i>By: <?php echo $row['name'];?> <br> Posted: <?php echo $row['lastUpdated'] ;?></i>
                                    <p class="card-text" ><?php echo $row['body'] ?></p>
                                <div class="collapse" id="collapse<?php echo $row['id']?>">

                                </div>
                                <p>
                                    <a class="card-link" data-toggle="collapse" href="#collapse<?php echo $row['id']?>" role="button" aria-expanded="false" aria-controls="collapse<?php echo $row['id']?>">Read More</a>
                                </p>
                            </div>
                        </div>

                        <p></p>
                <?php }?>
            </div>

            <div class="col-lg-4">
                <div class="googleCalendar">
                    <iframe src="https://calendar.google.com/calendar/b/3/embed?title=AFED%20Events&amp;height=400&amp;wkst=2&amp;bgcolor=%2399ff99&amp;src=noreply.lapdoc%40gmail.com&amp;color=%231B887A&amp;src=en.philippines%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FManila" style="border-width:0" width="600" height="400" frameborder="0" scrolling="no"></iframe>
                </div>
        </div>



    </div>

</div>

<?php include 'GLOBAL_FOOTER.php';?>

