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
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Invite Authors</h3>
                </div>
                <div class="modal-body">
                    <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                        <table class="table table-bordered" align="center" id="dataTable">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>

                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>

                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            <tr>
                                <td><a href="EDMS_ViewDocument.php"><i class="fa fa-fw fa-user"></i> Juan Dela Cruz</a></td>
                                
                                <td>
                                    <button class="btn primary">Invite</button class="btn primary">
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
