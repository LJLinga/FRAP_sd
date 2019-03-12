<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 3:31 PM
 */
?>
<!--
    Adds Modal function.

    Append to element => data-toggle="modal" data-target="#myModal"
-->


    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog" data-backdrop="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h3 class="modal-title">Choose People</h3>
                </div>
                <div class="modal-body">

                    <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th width="20px"></th>
                                <th>Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            <tr>
                                <td><input type="checkbox" name="employee" value="1"></td>
                                <td>
                                    <a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-default" value="Confirm">
                    <button type="button" class="btn btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>

        </div>
    </div>
