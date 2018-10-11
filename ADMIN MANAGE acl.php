<?php
session_start();
require_once("mysql_connect_FA.php");
if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

    $page_title = 'Loans - Manage ACL';
    include 'GLOBAL_TEMPLATE_Header.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Membership.php';
?>

<style>

    input[type=checkbox] {
        transform: scale(2);
    }

</style>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Edit Access for Patrick Mijares
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">

                    <div class="col-lg-12">

                        <div class="alert alert-info">

                            <strong>Here, you can edit if this admin can access the modules listed below</strong>

                        </div>

                       <div class="row">

                            <div class="col-lg-2">
                            </div>

                            <div class="col-lg-8">

                                <form action="ADMIN MANAGE acl.php" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center" width="600px"><b>Module Name</b></td>
                                        <td align="center"><b>Edit Access</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                        <td align="center">Add Admin Account</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Evaluate Membership Applications</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Evaluate FALP Applications</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Evaluate Bank Loan Applications</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Evaluate Health Aid Applications</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Add Lifetime Member</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Add Partner Bank</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Enable/Disable Partner Banks</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Add Bank Loan Plan</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Enable/Disable Bank Loan Plans</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View All Members</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View Member's Services</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View On-going FALP Loans</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View On-going Bank Loans</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View General Deductions</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View Detailed Deductions</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View Completed Loans</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View New Deductions</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View Monthly Report</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">View Documents Uploaded</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Upload Documents</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">Manage Admins</td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="checkbox" name="details" width="50px" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                    </tbody>

                                </table>

                                <hr>

                                <div align="center">

                                <input type="submit" value="Apply Changes" name="submit" class="btn btn-success">
                                <a href="ADMIN MANAGE.php" class="btn btn-default">Go Back</a><p><p>&nbsp;

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

</body>

</html>
