<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/21/2019
 * Time: 11:45 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();
$userId = $_SESSION['idnum'];


if(isset($_POST['requestType'])){
    $requestType = $_POST['requestType'];
    if($requestType == 'ANSWERED_POLLS' && isset($_POST['postId'])){
        $postId = $_POST['postId'];
        $data = '';

        $query = "SELECT pl.id AS pollId, pl.question, ps.authorId FROM polls pl 
                    JOIN posts ps ON pl.postId = ps.id
                    WHERE (pl.id IN (SELECT pl.id FROM polls pl
                    JOIN poll_options po ON pl.id = po.pollId
                    JOIN poll_responses res ON po.optionId = res.responseId
                    WHERE res.responderId = '$userId') OR ps.authorId = '$userId')
                    AND pl.postId = '$postId'
                    GROUP BY pl.id;";

        $rows = $crud->getData($query);

        if(!empty($rows)) {
            foreach ((array)$rows as $key => $row) {
                $pollId = $row['pollId'];
                $data='<div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Question: </strong>'.$row['question'].'
                        </div>
                        <div class="panel-body">';
                if($row['authorId'] == $userId){
                    $data.='<div class="alert alert-warning"><small>You cannot vote in your own poll.</small></div>';
                }else{
                    $query = "SELECT po.response, res.timeStamp FROM poll_responses res 
                                    JOIN poll_options po ON res.responseId = po.optionId 
                                    JOIN polls p ON po.pollId = p.id
                                    WHERE res.responderId = '$userId' AND p.id = '$pollId LIMIT 1'";
                    $rows3 = $crud->getData($query);
                    if(!empty($rows3)){
                        $data.='<div class="alert alert-info"><small>You voted <strong>"'.$rows3[0]['response'].'"</strong> on <i>'.$crud->friendlyDate($rows3[0]['timeStamp']).'</i></small></div>';
                    }
                }
                $rows2 = $crud->getData("SELECT COUNT(DISTINCT(pr.responderId)) as responseCount, po.optionId, po.response 
                                    FROM facultyassocnew.poll_responses pr
                                    RIGHT JOIN poll_options po ON pr.responseId = po.optionId 
                                    JOIN polls p ON po.pollId = p.id WHERE p.id='$pollId' GROUP BY po.optionId;");
                $total = 0;
                if(!empty($rows2)){
                    foreach ((array) $rows2 as $key2 => $row2){
                        $total = $total + (int) $row2['responseCount'];
                    }
                    foreach ((array)$rows2 as $key2 => $row2) {
                        $optionId = $row2['optionId'];
                        $percent = 0;
                        if($row2['responseCount'] !== '0'){
                            $percent = (int) $row2['responseCount'] / $total * 100;
                        }
                        $percent = round($percent, 2);
                        $data .= '<label>'.$row2['response'].'</label>';
                        $data .= ' ('.$row2['responseCount'].' out of '.$total.' votes)';
                        $rows4 = $crud->getData("SELECT DISTINCT(res.responderId), res.timeStamp  FROM poll_responses res WHERE res.responseId = '$optionId'");
                        if(!empty($rows4)){
                            $data.= ' <a data-toggle="modal" data-target="#modalVotes'.$optionId.'" onclick="pauseResults()">Show votes</a>';
                            $data.='<div id="modalVotes'.$optionId.'" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    People who voted <strong>"'.$row2['response'].'"</strong> in the question <strong>"'.$row['question'].'"</strong>
                                                </div>
                                                <div class="modal-body" style="max-height: 50rem; overflow-y: auto">';
                                                    foreach((array) $rows4 AS $key4 => $row4){
                                                        $data.='<strong>'.$crud->getUserName($row4['responderId']).'</strong> on <i>'.$crud->friendlyDate($row4['timeStamp']).'</i><br>';
                                                    }
                                                $data.='</div>
                                                <div class="modal-footer">
                                                    <div class="form-group">
                                                        <button type="button" class="btn btn-default" onclick="resumeResults()" data-dismiss="modal">Close</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                        }
                        $data .= '<div class="progress">';
                        $data .= '<div class="progress-bar progress-bar-success" role="progressbar" style="width: '.$percent.'%;" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100">'.$percent.'%</div>';
                        $data .= '</div>';


                    }
                }else{
                    $data.='<div class="alert alert-info"> No responses to show</div>';
                }
                $data.= '</div>
                        </div>
                        ';
            }
        }

        echo $data;
    }else if($requestType == 'POST_SEEN' && isset($_POST['postId'])){
        $postId = $_POST['postId'];
        $query = "SELECT timeStamp, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) as name 
                    FROM post_views v JOIN employee e ON e.EMP_ID = v.viewerId 
                    WHERE typeId = 2 AND id = '$postId' GROUP BY v.viewerId ORDER BY timestamp DESC;";

        $rows = $crud->getData($query);
        $html = '';
        $output = '';
        $count = 0;
        if(!empty($rows)){
            foreach ((array) $rows as $key => $row) {
                $html .= '<strong>'.$row['name'].'</strong> on <i>'.date("F j, Y g:i:s A ", strtotime($row['timeStamp'])).'</i><br>';
                $count++;
            }
            $output .= '<div class="panel panel-default" style="margin-top: 1rem;">';
            $output .= '<div class="panel-body">';
            $output .= '<a style="text-align: left" data-toggle="collapse" data-target="#collapse_seen" aria-expanded="true" aria-controls="collapse_seen" onclick="toggleSeen()">Seen by '.$count.' people.</a><br>';
            $output .= '<div id="collapse_seen" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">';
            $output .= $html;
            $output .= '</div></div></div>';

        }
        echo $output;
    }

}

