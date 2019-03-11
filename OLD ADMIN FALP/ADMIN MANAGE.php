<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

    $page_title = 'Loans - Manage';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';

?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Admin Management
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">

                    <div class="col-lg-12">

                        <div class="alert alert-info">

                            <strong>Here, you can edit what modules that other admins of the system could access</strong>

                        </div>

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="ADMIN MANAGE acl.php" method="POST"> <!-- SERVER SELF -->

                                <table id="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center" width="300px"><b>Admin ID Number</b></td>
                                        <td align="center" width="600px"><b>Name</b></td>
                                        <td align="center"><b>Manage Access Control List</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>

                                        <td align="center">11436786</td>
                                        <td align="center">Patrick Mijares </td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="submit" name="details" class="btn btn-success" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">11436786</td>
                                        <td align="center">Patrick Mijares </td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="submit" name="details" class="btn btn-success" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                        <tr>

                                        <td align="center">11436786</td>
                                        <td align="center">Patrick Mijares </td>
                                        <td align="center">&nbsp;&nbsp;&nbsp;<input type="submit" name="details" class="btn btn-success" value="Details">&nbsp;&nbsp;&nbsp;</td>

                                        </tr>

                                    </tbody>

                                </table>

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
