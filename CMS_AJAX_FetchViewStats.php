<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/23/2019
 * Time: 5:31 PM
 */

require 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();

if(isset($_POST['mode']) && isset($_POST['option'])){
    $mode = $_POST['mode'];
    $option = $_POST['option'];
    $type = '1';

    if($mode == 'views'){
        $type = '2';
    }


    $extra = '';
    $groupBy = '';
    $interval = '';
    $start='';
    $end='';

    if(isset($_POST['id'])){
        $id = $_POST['id'];
        if(!empty($id) && $id != '0'){
            $extra = "AND post_views.id = '$id'";
        }
    }

    if($option == '1'){
        $interval = "AND timeStamp >= NOW() + INTERVAL -24 HOUR
				  AND timeStamp <  NOW()";
        $groupBy = "GROUP BY HOUR(timeStamp)";
    }else if($option == '2'){
        $interval = "AND timeStamp >= NOW() + INTERVAL -7 DAY
				  AND timeStamp <  NOW()";
        $groupBy = "GROUP BY DATE(timeStamp)";
    }else if($option == '3'){
        $interval = "AND timeStamp >= NOW() + INTERVAL -1 MONTH
				  AND timeStamp < NOW()";
        $groupBy = "GROUP BY DATE(timeStamp)";
    }else if($option == '4'){
        $interval = "AND timeStamp >= NOW() + INTERVAL -1 YEAR
				  AND timeStamp <  NOW()";
        $groupBy = "GROUP BY MONTH(timeStamp)";
    }else if($option == '5'){
        $interval = "AND timeStamp >= NOW() + INTERVAL -5 YEAR
				  AND timeStamp <  NOW()";
        $groupBy = "GROUP BY YEAR(timeStamp)";
    }else if($option == '6'){
        if(isset($_POST['start']) && isset($_POST['end'])){
            $start = $_POST['start'];
            $end = $_POST['end'];
            $diff=date_diff(date_create($start),date_create($end));
            $diff=$diff->format('%a');
            if($diff<=1 || $start == $end){
                $groupBy = "GROUP BY HOUR(timeStamp)";
            }else if($diff>1 && $diff<=31) {
                $groupBy = "GROUP BY DATE(timeStamp)";
            } else if ($diff>31 && $diff<=365){
                $groupBy = "GROUP BY MONTH(timeStamp)";
            } else if ($diff>365){
                $groupBy = "GROUP BY YEAR(timeStamp)";
            }
        }
        $interval = "AND timeStamp BETWEEN ('$start') AND ('$end')";
    }


    $query = "SELECT COUNT(id) AS view_count, COUNT(DISTINCT viewerId) AS unique_count, timeStamp as 'xmin'
              FROM facultyassocnew.post_views WHERE post_views.typeId = '$type' 
              $extra
              $interval
              $groupBy
              ORDER BY timeStamp ASC;";

    $rows = $crud->getData($query);
    $temp=[];
    foreach ((array) $rows as $key => $row) {
        $temp[] =  array(
            'xmin' => $row['xmin'],
            'count' => $row['view_count'],
            'unique_count' => $row['unique_count']
        );
    }
    echo json_encode($temp);
    // DO NOT FORGET TO ECHO
    exit;
}
?>