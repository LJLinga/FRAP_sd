<?php

    session_start();

    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");

    }

    require_once('mysql_connect_FA.php');
    $page_title = 'Admin - Dashboard';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATES_NAVIGATION_Dashboard.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">



            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->]
</body>

</html>
