<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/31/2018
 * Time: 10:57 AM
 **/

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];
//$cmsRole = $_SESSION['CMS_ROLE'];

if(!empty($_GET['permalink'])){

    $permalink = $_GET['permalink'];

    $rows = $crud->getData("SELECT p.authorId, p.publisherId, p.statusId
                            FROM posts p
                            WHERE p.permalink = '$permalink'");

    foreach ((array) $rows as $key => $row) {
        $authorId = $row['authorId'];
        $publisherId = $row['publisherId'];
        $statusId = $row['statusId'];
    }

    if($statusId!=3){
        if($authorId!=$userId && $cmsRole!=3){
            header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_PostsFeed.php");
        }
    }

    $rows = $crud->getData("SELECT 
            p.title,
            CONCAT(u.firstName,' ', u.lastName) AS author,
            p.body,
            p.statusId,
            p.lastUpdated
        FROM
            employee u
                JOIN
            posts p ON p.authorId = u.EMP_ID
        WHERE
            p.permalink = '$permalink'   ");

    foreach ((array) $rows as $key => $row) {
        $title = $row['title'];
        $body = $row['body'];
        $author = $row['author'];
        $lastUpdated = $row['lastUpdated'];
        $statusId = $row['statusId'];
    }


}else{
    header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_PostsDashboard.php");
}

$page_title = $title;
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';

?>
<script>

</script>
<div id="container">
    <div class="container-fluid">
        <div class="row h-100 justify-content-center align-items-center">
            <div class="col-lg-6" style="top: 1rem; bottom: 1rem;">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title"><b><?php echo $page_title;?></b></h3>
                        <h5 class="card-subtitle">by <?php echo $author;?> | <?php echo date("F j, Y g:i A ", strtotime($lastUpdated)) ;?></h5>
                    </div>
                    <div class="card-body" style="overflow: hidden";>
                        <p class="card-text"><?php echo $body ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'GLOBAL_FOOTER.php'; ?>
