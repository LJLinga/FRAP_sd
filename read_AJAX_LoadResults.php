<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/21/2019
 * Time: 11:45 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['pollId'])){
    $pollId = $_POST['pollId'];
    $rows = $crud->getData("SELECT COUNT(DISTINCT(pr.responderId))  as responseCount, pr.responseId, po.response FROM facultyassocnew.poll_responses pr
                                    JOIN poll_options po ON pr.responseId = po.optionId 
                                    JOIN polls p ON po.pollId = p.id WHERE p.id='$pollId' GROUP BY po.optionId;");
    $data = '';
    $total = 0;
    if(!empty($rows)) {
        foreach ((array)$rows as $key => $row) {
            $total = $total + (int) $row['responseCount'];
        }
        foreach ((array)$rows as $key => $row) {
            $percent = (int) $row['responseCount'] / $total * 100;
            $percent = round($percent, 2);
            $data .= '<label>'.$row['response'].'</label>';
            $data .= ' ('.$row['responseCount'].' out of '.$total.')';
            $data .= '<div class="progress">';
            $data .= '<div class="progress-bar progress-bar-success" role="progressbar" style="width: '.$percent.'%;" aria-valuenow="'.$percent.'" aria-valuemin="0" aria-valuemax="100">'.$percent.'%</div>';
            $data .= '</div>';
        }

    }
    echo $data;
}

