<?php


require_once ("mysql_connect_FA.php");
include 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';

error_reporting(NULL);

$userId=$_SESSION['idnum'];

if(empty($_GET['lastTimeStamp'])){
    $lastTimeStamp = $crud->getData("SELECT CURRENT_TIMESTAMP() AS time");
    $lastTimeStamp = $lastTimeStamp[0]['time'];
}else{
    $lastTimeStamp = $_GET['lastTimeStamp'];
}


$page_title = 'Loans - Dashboard';
include 'GLOBAL_HEADER.php';

?>
</div>
</nav>
</div>



<div id="content-wrapper">

    <div class="container-fluid">

        <!---     PLace php code here for the information here thank you please.         -->


            <div class="row">
                <div class="column col-lg-2" style="margin-top: 1rem;">

                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-header">
                            <b> Account Information  </b>
                        </div>
                        <div class="card-body" >
                            <b>Account Type: </b> Pending/Ongoing/Matured/Declined <br>
                            <b>Date Accepted.  </b>Pending/Accepted/Declined <br>
                            <b>Lifetime: </b> Ineligible/ Eligible <br>
                        </div>
                        <div class="card-footer" >
                            <a href = "ADMIN%20Deductions.php">View All Deductions</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-header">
                            <b> Faculty Assistance Loan Program </b>
                        </div>
                        <div class="card-body" >
                            <b> Status: </b> Your Mother Gay
                        </div>
                        <div class="card-footer" >
                            <a href = "ADMIN%20Deductions.php">View FALP</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-header">
                            <b> Health-Aid Assistance  </b>
                        </div>
                        <div class="card-body" >
                            <b> Status: </b> Your Mother Gay
                        </div>
                        <div class="card-footer" >
                            <a href = "ADMIN%20Deductions.php">View Health-Aid</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>

                    <div class="card" style="margin-top: 1rem;">
                        <div class="card-header">
                            <b> Lifetime Membership Status</b>
                        </div>
                        <div class="card-body" >
                            <b> Status: </b> Your Mother Gay
                        </div>
                        <div class="card-footer" >
                            <a href = "ADMIN%20Deductions.php">View Lifetime</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>


                </div>





                <div class="column col-lg-6" style="margin-top: 2rem; margin-bottom: 2rem;">

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

                <div id="calendarColumn" align="center" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 2rem;">
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





    </div>
    <!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>
