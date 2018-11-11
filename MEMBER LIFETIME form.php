<?php

    session_start();
    error_reporting(null);
    require_once("mysql_connect_FA.php");
    include 'GLOBAL_USER_TYPE_CHECKING.php';

    // compute the date first 

        $query = "SELECT YEAR(DATE_HIRED) as 'year' from member where member_id = ".$_SESSION['idnum']." ";

        $hireDate = mysqli_fetch_assoc(mysqli_query($dbc,$query));

        $yearHired = $hireDate['year'];

        $yearNOW = date('Y');


        if(isset($_POST['download'])){


            if(($yearNOW - $yearHired) >= 10 ){

                $ITR = "Lifetime_Document/Lifetime_Membership_Application_Form.pdf";

                header("Location:http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF'])."/downloadLifetime.php?loanID=".urlencode(''.$ITR) );

            }else{

                echo '<script language="javascript">';

                echo 'alert("You cannot as you have to serve for at least 10 years in the faculty association. ")';

                echo '</script>';

            }


        }


        


    // then once the download has been clicked g it my amigo 

    // 
$page_title = 'Loans - Lifetime Membership Form';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Member.php';
?>

        <div id="page-wrapper">

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

                         <div class="well">

                            <p class="text-center">

                            <h3> 
                                You can download the Lifetime application form here once you are Eligible, at least 10 years of being a member of the Faculty Association.
                            </h3>

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

                         <input type = "submit" class="btn btn-success  btn-lg" name="download" value = "Download Lifetime Application Form">

                         </form>


                        </div>

                    </div>

                </div>

                <p>&nbsp;

                <div class="row">

                    <div class="col-lg-12">

                        <div align="center">

                            <a href="MEMBER dashboard.php" class="btn btn-success" role="button">Return to Dashboard</a>

                        </div>

                    </div>

                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
