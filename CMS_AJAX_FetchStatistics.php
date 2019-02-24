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
    $type = '';

    if($mode == 'views'){
        $type = '2';
    }else if ($mode == 'previews'){
        $type = '1';
    }

    if($option == '1'){
        $query = "SELECT COUNT(id) AS view_count, HOUR(timeStamp) as 'xmin'
                  FROM facultyassocnew.post_views WHERE post_views.typeId = '$type'
                  AND DATE(timeStamp) = CURDATE()
                  GROUP BY HOUR(timeStamp) ORDER BY timeStamp ASC;";
    }else if($option == '2'){
        $query = "SELECT COUNT(id) AS view_count, DAY(timeStamp) as 'xmin' 
                  FROM facultyassocnew.post_views WHERE post_views.typeId = '2' 
                  AND timeStamp >= NOW() + INTERVAL -7 DAY
				  AND timeStamp <  NOW() + INTERVAL  0 DAY
                  GROUP BY DAY(timeStamp) ASC;";
    }else if($option == '3'){
        $query = "SELECT COUNT(id) AS view_count, DAY(timeStamp) as 'xmin'
                  FROM facultyassocnew.post_views WHERE post_views.typeId = '2'
                  AND timeStamp >= NOW() + INTERVAL -1 MONTH
				  AND timeStamp < NOW() + INTERVAL 0 MONTH
                  GROUP BY DAY(timeStamp) ORDER BY timeStamp ASC;";
    }else if($option == '4'){
        $query = "SELECT COUNT(id) AS view_count, DATE_FORMAT(timeStamp, '%Y %b') as 'xmin' 
                  FROM facultyassocnew.post_views WHERE post_views.typeId = '$type' 
                  AND timeStamp >= NOW() + INTERVAL -1 YEAR
				  AND timeStamp <  NOW() + INTERVAL  0 YEAR
                  GROUP BY MONTH(timeStamp) ORDER BY timeStamp ASC;";
    }else if($option == '5'){
        $query = "SELECT COUNT(id) AS view_count, YEAR(timeStamp) as 'xmin' 
                  FROM facultyassocnew.post_views WHERE post_views.typeId = '$type' 
                  AND timeStamp >= NOW() + INTERVAL -5 YEAR
				  AND timeStamp <  NOW() + INTERVAL  0 YEAR
                  GROUP BY YEAR(timeStamp) ORDER BY timeStamp ASC;";
    }

    $rows = $crud->getData($query);
    $temp=[];
    foreach ((array) $rows as $key => $row) {
        $temp[] =  array(
            'xmin' => $row['xmin'],
            'count' => $row['view_count']
        );
    }
    echo json_encode($temp);
    // DO NOT FORGET TO ECHO
    exit;
}
?>