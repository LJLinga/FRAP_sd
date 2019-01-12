<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include 'GLOBAL_FRAP_ADMIN_CHECKING.php';

     //Test value
    //$_SESSION['idnum']=1141231234;

    /* I dont have any fucking idea what this fucking does stupid past me mustve had a reason so im keeping it here
    $query = "SELECT MEMBER_ID FROM LOANS WHERE LOAN_ID = '{$_SESSION['showHAID']}'";
    $result = mysqli_query($dbc, $query);
    $row = mysqli_fetch_array($result);
    */

    if(isset($_POST['details'])){

        $_SESSION['showHAID'] = NULL;   // Current Health ID
        $_SESSION['showHAMID'] = NULL;  // Current Member ID of the  current Health Aid ID.


        $_SESSION['showHAID'] = $_POST['details']; //assigns the record id

        $query = "SELECT MEMBER_ID FROM health_aid  WHERE RECORD_ID = '{$_POST['details']}'";
        $result = mysqli_query($dbc, $query);
        $row = mysqli_fetch_array($result);

        $_SESSION['showHAMID'] = $row['MEMBER_ID']; //assigns the member id of the record id

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID appdetails.php");

    }




    $page_title = 'Loans - Health Applications';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_ADMIN_SIDEBAR.php';
?>

<script>

    $(document).ready(function(){

        $('#table').DataTable();

    });

</script>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Pending Health Aid Applications
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                       <div class="row">

                            <div class="col-lg-12">

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST"> <!-- SERVER SELF -->

                                <table id="table" name="table" class="table table-bordered table-striped">
                                    
                                    <thead>

                                        <tr>

                                        <td align="center"><b>Date Applied</b></td>
                                        <td align="center"><b>ID Number</b></td>
                                        <td align="center"><b>Name</b></td>
                                        <td align="center"><b>Department</b></td>
                                        <td align="center"><b>Actions</b></td>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php

                                            $query = "SELECT HA.DATE_APPLIED, HA.RECORD_ID,  M.MEMBER_ID, M.FIRSTNAME, M.LASTNAME, RD.DEPT_NAME 
                                                      FROM MEMBER M 
                                                      JOIN HEALTH_AID HA 
                                                      ON M.MEMBER_ID = HA.MEMBER_ID 
                                                      JOIN REF_DEPARTMENT RD 
                                                      ON M.DEPT_ID = RD.DEPT_ID 
                                                      WHERE HA.APP_STATUS='1';";

                                            $result = mysqli_query($dbc, $query);
                                            
                                            foreach ($result as $resultRow) {
                                                echo"
                                                    <tr>
                                                        <td align='center'>". $resultRow['DATE_APPLIED'] ."</td>
                                                        <td align='center'>". $resultRow['MEMBER_ID'] ."</td>
                                                        <td align='center'>". $resultRow['FIRSTNAME'] . " " .$resultRow['LASTNAME'] ."</td>
                                                        <td align='center'>". $resultRow['DEPT_NAME'] ."</td>
                                                        <td align='center'>&nbsp;&nbsp;&nbsp;<button type='submit' class='btn-xs btn-success' name='details' value='". $resultRow['RECORD_ID'] ."'>Details</button>&nbsp;&nbsp;&nbsp;</td>
                                                    </tr>
                                                ";
                                            }



                                        ?>
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


</body>

</html>
