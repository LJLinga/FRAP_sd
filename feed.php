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

$userId=$_SESSION['idnum'];

if(empty($_GET['lastTimeStamp'])){
    $lastTimeStamp = $crud->getData("SELECT CURRENT_TIMESTAMP() AS time");
    $lastTimeStamp = $lastTimeStamp[0]['time'];
}else{
    $lastTimeStamp = $_GET['lastTimeStamp'];
}

$page_title = "Santinig Feed";
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>
<style>
    @media screen and (min-width: 1200px) {
        #calendarColumn{
            position: fixed;
            right:1rem;
        }
    }
    @media screen and (max-width: 1199px) {
        #calendarColumn{
            position: relative;
        }
    }
    .card {
        font-family: "Verdana", Georgia, Serif;
        font-size: 12px;
    }
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="column col-lg-7" style="margin-top: 2rem; margin-bottom: 2rem;">

                <?php

                $rows = $crud->getData("SELECT p.id, p.permalink, p.title, p.body, 
                                          CONCAT(a.firstName,' ', a.lastName) AS name, 
                                          s.description AS status, p.timePublished, p.lastUpdated 
                                          FROM posts p JOIN employee a ON p.authorId = a.EMP_ID 
                                          JOIN post_status s ON s.id = p.statusId 
                                          WHERE s.id='4' AND p.timePublished < '$lastTimeStamp'
                                          ORDER BY p.timePublished DESC LIMIT 10;");

                foreach ((array) $rows as $key => $row){
                    ?>

                        <div class="card" >
                            <div class="card-body" style="overflow: hidden; max-height: 50rem;">
                                <h4 class="card-title"><b><?php echo $row['title'];?></b></h4>
                                <h5 class="card-subtitle">by <?php echo $row['name'];?> | <?php echo date("F j, Y g:i A ", strtotime($row['lastUpdated'])) ;?></h5>
                                <br><p class="card-text"><?php echo $row['body'] ?></>
                            </div>
                            <div class="card-body">
                                <a class="card-link" href="<?php echo "http://localhost/FRAP_sd/read.php?pl=".$row['permalink']?>" >Read More</a>
                            </div>
                        </div>

                        <p></p>
                <?php
                    $postId = $row['id'];
                    $insertView = "INSERT INTO post_views (id, viewerId, typeId) VALUE ('$postId','$userId','1')";
                    $crud->execute($insertView);
                    $lastTimeStamp = $row['timePublished'];
                }?>

                <div class="card">
                    <div class="card-body">
                        <?php if(!empty($rows[0]['permalink'])) { ?>
                            <a href="<?php echo "http://localhost/FRAP_sd/feed.php?lastTimeStamp=".$lastTimeStamp ?>" >Load More</a>
                        <?php } else { ?>
                            <a href="<?php echo "http://localhost/FRAP_sd/feed.php"?>" >No More Posts. Go Back</a>
                        <?php }  ?>
                    </div>
                </div>
            </div>

            <div id="calendarColumn" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 2rem;">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Events </b>
                    </div>
                    <div class="card-body" >
                        <iframe src="https://calendar.google.com/calendar/b/3/embed?showTitle=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=800&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=noreply.lapdoc%40gmail.com&amp;color=%231B887A&amp;src=en.philippines%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FManila" style="border-width:0" width="480" height="360" frameborder="0" scrolling="no"></iframe>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Polls </b>
                    </div>
                    <div class="card-body" >
                    </div>
                </div>
            </div>



    </div>

</div>

<?php include 'GLOBAL_FOOTER.php';?>

