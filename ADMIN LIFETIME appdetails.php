
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
                 WHERE M.MEMBER_ID = '{$_SESSION['lifetime_selected_id']}';";

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

    if (isset($_POST['accept']) && isset($_POST['selected'])) {

        if ($_POST['selected'] == 1) { /* Chose human */

            $queryAccept = "INSERT INTO LIFETIME (MEMBER_ID, BPRIMARY, BSECONDARY, APP_STATUS, DATE_ADDED, EMP_ID)
                            VALUES ('{$_SESSION['lifetime_selected_id']}', '{$_POST['primary']}', '{$_POST['secondary']}', 2, DATE(NOW()), '{$_SESSION['idnum']}')";

            $resultAccept = mysqli_query($dbc, $queryAccept);

            $queryTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, EMP_ID, SERVICE_TYPE) 
                        VALUES ('{$_SESSION['lifetime_selected_id']}', 1, 'Lifetime Membership Application Approved', 0, NOW(), '{$_SESSION['idnum']}', 1);";

            $resultAccept = mysqli_query($dbc, $queryTxn);

        }

        else if ($_POST['selected'] == 2) { /* Chose organization */

            $queryAccept = "INSERT INTO LIFETIME (MEMBER_ID, ORG, APP_STATUS, DATE_ADDED, EMP_ID)
                            VALUES ('{$_SESSION['lifetime_selected_id']}', '{$_POST['org']}', 2, NOW(), '{$_SESSION['idnum']}');";

            $resultAccept = mysqli_query($dbc, $queryAccept);

            $queryTxn = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, EMP_ID, SERVICE_TYPE) 
                        VALUES ('{$_SESSION['lifetime_selected_id']}', 1, 'Lifetime Membership Application Approved', 0, NOW(), '{$_SESSION['idnum']}', 5);";

            $resultAccept = mysqli_query($dbc, $queryTxn);

        }

    }

    else if (isset($_POST['reject'])) {

        $queryReject = "INSERT INTO LIFETIME (MEMBER_ID, ORG, APP_STATUS, DATE_ADDED, EMP_ID)
                            VALUES ('{$_SESSION['lifetime_selected_id']}', '{$_POST['org']}', 3, NOW(), '{$_SESSION['idnum']}');";

        $resultAccept = mysqli_query($dbc, $queryReject);

    }

    else {



    }

    $page_title = 'Loans - Lifetime Application Details';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';

?>

<style>
    input[type=radio] {
        transform: scale(1.2);
    }
</style>
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
                                            <b>Personal Information</b>
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
                                            <b>Beneficiaries</b>
                                        </div>
                                        <div class="panel-body"><p>
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="panel panel-info">
                                                        <div class="panel-heading">
                                                            <b>Human Beneficiaries</b>
                                                        </div>
                                                        <div class="panel-body">
                                                            <input type="radio" name="selected" value="1"> Select This<p>
                                                            <div class="row">

                                                                <div class="col-lg-12">

                                                                    <b>Primary Beneficiary:</b> <input type="text" name="primary" class="form-control">

                                                                    <p><p>

                                                                    <b>Secondary Beneficiary:</b> <input type="text" name="secondary" class="form-control">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <div class="col-lg-6">

                                                    <div class="panel panel-info">

                                                        <div class="panel-heading">

                                                            <b>Organization Beneficiary</b>

                                                        </div>

                                                        <div class="panel-body">

                                                            <input type="radio" name="selected" value="2"> Select This<p>

                                                            <div class="row">

                                                                <div class="col-lg-12">

                                                                    <b>Organization Name:</b> <input type="text" name="org" class="form-control">

                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
