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

//these are the queries used for later for the shit for the left side of the stuffs.
$query3 = "SELECT M.DATE_APPROVED, D.DEPT_NAME, US.STATUS
                                    FROM MEMBER M
                                    JOIN ref_department D
                                    ON M.DEPT_ID = D.DEPT_ID
                                    JOIN user_status US
                                    ON M.USER_STATUS = US.STATUS_ID
                                    WHERE M.MEMBER_ID = {$_SESSION['idnum']}";
$result3 = mysqli_query($dbc, $query3);
$row3 = mysqli_fetch_array($result3);
$dateOfAcceptance = date_create($row3['DATE_APPROVED']);
// queries for the falp
$queryCurrentLoanStatus = "SELECT ls.STATUS 
                    from loans l
                    join loan_status ls
                    on l.LOAN_STATUS = ls.STATUS_ID
                    WHERE l.MEMBER_ID = {$_SESSION['idnum']}
                    ORDER BY LOAN_ID DESC 
                    LIMIT 1";
$queryCurrentLoanResult = mysqli_query($dbc, $queryCurrentLoanStatus);
$currentLoanStatus = mysqli_fetch_array($queryCurrentLoanResult);
// health aid
$queryHealthAidStatus = "SELECT a.STATUS
                          from health_aid h 
                          JOIN app_status a 
                          on a.STATUS_ID = h.APP_STATUS
                          WHERE h.MEMBER_ID = {$_SESSION['idnum']}
                          ORDER BY RECORD_ID DESC 
                          LIMIT 1
                          ";
$queryCurrentHAResult= mysqli_query($dbc, $queryHealthAidStatus);
$currentHAStatus = mysqli_fetch_array($queryCurrentHAResult);
// lifetime statuses.

//check if the member is at least 10 years.

$query = "SELECT YEAR(DATE_APPROVED) as 'year' from member where member_id = ".$_SESSION['idnum']." ";

$hireDate = mysqli_fetch_assoc(mysqli_query($dbc,$query));

$yearHired = $hireDate['year'];

$yearNOW = date('Y');



$page_title = 'Newsfeed';
include 'GLOBAL_HEADER.php';

?>
</div>
</nav>
</div>

<div id="content-wrapper">

    <div class="container-fluid">
        <!---     PLace php code here for the information here thank you please.         -->
            <div class="row" style="position: relative;">

                <div class="column col-lg-2" style="margin-top: 1rem; margin-bottom: 1rem; position:fixed;">
                    <div class="panel panel-green" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> Account Information  </b>
                        </div>
                        <div class="panel-body" >
                            <b>Department: </b> <?php echo "Faculty - ";
                                echo $row3["DEPT_NAME"]; ?> <br>
                            <b>Member Since: </b><?php echo date_format($dateOfAcceptance, 'F  j,  Y'); ?><br>
                            <b>User Type: </b>   <?php echo $row3['STATUS'];?> <br>
                        </div>
                    </div>
                    <div class="panel panel-green" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> Faculty Assistance Loan Program </b>
                        </div>
                        <div class="panel-body" >

                            <?php if(empty($currentLoanStatus['STATUS'])) {
                                echo "No Applications yet. Apply using the link below. ";
                            }else{
                                echo '<b> Status: </b>';
                                echo $currentLoanStatus['STATUS'];
                            }
                                ?>
                        </div>
                        <div class="panel-footer" >
                            <a href = "MEMBER%20FALP%20application.php">View FALP</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>

                    <div class="panel panel-green" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> Health-Aid Assistance  </b>
                        </div>
                        <div class="panel-body" >
                            <?php if(empty($currentHAStatus['STATUS'])) {
                                echo "No Applications yet. Apply using the link below. ";
                            }else{
                                echo '<b> Status: </b>';
                                echo $currentHAStatus['STATUS'];
                            }
                            ?>

                        </div>
                        <div class="panel-footer" >
                            <a href = "ADMIN%20Deductions.php">View Health-Aid</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>

                    <div class="panel panel-green" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> Lifetime Membership Status</b>
                        </div>
                        <div class="panel-body" >
                            <?php if(($yearNOW - $yearHired) >= 10 ){?>
                            <b> Status: </b> Eligible
                            <?php }else{?>
                                <b> Status: </b> Ineligible
                            <?php }?>
                        </div>
                        <div class="panel-footer" >
                            <a href = "ADMIN%20Deductions.php">View Lifetime</a>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                    </div>


                </div>

                <div class="column col-lg-6 col-lg-offset-2" style="position: relative; margin-top: 1rem; margin-bottom: 1rem;">
                    <?php
                    $rows = $crud->getData("SELECT p.id, p.permalink, p.title, p.body, 
                                          CONCAT(a.firstName,' ', a.lastName) AS name, 
                                          s.description AS status, p.timePublished, p.lastUpdated 
                                          FROM posts p JOIN employee a ON p.authorId = a.EMP_ID 
                                          JOIN post_status s ON s.id = p.statusId 
                                          WHERE s.id='4' AND p.timePublished < '$lastTimeStamp'
                                          ORDER BY p.timePublished DESC LIMIT 10;");

                    foreach ((array) $rows as $key => $row){
                        $postId = $row['id'];
                        ?>

                        <div class="panel panel-green" style="margin-top: 1rem;">
                            <div class="panel-body" style="overflow: hidden; max-height: 50rem;">
                                <h4 class="card-title"><b><?php echo $row['title'];?></b></h4>
                                <h5 class="card-subtitle">by <?php echo $row['name'];?> | <?php echo date("F j, Y g:i A ", strtotime($row['lastUpdated'])) ;?></h5>
                                <br><p class="card-text"><?php echo $row['body'] ?></p>
                            </div>
                            <div class="panel-body">
                                <?php
                                    $rows2 = $crud->getData("SELECT pl.question 
                                                  FROM facultyassocnew.polls pl WHERE pl.postId='$postId';");
                                    if(!empty($rows)){
                                        foreach ((array) $rows2 as $key2 => $row2) {
                                            echo '<span class="badge"> Question: '.$row2['question'].'</span><br>';
                                        }
                                    }
                                    $rows3 = $crud->getData("SELECT COUNT(v.versionId) AS verCount 
                                        FROM documents d JOIN doc_versions v ON d.documentId = v.documentId
                                        JOIN post_ref_versions ref ON ref.versionId = v.versionId WHERE ref.postId = '$postId';");
                                    if($rows3[0]['verCount'] != 0){
                                        echo '<span class="badge">'.$rows3[0]['verCount'].' Document References</span>';
                                    }
                                ?>
                                <br><br>
                                <a class="card-link btn btn-default btn-sm" href="<?php echo "http://localhost/FRAP_sd/read.php?pl=".$row['permalink']?>" >Read More</a>
                            </div>
                        </div>

                        <p></p>
                        <?php
                        $postId = $row['id'];
                        $insertView = "INSERT INTO post_views (id, viewerId, typeId) VALUE ('$postId','$userId','1')";
                        $crud->execute($insertView);
                        $lastTimeStamp = $row['timePublished'];
                    }?>

                    <div class="panel panel-green">
                        <div class="panel-body">
                            <?php if(!empty($rows[0]['permalink'])) { ?>
                                <a href="<?php echo "http://localhost/FRAP_sd/feed.php?lastTimeStamp=".$lastTimeStamp ?>" >Load More</a>
                            <?php } else { ?>
                                <a href="<?php echo "http://localhost/FRAP_sd/feed.php"?>" >No More Posts. Go Back</a>
                            <?php }  ?>
                        </div>
                    </div>

                </div>

                <div id="calendarColumn" class="column col-lg-4 col-lg-offset-8" style="margin-top: 1rem; margin-bottom: 1rem; position: fixed;">
                    <div class="panel panel-green" style="margin-top: 1rem;">
                        <div class="panel-heading">
                            <b> Events </b>
                        </div>
                        <div class="panel-body" >
                            <iframe src="https://calendar.google.com/calendar/b/3/embed?showTitle=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=800&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=noreply.lapdoc%40gmail.com&amp;color=%231B887A&amp;src=en.philippines%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FManila" style="border-width:0" width="480" height="360" frameborder="0" scrolling="no"></iframe>
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
