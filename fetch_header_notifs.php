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
            //4 - FAP Application 2 - HA 1 - Membership
            //
            if($row['SERVICE_ID'] == 4 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 1){ // basically this one

//                $outputs .= '
//                    <a href="ADMIN%20FALP%20applications.php">
//                  <li>
//                  <span class="label label-info">Loans</span>
//                    '."New FAP Application!".' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="ADMIN%20FALP%20applications.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '        New FAP Application!     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else  if($row['SERVICE_ID'] == 1 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 1){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Health Aid</span>
//                    <a href="ADMIN%20HEALTHAID%20applications.php">'."New Health Aid Application!".' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="ADMIN%20MEMBERSHIP%20applications.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '        New Membership Application!     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else  if($row['SERVICE_ID'] == 2 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 1){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Health Aid</span>
//                    <a href="ADMIN%20HEALTHAID%20applications.php">'."New Health Aid Application!".' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="ADMIN%20HEALTHAID%20applications.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '        New Health Aid Application!     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else if($row['SERVICE_ID'] == 4 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 2){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Loans</span>
//                    <a href="MEMBER%20FALP%20summary.php">'."Sucessfully Deducted".' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="ADMIN%20DREPORT%20general.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '        Sucessfully Deducted for Loans.     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else if($row['SERVICE_ID'] == 2 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 2){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Loans</span>
//                    <a href="MEMBER%20FALP%20summary.php">'.$row["TXN_DESC"].' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';
                $outputs .= '<a href="ADMIN%20DREPORT%20general.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '        Sucessfully Deducted for Health Aid.     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';


            }else if($row['SERVICE_ID'] == 1 && $row['MEMBER_ID'] != $_SESSION['idnum'] && $row['TXN_TYPE'] == 2){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Loans</span>
//                    <a href="MEMBER%20FALP%20summary.php">'."Sucessfully Deducted".' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="ADMIN%20DREPORT%20general.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= '      Successfully Deducted for Membership.     ';
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else if($row['SERVICE_ID'] == 4 ){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Loans</span>
//                    <a href="MEMBER%20FALP%20summary.php">'.$row["TXN_DESC"].' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="MEMBER%20FALP%20summary.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= $row["TXN_DESC"];
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';

            }else  if($row['SERVICE_ID'] == 2 ){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Health Aid</span>
//                    <a href="MEMBER%20HA%20summary.php">'.$row["TXN_DESC"].' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="MEMBER%20HA%20summary.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= $row["TXN_DESC"];
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';
            }else  if($row['SERVICE_ID'] == 6 ){
//                $outputs .= '
//                  <li>
//                  <span class="label label-info">Health Aid</span>
//                    <a href="MEMBER%20HA%20summary.php">'.$row["TXN_DESC"].' </a>
//                    '.date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"])).'
//                  </li><br>
//                  ';

                $outputs .= '<a href="MEMBER%20LIFETIME%20form.php">';
                $outputs .= '<li>';
                $outputs .= '<span class="label label-info">Services</span>';
                $outputs .= $row["TXN_DESC"];
                $outputs .=  date("F j, Y g:i:s A ", strtotime($row["TXN_DATE"]));
                $outputs .= '</li>';
                $outputs .= '</a>';
            }


        }

    }
    else{
        $outputs .= '<li><a href="#" class="text-bold text-italic">No Notifications Found</a></li>';
    }

    if($_SESSION['FRAP_ROLE'] == 1){
        $status_query = "SELECT * FROM txn_reference WHERE MEMBER_ID = {$_POST['idnum']} && SEEN = 1";
    }else{ // meaning this d00ds a goddamn admin.
        $status_query = "SELECT * FROM txn_reference WHERE SEEN = 1";
    }

    $result_query = mysqli_query($dbc, $status_query);
    $counts = mysqli_num_rows($result_query);
    $data = array(
        'notification' => $outputs,
        'unseen_notification'  => $counts
    );
    echo json_encode($data);
}

?>