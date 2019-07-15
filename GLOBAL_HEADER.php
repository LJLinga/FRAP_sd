<?php ?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php if(isset($page_title)){ echo $page_title; } ?></title>

    <link href="css/montserrat.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/sb-admin.css" rel="stylesheet">
    <link href="css/cards.css" rel="stylesheet">
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/select2.min.css" rel="stylesheet" />

    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/moment.js"></script>
    <script src="js/bootstrap-datetimepicker.min.js"></script>
    <script src="js/select2.min.js"></script>
    <script src="js/tinymce.min.js"></script>

    <link href="datatables/dataTables.bootstrap4.css" rel="stylesheet">
    <script src="datatables/jquery.dataTables.js"></script>
    <script src="datatables/dataTables.bootstrap4.js"></script>

    <script src="js/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet">
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.structure.min.css" rel="stylesheet">
    <link href="js/jquery-ui-1.12.1.custom/jquery-ui.theme.min.css" rel="stylesheet">

    <!-- Include Editor style. -->

    <link href="froala/css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />
    <link href="froala/css/froala_style.min.css" rel="stylesheet" type="text/css" />

    <link href="morris/morris.css" rel="stylesheet" type="text/css"/>
    <script type="text/javascript" src="morris/morris.js"></script>
    <script type="text/javascript" src="morris/raphael-min.js"></script>

    <!-- Include JS file. -->

    <script type="text/javascript" src="froala/js/froala_editor.pkgd.min.js"></script>
</head>
<style>
    head{
        background-color: #f0f0f0;
    }

    body{
        padding-top: 1rem;
        padding-bottom: 1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        background-color: #f0f0f0;
    }

    body
    {
        font-family: 'Helvetica', 'Tahoma', sans-serif;
        color: #444444;
        font-size: 11pt;
        background-color: #FAFAFA;
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        /* display: none; <- Crashes Chrome on hover */
        -webkit-appearance: none;
        margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
    }

    input[type=number] {
        -moz-appearance:textfield; /* Firefox */
    }
</style>

<body>
<div id="wrapper">
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="navbar-header"> <!-- Logo -->

            <img src="images/iafedlogo normal Edited.png" id="ifalogo">

            <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->
                <li>
                    <a href="feed.php"> Home </a>
                </li>

                <li>
                    <a href="MEMBER%20dashboard.php"> Loans </a>
                </li>
                <?php

                    $query = "SELECT FRAP_ROLE, EDMS_ROLE, CMS_ROLE, SYS_ROLE FROM employee WHERE MEMBER_ID = '{$_SESSION['idnum']}' ";
                    $row = mysqli_query($dbc, $query);
                    $result = mysqli_fetch_array($row);

                    $_SESSION['FRAP_ROLE'] =  $result['FRAP_ROLE'];
                    $_SESSION['CMS_ROLE'] =  $result['CMS_ROLE'];
                    $_SESSION['EDMS_ROLE'] =  $result['EDMS_ROLE'];
                    $_SESSION['SYS_ROLE'] =  $result['SYS_ROLE'];


                    if($_SESSION['FRAP_ROLE'] > 1 || $_SESSION['EDMS_ROLE'] > 1 || $_SESSION['CMS_ROLE'] > 1) {
                        echo '<li class="dropdown sideicons"><a href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Tools <b class="caret"></b></a><ul class="dropdown-menu alert-dropdown">';
                        if($_SESSION['FRAP_ROLE'] > 1) {
                            echo '<li><a href="ADMIN%20Deductions.php"> <i class="fa fa-money" aria-hidden="true"></i> Loans </a></li>';
                        }
                        if($_SESSION['CMS_ROLE'] > 1) {
                            echo '<li><a href="CMS_PostsDashboard.php"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> Santinig Content </a></li>';
                        }
                        if($_SESSION['EDMS_ROLE'] >= 1){
                            echo '<li><a href="EDMS_Workspace.php"> <i class="fa fa-file-text" aria-hidden="true"></i> Documents</a></li>';
                        }
                        if($_SESSION['SYS_ROLE'] >= 1){
                            echo '<li><a href="SYS_UserRoles.php"> <i class="fa fa-wrench" aria-hidden="true"></i> Configurations </a></li>';
                        }
                        echo '</ul></li>';
                    }

                    ?>
                <li class="dropdown sideicons">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-money"><span class="badge count"></span></i></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <div class="notifications"></div>
                        <li class="divider"></li>
                        <li>
                            <a href="FRAP_ALL_NOTIFS.php">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown sideicons">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-newspaper-o"><span id="cmsCount" class="label label-danger"></span></i></a>
                    <ul class="dropdown-menu alert-dropdown">
                        <li id="cms_notifications" style="font-size: 12px;">

                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="CMS_PostsDashboard.php">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown sideicons">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-globe"><span id="notificationsCount" class="label label-danger"></span></i></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li id="notificationsDisplay" style=""></li>
                        <li>
                            <a href="notifications.php">View All</a>
                        </li>
                    </ul>
                </li>
                <li class="dropdown sideicons">
                    <?php
                        $query = "SELECT LASTNAME, FIRSTNAME FROM employee WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                        $result = mysqli_query($dbc, $query);
                        $row = mysqli_fetch_array($result);
                        $displayName = $row['LASTNAME']." , ".$row['FIRSTNAME'][0].". ";
                        $_SESSION['NAME'] = $displayName;
                    ?>

                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName; ?> <b class="caret"></b></a>

                    <ul class="dropdown-menu">

                        <li>

                            <a href="FA_Profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>

                        </li>
                        <li>

                            <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                        </li>

                    </ul>

                </li>

            </ul>

            <div id="notif_alert" class="alert alert-info" style="position:fixed;
                                top: 8%;
                                right: 2%;
                                 font-size: 10pt;">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <span id="spanMsg"></span>

            </div>

            <script>
                $(document).ready(function(){
                    // updating the view with notifications using ajax

                    let temp = "<?php echo $_SESSION['idnum'];?>";

                    function load_unseen_notification(idnum)
                    {
                        $.ajax({
                            url:"fetch_header_notifs.php",
                            method:"POST",
                            data:{idnum:idnum},
                            dataType:"json",
                            success:function(data)
                            {
                                $('.notifications').html(data.notification);
                                if(data.unseen_notification > 0)
                                {
                                    $('.counts').html(data.unseen_notification);
                                }
                            }
                        });
                    }

                    function load_cms_notifications(idnum) {
                        $.ajax({
                            url: "CMS_AJAX_Notifications.php",
                            method: "POST",
                            data: {userId: idnum, limit: 5},
                            dataType: "json",
                            success: function (data) {
                                $('#cms_notifications').html(data.notification);
                                if (data.count > 0) {
                                    $('#cmsCount').html(data.count);
                                }
                            }
                        });
                    }

                    $("#notif_alert").hide();

                    function load_notifications() {
                        $.ajax({
                            url: "GLOBAL_AJAX_Notifications.php",
                            method: "POST",
                            data: '',
                            dataType: "json",
                            success: function (data) {
                                $('#spanMsg').html(data.notifications[1]);
                                if (data.count > 0) {
                                    $('#notificationsCount').html(data.count);
                                    $.each(data.notifications, function(key, value){
                                        $('#spanMsg').html(value);
                                        $("#notif_alert").slideDown();
                                        $("#notif_alert").fadeTo(5000, 5000).slideUp(5000, function(){});
                                    });
                                }
                            }
                        });
                    }

                    setInterval(function(){

                        //load_notifications();
                        //load_cms_notifications(temp);
                        //load_unseen_notification(temp); // this will run after every 1 second
                    }, 1000);


                });
            </script>
