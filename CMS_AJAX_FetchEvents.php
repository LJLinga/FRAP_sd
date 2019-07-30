<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:34 PM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['requestType'])){
    $requestType = $_POST['requestType'];
    if($requestType == 'ALL'){
        $query = "SELECT e.*, p.permalink FROM facultyassocnew.events e LEFT JOIN post_ref_events ref ON e.id = ref.eventId LEFT JOIN posts p ON ref.postId = p.id ORDER BY startTime DESC;";
        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $status = 'CONFIRMED';
            if($row['statusId'] == '2'){
                $status = 'CANCELLED';
            }
            $permalink = $row['permalink'];
            $button = '';
            if($permalink != ''){
                $button = '<a href="http://localhost/FRAP_sd/read.php?pl='.$permalink.'" target="_blank" class="btn btn-default">View Post</a>';
            }else{
                $button = 'NONE';
            }
            $data[] =  array(
                'event' => '<b>'.$row['title'].'</b>',
                'start' => date("F j, Y g:i:s A ", strtotime($row['startTime'])),
                'end' => date("F j, Y g:i:s A ", strtotime($row['endTime'])),
                'status' => $status,
                'permalink' => $button,
                'added_by' => $crud->getUserName($row['posterId']),
                'added_on' => $crud->friendlyDate($row['firstCreated']),
                'action'=> '<a class="btn btn-default" href="'.$row['GOOGLE_EVENTLINK'].'" target="_blank" title="View in Google Calendar"><i class="fa fa-google fa-calendar"></i></a>'
            );
        }
        echo json_encode($data);
    }else if($requestType == 'UPCOMING'){
        $query = "SELECT * FROM facultyassocnew.events WHERE startTime > NOW() AND statusId = 1 ORDER BY startTime DESC;";
        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $status = 'CONFIRMED';
            if($row['statusId'] == '2'){
                $status = 'CANCELLED';
            }
            $data[] =  array(
                'event' => '<b>'.$row['title'].'</b>',
                'start' => date("F j, Y g:i:s A ", strtotime($row['startTime'])),
                'end' => date("F j, Y g:i:s A ", strtotime($row['endTime'])),
                'action'=> '<button type="button" class="btn btn-default"><a href="'.$row['GOOGLE_EVENTLINK'].'" target="_blank"> Google </a></button>'
            );
        }
        echo json_encode($data);
    }else if($requestType == 'POST_ATTACHED_EVENTS' && isset($_POST['postId'])){
        $postId = $_POST['postId'];
        $query = "SELECT * FROM events e JOIN post_ref_events ref ON e.id = ref.eventId WHERE ref.postId = '$postId' ORDER BY startTime DESC;";
        $rows = $crud->getData($query);
        $data = [];
        foreach ((array) $rows as $key => $row) {
            $status = 'CONFIRMED';
            if($row['statusId'] == '2'){
                $status = 'CANCELLED';
            }
            $data[] =  array(
                'event' => '<div class="card">
                                <div class="card-body"><small>
                                    Event: '.$row['title'].'
                                    <br>Start: '.$crud->friendlyDate($row['startTime']).'
                                    <br>End: '.$crud->friendlyDate($row['endTime']).'
                                    <br>Created by: '.$crud->getUserName($row['posterId']).'
                                    <br>Created on: '.$crud->friendlyDate($row['firstCreated']).'
                                    <br><a href="'.$row['GOOGLE_EVENTLINK'].'" target="_blank" title="View in Google Calendar" class="btn btn-info btn-sm"><i class="fa fa-calendar"> View on GCal</i></a>
                                </small>
                                </div>
                            </div>
                            '
            );
        }
        echo json_encode($data);
    }else if($requestType == 'ADD_POST_EVENT' && isset($_POST['postId'])){
        $postId = $_POST['postId'];
        $title = $crud->escape_string($_POST['event_title']);
        $body = $crud->escape_string($_POST['event_content']);
        $startTime =  $_POST['event_start'];
        $endTime =  $_POST['event_end'];
        $userId =  $_POST['userId'];

        $email_array = [];

        if(isset($_POST['toAddEmails'])){
            $email_array = $_POST['toAddEmails'];
        }

        $startTime = preg_replace('/\s+/', 'T', $startTime);
        $endTime = preg_replace('/\s+/', 'T', $endTime);

        $bool = $crud->insertCalendarEvent($userId, $title, $body, $startTime, $endTime, $email_array, 'DAILY','1');
        $bool2 = $crud->executeGetKey("INSERT INTO post_ref_events (postId, eventId) VALUES('$postId','$bool')");
        if($bool2 != false){
            echo 'success';
        }else{
            echo $bool;
        }
    }
}
