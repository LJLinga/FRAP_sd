<?php
require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_CLASS_CRUD.php';
$crud = new GLOBAL_CLASS_CRUD();
include 'GLOBAL_USER_TYPE_CHECKING.php';




$page_title = 'GLOBAL NOTIFS ';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';

if(empty($_GET['lastTimeStamp'])){
    $lastTimeStamp = $crud->getData("SELECT CURRENT_TIMESTAMP() AS time");
    $lastTimeStamp = $lastTimeStamp[0]['time'];
}else{
    $lastTimeStamp = $_GET['lastTimeStamp'];
}
?>
<div class="row">

    <div class="col-lg-12">
        <h1 class="page-header">All Notifications</h1>
    </div>
    <div class="col-lg-12">
        <div class="custom">
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
// updating the view with notifications using ajax

        let temp = "<?php echo $_SESSION['idnum'] ?>";
        //let date = "<?php echo $lastTimeStamp ?>";

        function load_all_notifs(idnum)
        {
            $.ajax({
                url:"fetch_notifs.php",
                method:"POST",
                data:{
                    idnum:idnum,
                    //date: date
                },
                dataType:"json",
                success:function(data)
                {
                    $('.custom').html(data.notification);
                    if(data.unseen_notification > 0)
                    {
                        $('.count').html(data.unseen_notification);
                    }
                    $
                }
            });
        }

        setInterval(function(){
            load_all_notifs(temp); // this will run after every 1 second
        }, 1000);


    });
</script>






<?php include 'GLOBAL_FOOTER.php' ?>

