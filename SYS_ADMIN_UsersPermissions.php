<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 2/17/2019
 * Time: 11:09 PM
 */


//include_once('GLOBAL_CLASS_CRUD.php');
//$crud = new GLOBAL_CLASS_CRUD();
//require_once('mysql_connect_FA.php');
session_start();
//include('GLOBAL_USER_TYPE_CHECKING.php');
//include('GLOBAL_SYS_ADMIN_CHECKING.php');

//hardcoded value for userType, will add MYSQL verification
$userId = $_SESSION['idnum'];

$page_title = 'Santinig - User Permissions Quick Edit';
include 'GLOBAL_HEADER.php';
//include 'SYS_ADMIN_SIDEBAR.php';

include('CMS_SIDEBAR.php')

?>
<div id="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-7">

                <h3>User roles and permissions</h3></br>

                <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                    <table class="table table-bordered" align="center" id="dataTable">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th width="100px">EDMS</th>
                            <th width="100px">CMS</th>
                            <th width="100px">Loans</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php for($i=0; $i<5; $i++){ ?>
                            <tr>
                                <td>Juan Dela Cruz</td>
                                <td>
                                    <select class="form-control">
                                        <option value="">Reader</option>
                                        <option value="">Technical Panel</option>
                                        <option value="">Negotiation Head</option>
                                        <option value="">Executive Board</option>
                                        <option value="">President</option>
                                        <option value="">Transcriber</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control">
                                        <option value="">Reader</option>
                                        <option value="">Reviewer</option>
                                        <option value="">Publisher</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control">
                                        <option value="">Member</option>
                                        <option value="">Office Assistant</option>
                                        <option value="">President</option>
                                        <option value="">Treasurer</option>
                                    </select>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $('#dataTable').DataTable();
    });
</script>

<?php include('GLOBAL_FOOTER.php'); ?>
