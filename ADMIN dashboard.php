<?php

    require_once ("mysql_connect_FA.php");
    include 'GLOBAL_CLASS_CRUD.php';
    $crud = new GLOBAL_CLASS_CRUD();
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';



    $userId=$_SESSION['idnum'];

    if(empty($_GET['lastTimeStamp'])){
        $lastTimeStamp = $crud->getData("SELECT CURRENT_TIMESTAMP() AS time");
        $lastTimeStamp = $lastTimeStamp[0]['time'];
    }else{
        $lastTimeStamp = $_GET['lastTimeStamp'];
    }

    $page_title = 'Admin - Dashboard';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';


?>

    <style>
        @media screen and (min-width: 1200px) {
            #calendarColumn{
                position: fixed;
                right:1rem;
            }
        }
        @media screen and (max-width: 1199px) {
            #calendarColumn{
                position: relative;
            }
        }
        .card {
            font-family: "Verdana", Georgia, Serif;
            font-size: 12px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="column col-lg-2" style="margin-top: 2rem; margin-bottom: 2rem;">

                <div class="panel" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Deductions for Loans </b>
                    </div>
                    <div class="card-body" >
                         You have ________ Pending Deductions
                    </div>
                    <div class="card-footer" >
                        <a href = "ADMIN%20Deductions.php">View All Deductions</a>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    </div>
                </div>


            </div>


            <div class="column col-lg-6" style="margin-top: 2rem; margin-bottom: 2rem;">







            </div>

            <div id="calendarColumn" align="center" class="column col-lg-4" style="margin-top: 1rem; margin-bottom: 2rem;">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Events </b>
                    </div>
                    <div class="card-body" >
                        <iframe src="https://calendar.google.com/calendar/b/3/embed?showTitle=0&amp;showCalendars=0&amp;mode=AGENDA&amp;height=800&amp;wkst=2&amp;bgcolor=%23ffffff&amp;src=noreply.lapdoc%40gmail.com&amp;color=%231B887A&amp;src=en.philippines%23holiday%40group.v.calendar.google.com&amp;color=%23125A12&amp;ctz=Asia%2FManila" style="border-width:0" width="480" height="360" frameborder="0" scrolling="no"></iframe>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b> Polls </b>
                    </div>
                    <div class="card-body" >
                    </div>
                </div>
            </div>



        </div>

    </div>


<?php include 'GLOBAL_FOOTER.php' ?>