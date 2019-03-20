<?php

    error_reporting(null);
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    $page_title = 'Loans - FALP Application Failed';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';
?>
        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                
                    <div class="col-lg-12">

                        <br>

                        <br>

                        <div class="well" align="center" style="background-color: white">

                            <h1>You have already loaned once during the term! Wait until the next term for you to be able to loan again!</h1>

                            <br>

                            <br>




                            <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Go Back</a>

                        </div>
                    
                    </div>

                </div>

                



                <hr>

               

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
