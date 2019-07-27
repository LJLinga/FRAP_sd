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
        $query = "SELECT * FROM facultyassocnew.events ORDER BY startTime DESC;";
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
                'status' => $status,
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
    }
}

