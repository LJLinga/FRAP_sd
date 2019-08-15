<?php

    session_start();
    error_reporting(null);
    require_once("mysql_connect_FA.php");
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    // compute the date first 

        $query1 = "SELECT YEAR(DATE_APPROVED) as 'year' from member where member_id = ".$_SESSION['idnum']." ";

        $hireDate = mysqli_fetch_assoc(mysqli_query($dbc,$query1));

        $yearHired = $hireDate['year'];

        $yearNOW = date('Y');


        if(isset($_POST['download'])){

            $lifetimeCheck = "UPDATE member set LIFETIME_STATUS = 2 where MEMBER_ID = {$_SESSION['MEMBER_ID']}";
            mysqli_query($dbc,$lifetimeCheck);


            $query = "INSERT INTO txn_reference(MEMBER_ID,TXN_TYPE, TXN_DESC, AMOUNT, SERVICE_ID)
                                  values({$member['MEMBER_ID']}, 2, 'Membership Fee has been deducted from your salary.' , 183.33, 1);";

            if (!mysqli_query($dbc,$query))
            {
                echo("Error description: " . mysqli_error($dbc));
            }


        }


        


    // then once the download has been clicked g it my amigo 

    // 
$page_title = 'Loans - Lifetime Membership Form';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';
?>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
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

                         <input type = "submit" class="btn btn-info  btn-md" name="download" value = "Download Lifetime Application Form">

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
