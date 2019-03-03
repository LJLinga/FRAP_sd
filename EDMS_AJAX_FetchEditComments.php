<?php

$documentId = $_POST['documentId'];

$connect = new PDO('mysql:host=localhost;dbname=facultyassocnew', 'root', '1234');

$query = "
SELECT c.id, c.commenterId, c.parentCommentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS commenterName, 
c.content, c.timePosted FROM doc_comments c JOIN employee e WHERE c.commenterId = e.EMP_ID
AND c.parentCommentId = '0' AND c.documentId = '$documentId'
ORDER BY id DESC
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();
$output = '';
foreach($result as $row)
{
    $output .= '
 <div class="card" style="margin-top: 1rem; font-size: small">
  <div class="card-header" style="font-size: x-small">by <b>'.$row["commenterName"].'</b> on <i>'.date("F j, Y g:i A ", strtotime($row['timePosted'])).'</i></div>
  <div class="card-body">
    <div class="card-text">'.$row["content"].'</div>
    <div class="card-link"><a type="button" class="btn btn-sm fa fa-reply reply" data-toggle="modal" data-target="#myModal" id="'.$row["id"].'"> Reply </a></div>
    </div>
    </div>
 ';
    $output .= get_reply_comment($connect, $row["id"]);
}

echo $output;

function get_reply_comment($connect, $parent_id = 0, $marginleft = 0)
{

    $query = "
SELECT c.id, c.commenterId, c.parentCommentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS commenterName, 
c.content, c.timePosted FROM doc_comments c JOIN employee e WHERE c.commenterId = e.EMP_ID
AND c.parentCommentId = '$parent_id'
ORDER BY id DESC
";
    $output = '';
    $statement = $connect->prepare($query);
    $statement->execute();
    $result = $statement->fetchAll();
    $count = $statement->rowCount();
    if($parent_id == 0)
    {
        $marginleft = 0;
    }
    else
    {
        $marginleft = 48;
    }
    if($count > 0)
    {
        foreach($result as $row)
        {
            $output .= '
   <div class="card" style="margin-left:'.$marginleft.'px; margin-top: 1rem; font-size: small">
    <div class="card-header" style="font-size: x-small">by <b>'.$row["commenterName"].'</b> on <i>'.date("F j, Y g:i A ", strtotime($row['timePosted'])).'</i></div>
    
    <div class="card-body">
    <div class="card-text">'.$row["content"].'</div>
    <div class="card-link"><a type="button" class="btn btn-sm fa fa-reply reply" data-toggle="modal" data-target="#myModal" id="'.$row["id"].'"> Reply </a></div>
    </div>
    
    </div>
   ';
            $output .= get_reply_comment($connect, $row["id"], $marginleft);
        }
    }
    return $output;
}

?>