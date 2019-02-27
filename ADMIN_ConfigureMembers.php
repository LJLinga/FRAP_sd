<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//require_once('mysql_connect_FA.php');
session_start();
//include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_EDMS_ADMIN_CHECKING.php');

include 'GLOBAL_HEADER.php';
include 'EDMS_USER_SIDEBAR_RolesPermission.php';
?>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3>Roles and Permission</h3>
                    <!-- This table should be updated based on the contents of the current directory being displayed.
                    Ex. Default, display all the directory as such on the screen. When user navigates to Contracts
                    > 2018. It should display all contracts for 2018 -->
                    <div class="card">
                        <div class="card-body">
                            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                                <table class="table table-bordered" align="center" id="dataTable">
                                    <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Loans</th>
                                        <th>CMS</th>
                                        <th>EDMS</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>

                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                        <td>
                                            <select class="form-control">
                                                <option name="loan1"> Member </option>
                                                <option name="loan2"> Admin </option>
                                            </select>

                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="cms1"> Reader </option>
                                                <option name="cms2"> Contributor </option>
                                                <option name="cms3"> Reviewer </option>
                                                <option name="cms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control">
                                                <option name="edms1"> Reader </option>
                                                <option name="edms2"> Contributor </option>
                                                <option name="edms3"> Reviewer </option>
                                                <option name="edms4"> Editor </option>
                                            </select>
                                        </td>
                                        <td>
                                            <button class="btn primary">Save</button class="btn primary">
                                        </td>
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


<?php include 'GLOBAL_FOOTER.php';?>
