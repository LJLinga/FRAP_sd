<?php

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';
include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


    $queryMem = "SELECT M.MEMBER_ID, M.LASTNAME, M.FIRSTNAME, M.MIDDLENAME, C.STATUS, M.DATE_HIRED, D.DEPT_NAME, M.HOME_ADDRESS, 
                 M.BUSINESS_ADDRESS, M.HOME_NUM, M.BUSINESS_NUM, M.BIRTHDATE, M.SEX
                 FROM MEMBER AS M
                 JOIN REF_DEPARTMENT AS D
                 ON M.DEPT_ID = D.DEPT_ID
                 JOIN CIV_STATUS AS C
                 ON M.CIV_STATUS = C.STATUS_ID
                 WHERE M.MEMBER_ID = '{$_SESSION['memapp_selected_id']}';";

    $resultMem = mysqli_query($dbc, $queryMem);
    $rowMem = mysqli_fetch_array($resultMem);

    if ($rowMem['SEX'] == 1) {
        $sex = "Male";
    }

    else {
        $sex = "Female";
    }

    if (!empty($rowMem['BUSINESS_NUM'])) {
        $businessnum = $rowMem['BUSINESS_NUM'];
    }

    else {
         $businessnum = "No business number";
    }

    if (!empty($rowMem['BUSINESS_ADDRESS'])) {
        $businessaddress = $rowMem['BUSINESS_ADDRESS'];
    }

    else {
         $businessadd = "No business address";
    }

    if (isset($_POST['accept'])) {

        $queryAccept = "UPDATE MEMBER SET MEMBERSHIP_STATUS = 2, DATE_APPROVED = NOW(), EMP_ID_APPROVE = '{$_SESSION['idnum']}'
                        WHERE MEMBER_ID = '{$_SESSION['memapp_selected_id']}';";

        $resultAccept = mysqli_query($dbc, $queryAccept);

        $queryTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, EMP_ID, SERVICE_TYPE) 
                     VALUES ('{$_SESSION['memapp_selected_id']}', 1, 'Membership Application Approved', 0, NOW(), '{$_SESSION['idnum']}', 1);";

        $resultAccept = mysqli_query($dbc, $queryTxn);

    }

    else if (isset($_POST['reject'])) {

        $queryReject = "DELETE FROM MEMBER_ACCOUNT WHERE MEMBER_ID = '{$_SESSION['memapp_selected_id']}';";

        $resultReject = mysqli_query($dbc, $queryReject);

        $queryReject = "DELETE FROM MEMBER WHERE MEMBER_ID = '{$_SESSION['memapp_selected_id']}';";

        $resultReject = mysqli_query($dbc, $queryReject);

    }

$page_title = 'Loans - Membership Application Details';
include 'GLOBAL_HEADER.php';
include 'FRAP_ADMIN_SIDEBAR.php';

?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            View Member Details
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information<p></b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>ID Number: </b><?php echo $rowMem['MEMBER_ID']; ?> <p>
                                            <b>First Name: </b><?php echo $rowMem['FIRSTNAME']; ?> <p>
                                            <b>Last Name: </b><?php echo $rowMem['LASTNAME']; ?> <p>
                                            <b>Middle Name: </b><?php echo $rowMem['MIDDLENAME']; ?> <p>
                                            <b>Civil Status: </b><?php echo $rowMem['STATUS']; ?> <p>
                                            <b>Date of Birth: </b><?php echo $rowMem['BIRTHDATE']; ?> <p>
                                            <b>Sex: </b><?php echo $sex; ?> <p>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Employment Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>Date of Hiring: </b><?php echo $rowMem['DATE_HIRED']; ?> <p>
                                            <b>Department: </b><?php echo $rowMem['DEPT_NAME']; ?> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Contact Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>Home Phone Number: </b><?php echo $rowMem['HOME_NUM']; ?> <p>
                                            <b>Business Phone Number: </b><?php echo $businessnum ?> <p>
                                            <b>Home Address: </b><?php echo $rowMem['HOME_ADDRESS']; ?> <p>
                                            <b>Business Address: </b><?php echo $businessadd ?> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <input type="submit" class="btn btn-success" name="accept" value="Accept Application">
                                            <input type="submit" class="btn btn-danger" name="reject" value="Reject Application">

                                        </div>

                                    </div>

                                    <a href="ADMIN MEMBERSHIP applications.php" class="btn btn-default">Go Back</a><p><p>&nbsp;

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
