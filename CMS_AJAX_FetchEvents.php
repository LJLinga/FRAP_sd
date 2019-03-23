<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:34 PM
 */


include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

$query = "SELECT * FROM facultyassocnew.events ORDER BY startTime DESC;";
$rows = $crud->getData($query);
$data = [];
foreach ((array) $rows as $key => $row) {
    $data[] =  array(
        'event' => '<b>'.$row['title'].'</b>',
        'start' => date("F j, Y g:i:s A ", strtotime($row['startTime'])),
        'end' => date("F j, Y g:i:s A ", strtotime($row['endTime'])),
        'action'=> '<button type="button" class="btn btn-default"><a href="'.$row['GOOGLE_EVENTLINK'].'" target="_blank"> Google </a></button>'
    );

}
echo json_encode($data);