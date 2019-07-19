<!DOCTYPE html>
<html lang="en">
<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';


    if(isset($_POST['action'])){
        if($_POST['action'] == "Reactivate Account"){
            $query1 = "UPDATE member
                      set USER_STATUS = 1,
                      MEMBERSHIP_STATUS = 2
                      where MEMBER_ID = {$_SESSION['currID']}  ";
        }
        else if($_POST['action'] == "Deactivate Account"){
            $query1 = "UPDATE member
                      set USER_STATUS = 4,
                      MEMBERSHIP_STATUS = 3
                      where MEMBER_ID = {$_SESSION['currID']}  ";
        }
        mysqli_query($dbc,$query1);

    }
    $query = "SELECT * FROM member m join ref_department d
              on m.dept_id = d.dept_id 
              join civ_status c
              on m.civ_status = c.status_id
              where m.member_id = {$_SESSION['currID']}";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $page_title = 'Loans - View Member Details';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';


?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">
                        <form method = "POST" action = "ADMIN MEMBERS viewdetails.php">
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

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Personal Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>ID Number: <?php echo $ans['MEMBER_ID']?></b> <p>
                                            <b>First Name: <?php echo $ans['FIRSTNAME']?></b> <p>
                                            <b>Last Name: <?php echo $ans['LASTNAME']?></b> <p>
                                            <b>Middle Name: <?php echo $ans['MIDDLENAME']?></b> <p>
                                            <b>Civil Status: <?php echo $ans['STATUS']?></b> <p>
                                            <b>Date of Birth: <?php echo $ans['BIRTHDATE']?></b> <p>
                                            <b>Sex:<?php if($ans['SEX']=="1")
                                                            echo "Male";
                                                            else
                                                                echo "Female";?></b> <p>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Employment Information</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <b>Employee Category: <?php echo $ans['EMP_TYPE']?></b> <p>
                                            <b>Employee Category: <?php echo $ans['TYPE']?></b> <p>
                                            <b>Employee Status: <?php echo $ans['EMP_STATUS']?></b> <p>
                                            <b>Date of Hiring: <?php echo $ans['DATE_HIRED']?></b> <p>
                                            <b>Department: <?php echo $ans['DEPT_NAME']?></b> <p>

                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Contact Information</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <b>Home Phone Number: <?php echo $ans['HOME_NUM']?></b> <p>
                                            <b>Business Phone Number: <?php echo $ans['BUSINESS_NUM']?></b> <p>
                                            <b>Home Address: <?php echo $ans['HOME_ADDRESS']?></b> <p>
                                            <b>Business Address: <?php echo $ans['BUSINESS_ADDRESS']?></b> <p>
                                            <input type = "text" name = "details" value = <?php echo $_SESSION['currID']?> hidden>
                                        </div>

                                    </div>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php if($ans['USER_STATUS']=="4"){
                                                echo '<input id="action" type="submit" name="action" value="Reactivate Account" hidden>';
                                                echo '<button id="modalTrigger" type="button" class="btn btn-success" data-toggle="modal" data-target="#confirm-submit">Reactivate Account</button>';
                                             } else{
                                                echo '<input id="action" type="submit" name="action" value="Deactivate Account" hidden>';
                                                echo '<button id="modalTrigger" type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirm-submit">Deactivate Account</button>';
                                             }?>
                                        </div>

                                    </div>
                                </form>
                                    <a href="ADMIN MEMBERS viewmembers.php" class="btn btn-default">Go Back</a><p><p>&nbsp;

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

<!-- Modal by xtian pls dont delete hehe -->
<div class="modal fade" id="confirm-submit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                Confirm Action
            </div>
            <div class="modal-body">
                Are you sure you want to <b id="changeText"></b> ?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a href="#" id="submit" class="btn btn-success success">Yes, I'm sure</a>
            </div>
        </div>
    </div>
</div>

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

            var action = $("#action").val();

            $('#modalTrigger').click(function() {
                $('#changeText').text(action);
                $('#submit').click(function() {
                    $("#action").click();
                });
            });
        });
    </script>

</body>

</html>
