<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

    $page_title = 'Admin - Dashboard';
    include 'GLOBAL_HEADER.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';


?>
        <div id="page-wrapper">

            <div class="container-fluid">



            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->]
<?php include 'GLOBAL_FOOTER.php' ?>