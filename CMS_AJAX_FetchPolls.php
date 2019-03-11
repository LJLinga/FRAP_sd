<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/24/2019
 * Time: 4:55 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['userId'])){

    $userId = $_POST['userId'];
    //$cmsRole = $_POST['cmsRole'];

    $query = "SELECT p.id, p.question, p.lastUpdated, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS author, 
                (SELECT COUNT(DISTINCT respondentId) FROM poll_responses r JOIN poll_options o ON r.optionId = o.id JOIN polls p ON p.id = o.pollId WHERE p.id=o.pollId) AS count
                FROM polls p JOIN employee e ON p.authorId = e.EMP_ID;";
    $rows = $crud->getData($query);
    $data = [];
    if(!empty($rows)){
        foreach ((array) $rows as $key => $row) {
            $data[] =  array(
                'question' => $row['question'],
                'lastUpdated' => date("F j, Y g:i A ", strtotime($row['lastUpdated'])),
                'author' => $row['author'],
                'count' => $row['count'],
                'actions'=> '<a href="CMS_EditPoll.php?pollId='.$row['id'].'" class="btn btn-default">Edit</a>'
            );
        }
        echo json_encode($data);
        exit;
    }else{
        echo 'empty';
    }


}else{
    echo 'userId or cmsRole not set';
}



?>