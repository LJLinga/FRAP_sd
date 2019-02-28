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
                    <h3 class="modal-title">Upload File</h3>
                </div>
                <div class="modal-body">
                    <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                        <input type="file" class="file-input">
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="btn-group">
                        <button type="button" class="btn btn-default">Upload File</button>
                        <button type="button" class="btn btn-default dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-fw fa-caret-down"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="#">Upload as Application</a>
                            <a class="dropdown-item" href="#">Upload as By Law</a>
                            <a class="dropdown-item" href="#">Upload as Section</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">Separated link</a>
                        </div>
                    </div>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
