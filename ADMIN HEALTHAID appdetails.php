<?php
    session_start();
    if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}

    require_once('mysql_connect_FA.php');
     //Test value
    //$_SESSION['idnum']=1141231234;


    if(isset($_POST['action'])){
        if($_POST['action'] == "Accept Application"){
            //Change the status into Approved (APP_STATUS =2)
            $query = "UPDATE HEALTH_AID SET APP_STATUS = '2', DATE_APPROVED = NOW(), EMP_ID =". $_SESSION['idnum'] ."
                      WHERE MEMBER_ID =" . $_SESSION['showHAMID']." && RECORD_ID =" . $_SESSION['showHAID']."  ;";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
                                            VALUES({$_SESSION['showHAMID']}, '1', 'Health Aid Approved', 0, NOW(), {$_SESSION['showHAID']}, {$_SESSION['idnum']}, '2'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID applications.php");

        }
        else if($_POST['action'] == "Reject Application"){
            //Change the status into Rejected (APP_STATUS =3)
            $query = "UPDATE HEALTH_AID SET APP_STATUS = '3', EMP_ID =". $_SESSION['idnum'] ." WHERE MEMBER_ID =" . $_SESSION['showHAID'].";";
            $result = mysqli_query($dbc, $query);

           //Insert into transaction table
            $queryTnx = "INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, TXN_DATE, LOAN_REF, EMP_ID, SERVICE_ID) 
                                VALUES({$_SESSION['showHAMID']}, '1', 'Health Aid Rejected', 0, NOW(), {$_SESSION['showHAID']}, {$_SESSION['idnum']}, '2'); ";
            $resultTnx = mysqli_query($dbc, $queryTnx);

            header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/ADMIN HEALTHAID applications.php");

        }



    }



    $page_title = 'Loans - Health Aid Application Details';
    include 'GLOBAL_HEADER.php';
    include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

<!---
<script>
    $(document).ready(function(){
        $('input[name=action]').on('click', function(){
            console.log("Are you sure?");
            confirm("Are you sure?");
        });
    });
</script>

--->

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            View Health Aid Details
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

                                            <b>Applicant Information</b>

                                        </div>

                                        <div class="panel-body"><p>
                                            <?php 
                                                $query = "SELECT M.FIRSTNAME, M.LASTNAME, M.MIDDLENAME, RD.DEPT_NAME 
                                                          FROM MEMBER M 
                                                          JOIN HEALTH_AID HA 
                                                          ON M.MEMBER_ID = HA.MEMBER_ID 
                                                          JOIN REF_DEPARTMENT RD 
                                                          ON M.DEPT_ID = RD.DEPT_ID 
                                                          WHERE M.MEMBER_ID = ". $_SESSION['showHAMID'] .";";
                                                $result = mysqli_query($dbc, $query);
                                                $row = mysqli_fetch_array($result);
                                            ?>

                                            <b>ID Number:</b> <?php echo $_SESSION['showHAMID'] ?> <p>
                                            <b>First Name:</b> <?php echo $row['FIRSTNAME'] ?> <p>
                                            <b>Last Name:</b> <?php echo $row['LASTNAME'] ?> <p>
                                            <b>Middle Name:</b> <?php echo $row['MIDDLENAME'] ?> <p>
                                            <b>Department:</b> <?php echo $row['DEPT_NAME'] ?><p>
                                            
                                        </div>

                                    </div>

                                    <?php
                                        $query = "SELECT * FROM FATHER WHERE RECORD_ID =" . $_SESSION['showHAID'].";";
                                        $result = mysqli_query($dbc, $query);
                                        $row = mysqli_fetch_array($result);

                                        if(!empty($row)){


                                        $today = date("Y-m-d");
                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                    ?>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Father Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <table id="table" class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>
                                                    <td align="center"><b>Status</b></td>


                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>

                                                        <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                        <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                        <td align="center"><?php echo $row['BIRTHDATE']; ?></td>
                                                        <td align="center">
                                                            <?php
                                                            if ($row["STATUS"] == 1) echo "Alive";
                                                            else if ($row["STATUS"] == 0) echo "Deceased";


                                                            }
                                                        ?>
                                                    </td>

                                                    </tr>

                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>

                                    <?php
                                        $query = "SELECT * FROM MOTHER WHERE RECORD_ID =" . $_SESSION['showHAID'].";";
                                        $result = mysqli_query($dbc, $query);
                                        $row = mysqli_fetch_array($result);

                                        if(!empty($row)){

                                        $today = date("Y-m-d");
                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                    ?>


                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Mother Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>
                                                    <td align="center"><b>Status</b></td>


                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>
                                                        <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                        <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                        <td align="center"><?php echo $row['BIRTHDATE']; ?></td>
                                                        <td align="center">
                                                            <?php
                                                            if ($row["STATUS"] == 1) echo "Alive";
                                                            else if ($row["STATUS"] == 0) echo "Deceased";


                                                            }
                                                        ?>
                                                    </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                    <?php
                                        $query = "SELECT * FROM SPOUSE WHERE RECORD_ID =" . $_SESSION['showHAID'].";";
                                        $result = mysqli_query($dbc, $query);
                                        $row = mysqli_fetch_array($result);

                                        if(!empty($row)){

                                        $today = date("Y-m-d");
                                        $diff = date_diff(date_create($row['BIRTHDATE']), date_create($today));
                                    ?>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Spouse Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>
                                                    <td align="center"><b>Status</b></td>


                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <tr>



                                                        <td align="center"><?php echo $row['FIRSTNAME'] . " " . $row['LASTNAME']; ?></td>
                                                        <td align="center"><?php echo $diff->format('%y'); ?></td>
                                                        <td align="center"><?php echo $row['BIRTHDATE']; ?></td>
                                                        <td align="center">
                                                            <?php
                                                            if ($row["STATUS"] == 1) echo "Alive";
                                                            else if ($row["STATUS"] == 0) echo "Deceased";

                                                            }
                                                        ?>
                                                    </td>

                                                    </tr>

                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>
                                    <?php
                                        $query = "SELECT * FROM SIBLINGS WHERE RECORD_ID =" . $_SESSION['showHAID'].";";
                                        $result = mysqli_query($dbc, $query);

                                        if(!empty(mysqli_fetch_array($result))) {
                                    ?>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Siblings Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>
                                                    <td align="center"><b>Status</b></td>
                                                    <td align="center"><b>Sex</b></td>


                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <?php

                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo "    
                                                            <tr>
                                                                <td align='center'>" . $resultRow['FIRSTNAME'] . " " . $resultRow['LASTNAME'] . "</td>
                                                                <td align='center'>" . $diff->format('%y') . "</td>
                                                                <td align='center'>" . $resultRow['BIRTHDATE'] . "</td>
                                                                <td align='center'>";
                                                            if ($resultRow["STATUS"] == 1) echo "Alive";
                                                            else if ($resultRow["STATUS"] == 0) echo "Deceased";
                                                            echo "</td>
                                                                <td align='center'>";
                                                            if ($resultRow['SEX'] = 1) echo "Male";
                                                            else echo "Female";
                                                            echo "</td>
                                                            </tr>    
                                                            ";
                                                        }

                                                    }
                                                    ?>


                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>

                                    <?php
                                    $query = "SELECT * FROM CHILDREN WHERE RECORD_ID =" . $_SESSION['showHAID'].";";
                                    $result = mysqli_query($dbc, $query);

                                    if(!empty(mysqli_fetch_array($result))) {

                                    ?>

                                    <div class="panel panel-primary">

                                        <div class="panel-heading">

                                            <b>Children Details</b>

                                        </div>

                                        <div class="panel-body"><p>

                                            <table class="table table-bordered">
                                
                                                <thread>

                                                    <tr>

                                                    <td align="center"><b>Name</b></td>
                                                    <td align="center"><b>Age</b></td>
                                                    <td align="center"><b>Birthday</b></td>
                                                    <td align="center"><b>Status</b></td>
                                                    <td align="center"><b>Sex</b></td>


                                                    </tr>

                                                </thread>

                                                <tbody>

                                                    <?php 

                                                        foreach ($result as $resultRow) {
                                                            $today = date("Y-m-d");
                                                            $diff = date_diff(date_create($resultRow['BIRTHDATE']), date_create($today));

                                                            echo "
                                                            <tr>    
                                                                <td align='center'>" . $resultRow['FIRSTNAME'] . " " . $resultRow['LASTNAME'] . "</td>
                                                                <td align='center'>" . $diff->format('%y') . "</td>
                                                                <td align='center'>" . $resultRow['BIRTHDATE'] . "</td>
                                                                <td align='center'>";
                                                            if ($resultRow["STATUS"] == 1) echo "Alive";
                                                            else if ($resultRow["STATUS"] == 0) echo "Deceased";
                                                            echo "</td>
                                                                <td align='center'>";
                                                            if ($resultRow['SEX'] = 1) echo "Male";
                                                            else echo "Female";
                                                            echo "</td>
                                                            </tr>    
                                                            ";
                                                        }

                                                    }
                                                    ?>

                                                    

                                                </tbody>

                                            </table>
                                            
                                        </div>

                                    </div>

                                    <div class="panel panel-green">

                                        <div class="panel-heading">

                                            <b>Actions</b>

                                        </div>

                                        <div class="panel-body"><p> 
                                                <input type="submit" class="btn btn-success" name="action" value="Accept Application">
                                                <input type="submit" class="btn btn-danger" name="action" value="Reject Application">
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


    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>
