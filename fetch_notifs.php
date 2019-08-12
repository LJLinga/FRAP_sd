<?php

include('mysql_connect_FA.php');

if(isset($_POST['idnum'])){

    //$lastTimeStamp = $_POST['date'];
    $query ='';

    if($_SESSION['FRAP_ROLE'] == 1){
        $query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']} ORDER BY TXN_ID  ";
    }else{
        $query = "SELECT * FROM txn_reference ORDER BY TXN_ID  DESC LIMIT 5 ";
    }

    $result = mysqli_query($dbc, $query);
    $output = '';

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_array($result))
        {
            if($row['SERVICE_ID'] == 4){
                $output .= '
                 <a href="MEMBER%20FALP%20activity.php">
                 <div class="card">
                    <div class="card-header">
                        <span class="label label-info">FALP</span>
                    </div>
                    <div class="card-body">
                     '.$row["TXN_DESC"].' 
                    </div>
                    <div class="card-footer">
                        '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                    </div>
                </div>
                </a>
                <br><br>
                  ';

            }else if($row['SERVICE_ID'] == 2){
                $output .= '
                 <a href="MEMBER%20HA%20summary.php">
                 <div class="card">
                    <div class="card-header">
                        <span class="label label-info">FALP</span>
                    </div>
                    <div class="card-body">
                     '.$row["TXN_DESC"].' 
                    </div>
                    <div class="card-footer">
                        '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
                    </div>
                </div>
                </a>
                <br><br>
                 ';
            }

            //$lastTimeStamp = $row["TXN_DATE"];
        }
        /*
        $output .= '
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a id="loadmore" href="'."http://localhost/Thesis/FRAP_sd/FRAP_sd/GLOBAL_ALL_NOTIFS.php?lastTimeStamp=".$lastTimeStamp.'" >Load More Notifications</a>
                    </div>
                </div>
            </div>
        ';
        */

    }
    else{
        $output .= '
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <a id="refresh" href="http://localhost/Thesis/FRAP_sd/FRAP_sd/FRAP_ALL_NOTIFS.php">No More Notifcations. Refresh.</a>
                    </div>
                </div>
            </div>';
    }



    $status_query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']}";
    $result_query = mysqli_query($dbc, $status_query);
    $count = mysqli_num_rows($result_query);
    $data = array(
        'notification' => $output,
        'unseen_notification'  => $count,
        'date'
    );
    echo json_encode($data);
}

?>