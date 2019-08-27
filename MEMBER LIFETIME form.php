<?php

    session_start();
    error_reporting(null);
    require_once("mysql_connect_FA.php");
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    // compute the date first 

        $query1 = "SELECT DATE_APPROVED from member where MEMBER_ID ={$_SESSION['idnum']} ";
        $query1result = mysqli_query($dbc,$query1);

        $hireDate= mysqli_fetch_array($query1result);


        $dateApproved = date('Y-m-d',strtotime($hireDate['DATE_APPROVED'])); //

        $dateNow = date('Y-m-d'); //gts current Date

        $dateApprovedPlus10 = date('Y-m-d', strtotime(date("Y-m-d", strtotime($dateApproved)). "+ 10 years"));


        if(isset($_POST['download'])){

            $lifetimeCheck = "UPDATE member set LIFETIME_STATUS = 3 where MEMBER_ID = {$_SESSION['idnum']}";
            mysqli_query($dbc,$lifetimeCheck);

            $ITR = "Lifetime_Document/Lifetime_Membership_Application_Form.pdf";

            header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadLifetime.php?loanID=".urlencode(''.$ITR) );



        }


    // then once the download has been clicked g it my amigo 

    // 
$page_title = 'Services - Lifetime Membership Form';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';
?>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <?php if($dateNow < $dateApprovedPlus10 ){ // wala ka pang 10 years

                    ?>
                    <br>

                    <div class="col-lg-12" id="alertLocation" >

                        <div id="message" class="alert alert-danger">
                            <strong>
                                <span id="messageAlert">Sorry! You cannot apply just yet, you have not completed your 10 years in DLSU.</span>
                            </strong>

                        </div>

                    </div>
                <?php }
                ?>

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">Lifetime Membership Application</h1>
                    
                    </div>

                </div>
                <div class = "row">

                    <div align="center">
                    <div class="col-lg-2">

                    
                    </div>

                    <div class = "col-lg-8">

                         <div class="well" style="background-color: white">

                            <p class="text-center">

                            <h4>
                                You can download the Lifetime application form here once you are Eligible, at least 10 years of being a member of the Faculty Association.
                            </h4>

                            </p>
                        </div>
                    </div>


                     <div class="col-lg-2">

                    
                    </div>
                </div>


                </div>

                <div class="row"> <!-- Well -->

                    <div class="col-lg-1 col-1">

                    </div>

                    <div class="col-lg-10 col-2">

                        <div align="center">

                        <form action="#" method="POST" > 

                        <img class="pdficon10" src="images/pdficon.png">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php if($dateNow < $dateApprovedPlus10 ){ ?>
                         <input type = "submit" class="btn btn-info  btn-md" name="download" value = "Download Lifetime Application Form" disabled>
                        <?php }else{ ?>
                            <input type = "submit" class="btn btn-info  btn-md" name="download" value = "Download Lifetime Application Form" >
                        <?php } ?>
                         </form>


                        </div>

                    </div>

                </div>

                <p>&nbsp;

                <div class="row">

                    <div class="col-lg-12">

                        <div align="center">

                            <a href="MEMBER dashboard.php" class="btn btn-light btn-md" role="button">Return to Dashboard</a>

                        </div>

                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
