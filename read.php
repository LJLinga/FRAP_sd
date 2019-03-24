<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/31/2018
 * Time: 10:57 AM
 **/

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

$userId = $_SESSION['idnum'];
$postId = '';

if(!empty($_GET['pl'])){

    $permalink = $_GET['pl'];

    $rows = $crud->getData("SELECT p.id, p.authorId, p.publisherId, p.statusId
                            FROM posts p
                            WHERE p.permalink = '$permalink'");

    foreach ((array) $rows as $key => $row) {
        $postId = $row['id'];
        $authorId = $row['authorId'];
        $publisherId = $row['publisherId'];
        $statusId = $row['statusId'];
    }

    $insertView = "INSERT INTO post_views (id, viewerId, typeId) VALUE ('$postId','$userId','2')";
    $crud->execute($insertView);

    if($statusId!='4'){
        if($authorId!=$userId && $cmsRole!='4' && $cmsRole!='3'){
            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/feed.php");
        }
    }

    $rows = $crud->getData("SELECT 
            p.title,
            CONCAT(u.firstName,' ', u.lastName) AS author,
            p.body,
            p.statusId,
            p.lastUpdated
        FROM
            employee u
                JOIN
            posts p ON p.authorId = u.EMP_ID
        WHERE
            p.permalink = '$permalink'   ");

    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $author = $row['author'];
        $lastUpdated = $row['lastUpdated'];
        $statusId = $row['statusId'];
    }


}else{
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/feed.php");
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

$page_title = $title;
include 'GLOBAL_HEADER.php';

?>
</div>
</nav>
</div>
<script>

</script>
    <div class="container-fluid">
        <div class="row">
            <div class="column col-lg-2" style="margin-top: 1rem; margin-bottom: 1rem;">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Account Information  </b>
                    </div>
                    <div class="card-body" >
                        <b>Department: </b> <?php echo "Faculty - ";
                        echo $row3["DEPT_NAME"]; ?> <br>
                        <b>Member Since: </b><?php echo date_format($dateOfAcceptance, 'F  j,  Y'); ?><br>
                        <b>User Type: </b>   <?php echo $row3['STATUS'];?> <br>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Faculty Assistance Loan Program </b>
                    </div>
                    <div class="card-body" >

                        <?php if(empty($currentLoanStatus['STATUS'])) {
                            echo "No Applications yet. Apply using the link below. ";
                        }else{
                            echo '<b> Status: </b>';
                            echo $currentLoanStatus['STATUS'];
                        }
                        ?>
                    </div>
                    <div class="card-footer" >
                        <a href = "MEMBER%20FALP%20application.php">View FALP</a>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>

                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Health-Aid Assistance  </b>
                    </div>
                    <div class="card-body" >
                        <?php if(empty($currentHAStatus['STATUS'])) {
                            echo "No Applications yet. Apply using the link below. ";
                        }else{
                            echo '<b> Status: </b>';
                            echo $currentHAStatus['STATUS'];
                        }
                        ?>

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
                        <b> Status: </b> Eligible
                    </div>
                    <div class="card-footer" >
                        <a href = "ADMIN%20Deductions.php">View Lifetime</a>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>


            </div>
            <div class="column col-lg-6" style="margin-top: 1rem; margin-bottom: 1rem;">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-body">
                        <a href="<?php echo "http://localhost/FRAP_sd/feed.php"?>" ><i class="fa fa-backward"></i> Back to Newsfeed</a>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-body">
                        <h4 class="card-title"><b><?php echo $page_title;?></b></h4>
                        <h5 class="card-subtitle">by <?php echo $author;?> | <?php echo date("F j, Y g:i A ", strtotime($lastUpdated)) ;?></h5>
                        <br><p class="card-text"><?php echo $body ?></p>
                        <?php

                            $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                                                                v.filePath, v.title, v.versionNo, v.timeCreated, d.lastUpdated,
                                                                stat.statusName, s.stepNo, s.stepName, t.type,
                                                                pr.processName, v.versionId AS vid,
                                                                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                                                                FROM doc_versions v 
                                                                JOIN documents d ON v.documentId = d.documentId
                                                                JOIN post_ref_versions ref ON ref.versionId = v.versionId
                                                                JOIN employee e ON e.EMP_ID = v.authorId
                                                                JOIN doc_status stat ON stat.id = d.statusId 
                                                                JOIN doc_type t ON t.id = d.typeId
                                                                JOIN steps s ON s.id = d.stepId
                                                                JOIN process pr ON pr.id = s.processId
                                                                WHERE ref.postId = $postId");

                            if(!empty($rows)) {
                                echo '<div class="card" style="margin-top: 1rem;">
                                        <div class="card-header"><b>Document References</b></div>
                                        <div class="card-body">';
                                foreach ((array)$rows as $key => $row) {
                                    $title = $row['title'];
                                    $versionNo = $row['versionNo'];
                                    $originalAuthor = $row['firstAuthorName'];
                                    $currentAuthor = $row['authorName'];
                                    $processName = $row['processName'];
                                    $updatedOn = date("F j, Y g:i:s A ", strtotime($row['timeCreated']));
                                    $filePath = $row['filePath'];
                                    $fileName = $title.'_ver'.$versionNo.'_'.basename($filePath);
                                    echo '<div class="card" style="position: relative;">';
                                    echo '<input type="hidden" class="refDocuments" value="'.$row['vid'].'">';
                                    echo '<a style="text-align: left;" class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row['vid'] . '" aria-expanded="true" aria-controls="collapse' . $row['vid'] . '"><b>' . $title . ' </b><span class="badge">' . $versionNo . '</span></a>';
                                    echo '<div class="btn-group" style="position: absolute; right: 2px; top: 2px;" >';
                                    echo '<a class="btn fa fa-download"  href="'.$filePath.'" download="'.$fileName.'"></a>';
                                    echo '</div>';
                                    echo '<div id="collapse' . $row['vid'] . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
                                    echo '<div class="card-body">';
                                    echo 'Process: ' . $processName . '<br>';
                                    echo 'Created by: ' . $originalAuthor . '<br>';
                                    echo 'Modified by: ' . $currentAuthor . '<br>';
                                    echo 'on: <i>' . $updatedOn . '</i><br>';
                                    echo '</div></div></div>';
                                }
                                echo '</div></div>';
                            }
                            $rows = $crud->getData("SELECT pl.id, pl.typeId, pl.question 
                                              FROM polls pl WHERE pl.postId='$postId';");
                            if(!empty($rows)) {
                                foreach ((array)$rows as $key => $row) {
                                    $pollId = $row['id'];
                                    echo '<div class="card" style="margin-top: 1rem;">
                                                <div class="card-header"><b>Question: '.$row['question'].'</b></div>
                                                <div class="card-body">';
                                    $rowsIfAnswered = $crud->getData("SELECT pr.responderId, po.response 
                                                          FROM poll_options po JOIN poll_responses pr ON pr.responseId = po.optionId
                                                          WHERE po.pollId = '$pollId' AND pr.responderId = '$userId' ;");
                                    if(empty($rowsIfAnswered)) {
                                        $rows2 = $crud->getData("SELECT optionId, response FROM poll_options WHERE pollId = '$pollId';");
                                        if (!empty($rows)) {
                                            echo '<form id="submitResponse">';
                                            echo '<input type="hidden" name="userId" value="' . $userId . '">';
                                            foreach ((array)$rows2 as $key2 => $row2) {
                                                if (empty($rowsIfAnswered)) {
                                                    echo '<div class="form-check">
                                                          <input class="form-check-input" type="radio" name="responseId" value="' . $row2['optionId'] . '" checked>
                                                          <label class="form-check-label" for="response">' . $row2['response'] . '</label>
                                                        </div>';
                                                }
                                            }
                                            echo '<button type="button" class="btn btn-default btn-sm" onclick="respond(this,&quot;' . $pollId . '&quot;)">Submit Response</button>';
                                            echo '</form>';
                                        }
                                    }else{
                                        echo '<div><span class="badge badge-success">You responded "'.$rowsIfAnswered[0]['response'].'"</span></div>';
                                    }
                                    echo '<span id="loadResults">';
                                    $rows3 = $crud->getData("SELECT COUNT(DISTINCT(pr.responderId))  as responseCount, pr.responseId, po.response
                                        FROM facultyassocnew.poll_responses pr
                                        JOIN poll_options po ON pr.responseId = po.optionId
                                        JOIN polls p ON po.pollId = p.id WHERE p.id='$pollId' GROUP BY po.optionId;");
                                    $data = '';
                                    $total = 0;
                                    if(!empty($rows3)) {
                                        foreach ((array)$rows3 as $key3 => $row3) {
                                            $total = $total + (int) $row3['responseCount'];
                                        }
                                        foreach ((array)$rows3 as $key3 => $row3) {
                                            $percent = (int) $row3['responseCount'] / $total * 100;
                                            $percent = round($percent, 2);
                                            $data .= '<label>'.$row3['response'].'</label>';
                                            $data .= ' ('.$row3['responseCount'].' out of '.$total.' votes)';
                                            $data .= '<div class="progress">';
                                            $data .= '<div class="progress-bar progress-bar-success" role="progressbar" style="width: '.$percent.'%;" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100">'.$percent.'%</div>';
                                            $data .= '</div>';
                                        }
                                    }
                                    echo $data;
                                    echo '</span>';
                                    echo '</div></div>';
                                }
                            }
                        ?>
                    </div>
                </div>
                <?php
                    $query = "SELECT timeStamp, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) as name 
                    FROM post_views v JOIN employee e ON e.EMP_ID = v.viewerId 
                    WHERE typeId = 2 AND id = '$postId' GROUP BY v.viewerId ORDER BY timestamp DESC;";

                    $rows = $crud->getData($query);
                    $html = '';
                    $output = '';
                    $count = 0;
                    if(!empty($rows)){
                        foreach ((array) $rows as $key => $row) {
                            $html .= '<b>'.$row['name'].'</b> ('.date("F j, Y g:i:s A ", strtotime($row['timeStamp'])).')<br>';
                            $count++;
                        }
                        $output .= '<div class="card" style="margin-top: 1rem;">';
                        $output .= '<div class="card-body">';
                        $output .= '<a style="text-align: left" data-toggle="collapse" data-target="#collapse_seen" aria-expanded="true" aria-controls="collapse_seen">Seen by '.$count.' people.</a><br>';
                        $output .= '<div id="collapse_seen" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
                        $output .= $html;
                        $output .= '</div></div></div>';

                    }
                    echo $output;
                ?>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-body">
                        <button type="button" class="btn btn-primary fa fa-comment" data-toggle="modal" data-target="#myModal" name="addComment" id="addComment"> Comment </button>
                        <span id="comment_message"></span>
                        <div id="display_comment"></div>
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
            </div>
        </div>
    </div>


<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" id="comment_form">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="comment_name" id="comment_name" class="form-control" placeholder="Enter Name" value="<?php echo $userId; ?>"/>
                    </div>
                    <div class="form-group">
                        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="Enter Comment" rows="5"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <input type="hidden" name="comment_id" id="comment_id" value="0" />
                        <input type="hidden" name="post_id" id="post_id" value="<?php echo $postId?>" />
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="submit" id="submit" class="btn btn-info" value="Submit"/>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>
<script>
    $(document).ready(function(){

        $('.reply').click(function(){
        });

        let postId = "<?php echo $postId?>";

        $('#comment_form').on('submit', function(event){
            event.preventDefault();
            $('#myModal').modal('toggle');
            var form_data = $(this).serialize();
            $.ajax({
                url:"CMS_AJAX_AddComment.php",
                method:"POST",
                data:form_data,
                dataType:"JSON",
                success:function(data)
                {
                    if(data.error != '')
                    {
                        $('#comment_form')[0].reset();
                        $('#comment_message').html(data.error);
                        $('#comment_id').val('0');
                        load_comment(postId);
                    }
                }
            })
        });

        var dlg=$('#register').dialog({
            title: 'Register for LifeStor',
            resizable: true,
            autoOpen:false,
            modal: true,
            hide: 'fade',
            width:350,
            height:275
        });


        $('#reg_link').click(function(e) {
            e.preventDefault();
            dlg.load('register.php', function(){
                dlg.dialog('open');
            });
        });


        setInterval(function() {
            load_comment(postId);
            load_views(postId);
        }, 1000); //5

        function load_comment(postId)
        {
            $.ajax({
                url:"CMS_AJAX_FetchComments.php",
                method:"POST",
                data:{postId: postId},
                success:function(data)
                {
                    $('#display_comment').html(data);
                }
            })
        }

        // function load_views(postId) {
        //     $.ajax({
        //         url:"CMS_AJAX_FetchViewers.php",
        //         method:"POST",
        //         data:{postId: postId},
        //         success:function(data)
        //         {
        //             $('#display_view').html(data);
        //         }
        //     })
        // }


        $(document).on('click', '.reply', function(){
            var comment_id = $(this).attr("id");
            $('#comment_id').val(comment_id);
            $('#comment_name').focus();
        });

        //$('#loadResults').hide();
        loadResults($('#loadResults'), '<?php echo $pollId; ?>');


    });
    function loadResults(element,pollId){
        setInterval(function(){
            $.ajax({
                url:"read_AJAX_LoadResults.php",
                method:"POST",
                data:{pollId: pollId},
                success:function(data)
                {
                    $(element).html(data);
                }
            });
        }, 5000);
    }
    function respond(element, pollId){
        var form = $(element).closest('form').serialize();
        var span = $(element).closest('div.card-body').find('span.loadResults');
        $(element).closest('form').remove();
        $.ajax({
            url:"read_AJAX_Respond.php",
            method:"POST",
            data:form,
            dataType: "JSON",
            success:function(data)
            {
                //loadResults(span,pollId);
            }
        });
    }
    function preview(){
        $('#register').load('register.php');
    }
</script>
<?php include 'GLOBAL_FOOTER.php'; ?>
