<?php
session_start();
include('mysql_connect_FA.php');

if(isset($_POST['idnum'])){

    $query = "";

    if($_SESSION['FRAP_ROLE'] == 1){
        $query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']} ORDER BY TXN_ID  DESC LIMIT 5 ";
    }else{
        $query = "SELECT * FROM txn_reference ORDER BY TXN_ID  DESC LIMIT 5 ";
    }

//    $query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']} ORDER BY TXN_ID  DESC LIMIT 5 ";
    $result = mysqli_query($dbc, $query);
    $outputs = '';

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result))
        {
            //if statements here, to know if they are
            if($row['SERVICE_ID'] == 4 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 1){
                $outputs .= '
                  <li>
                  <span class="label label-info">Loans</span>
                    <a href="ADMIN%20FALP%20applications.php">'."New FALP Application!".' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }else  if($row['SERVICE_ID'] == 2 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 1){
                $outputs .= '
                  <li>
                  <span class="label label-info">Health Aid</span>
                    <a href="ADMIN%20HEALTHAID%20applications.php">'."New Health Aid Application!".' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }else if($row['SERVICE_ID'] == 4 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 2){
                $outputs .= '
                  <li>
                  <span class="label label-info">Loans</span>
                    <a href="MEMBER%20FALP%20summary.php">'."Sucessfully Deducted".' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }else if($row['SERVICE_ID'] == 2 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 2){
                $outputs .= '
                  <li>
                  <span class="label label-info">Loans</span>
                    <a href="MEMBER%20FALP%20summary.php">'.$row["TXN_DESC"].' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }else if($row['SERVICE_ID'] == 4 ){
                $outputs .= '
                  <li>
                  <span class="label label-info">Loans</span>
                    <a href="MEMBER%20FALP%20summary.php">'.$row["TXN_DESC"].' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }else  if($row['SERVICE_ID'] == 2 ){
                $outputs .= '
                  <li>
                  <span class="label label-info">Health Aid</span>
                    <a href="MEMBER%20HA%20summary.php">'.$row["TXN_DESC"].' </a>
                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                  </li><br>
                  ';
            }


        }

    }
    else{
        $outputs .= '<li><a href="#" class="text-bold text-italic">No Notifications Found</a></li>';
    }

    $status_query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']}";
    $result_query = mysqli_query($dbc, $status_query);
    $counts = mysqli_num_rows($result_query);
    $data = array(
        'notification' => $outputs,
        'unseen_notification'  => $counts
    );
    echo json_encode($data);
}

?>