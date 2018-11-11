<?php
    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';


    //checks the status of the application that you have sent
    $query = "SELECT MAX(RECORD_ID), APP_STATUS from health_aid WHERE MEMBER_ID = {$_SESSION['idnum']} ";
    $result = mysqli_query($dbc,$query);
    $row = mysqli_fetch_array($result);

    if($row['APP_STATUS'] == 2){ // it means you have an approved HA - youll be sent to the HA summary

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA summary.php");

    }else if(empty($row)){ // it means you have have not applied yet for HA, youll be sent to HA application

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

    }




$page_title = 'Loans - Health Aid Application Sent';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Member.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row"> <!-- Title & Breadcrumb -->
                    
                    <div class="col-lg-12">
                        
                        <h1 class="page-header">Health Aid Application Form</h1>

                    </div>

                </div>

                <form method="POST" action="MEMBER dashboard.php"> <!-- SERVER SELF -->

                <div class="row">

                    <div class="col-lg-12">

                        <div class="well">

                            <p class="welltext justify">Congratulations! You have successfully completed the steps in applying for being a part of the Health Aid Fund Program.  The admins will process and evaluate your application.  You will receive a notification whether your application is approved or not. Once your application has been approved, you will receive further instructions.</p>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-1">


                    </div>
                    <?php
                        $query = "SELECT * FROM FATHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                        $result = mysqli_query($dbc, $query);
                        $row = mysqli_fetch_array($result);

                        if(!empty($row)){
                    ?>

                    <div class="col-lg-10">

                        <p class="healthlabel" align="center">Father Details</p>

                        <table class="table table-bordered">

                            <thead>

                            <tr>

                                <td align="center"><b>Last Name</b></td>
                                <td align="center"><b>First Name</b></td>
                                <td align="center"><b>Middle Name</b></td>
                                <td align="center"><b>Sex</b></td>
                                <td align="center"><b>Birthday</b></td>
                                <td align="center"><b>Status</b></td>

                            </tr>

                            </thead>

                            <tbody>

                            <tr>
                                <!-- Modify and replace with idnum -->

                                <td align="center"><?php echo $row["LASTNAME"]; ?></td>
                                <td align="center"><?php echo $row["FIRSTNAME"]; ?></td>
                                <td align="center"><?php echo $row["MIDDLENAME"]; ?></td>
                                <td align="center">Male</td>
                                <td align="center"><?php echo $row["BIRTHDATE"]; ?></td>
                                <td align="center">
                                    <?php
                                    if ($row["STATUS"] == 1) echo "Alive";
                                    else if ($row["STATUS"] == 0) echo "Deceased";
                                    ?>
                                </td>

                            </tr>

                            </tbody>

                        </table>
                        <?php
                            }
                        ?>

                        <?php
                            $query = "SELECT * FROM MOTHER WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                            $result = mysqli_query($dbc, $query);
                            $row = mysqli_fetch_array($result);

                            if(!empty($row)){
                        ?>

                        <p class="healthlabel" align="center">Mother Details</p>

                        <table class="table table-bordered">
                            
                            <thead>

                                <tr>

                                <td align="center"><b>Last Name</b></td>
                                <td align="center"><b>First Name</b></td>
                                <td align="center"><b>Middle Name</b></td>
                                <td align="center"><b>Sex</b></td>
                                <td align="center"><b>Birthday</b></td>
                                <td align="center"><b>Status</b></td>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>



                                <td align="center"><?php echo $row["LASTNAME"]; ?></td>
                                <td align="center"><?php echo $row["FIRSTNAME"]; ?></td>
                                <td align="center"><?php echo $row["MIDDLENAME"]; ?></td>
                                <td align="center">Female</td>
                                <td align="center"><?php echo $row["BIRTHDATE"]; ?></td>
                                <td align="center">
                                    <?php
                                        if($row["STATUS"] == 1) echo"Alive";
                                        else if ($row["STATUS"] == 0) echo "Deceased";
                                    ?>        
                                </td>

                                </tr>

                            </tbody>

                        </table>
                                <?php
                            }
                        ?>

                        <?php
                            $query = "SELECT * FROM SPOUSE S JOIN MEMBER M ON S.MEMBER_ID = M. MEMBER_ID WHERE S.MEMBER_ID =" . $_SESSION['idnum'].";";
                            $result = mysqli_query($dbc, $query);
                            $row = mysqli_fetch_array($result);

                            if(!empty($row)) {
                                ?>

                                <p class="healthlabel" align="center">Spouse Details</p>

                                <table class="table table-bordered">

                                    <thead>

                                    <tr>

                                        <td align="center"><b>Last Name</b></td>
                                        <td align="center"><b>First Name</b></td>
                                        <td align="center"><b>Middle Name</b></td>
                                        <td align="center"><b>Sex</b></td>
                                        <td align="center"><b>Birthday</b></td>
                                        <td align="center"><b>Status</b></td>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    <tr>


                                        <td align="center"><?php echo $row["LASTNAME"]; ?></td>
                                        <td align="center"><?php echo $row["FIRSTNAME"]; ?></td>
                                        <td align="center"><?php echo $row["MIDDLENAME"]; ?></td>
                                        <td align="center">
                                            <?php
                                            if ($row['SEX'] == 1) echo "Female";
                                            else if ($row['SEX'] === 0) echo "Male";
                                            ?>
                                        </td>
                                        <td align="center"><?php echo $row["BIRTHDATE"]; ?></td>
                                        <td align="center">
                                            <?php
                                            if ($row["STATUS"] == 1) echo "Alive";
                                            else if ($row["STATUS"] === 0) echo "Deceased";
                                            ?>
                                        </td>


                                    </tr>

                                    </tbody>

                                </table>
                                <?php
                            }
                        ?>

                        <?php


                        $query = "SELECT * FROM SIBLINGS WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                        $result = mysqli_query($dbc, $query);

                        if(!empty($row)) {

                            foreach ($result as $resultRow) {
                        ?>
                        <p class="healthlabel" align="center">Siblings Details</p>

                        <table class="table table-bordered">
                            
                            <thead>

                                <tr>

                                <td align="center"><b>Last Name</b></td>
                                <td align="center"><b>First Name</b></td>
                                <td align="center"><b>Middle Name</b></td>
                                <td align="center"><b>Sex</b></td>
                                <td align="center"><b>Birthday</b></td>
                                <td align="center"><b>Status</b></td>

                                </tr>

                            </thead>

                            <tbody>

                                            <tr>
                                                <td align='center'><?php $resultRow['LASTNAME'] ?></td>
                                                <td align='center'><?php $resultRow['FIRSTNAME'] ?></td>
                                                <td align='center'><?php $resultRow['MIDDLENAME'] ?></td>
                                                <td align='center'>
                                                    <?php
                                                        if($row['SEX'] == 1) echo "Female";
                                                        else if($row['SEX'] === 0) echo "Male";
                                                    ?>
                                                </td>
                                                <td align='center'><?php $resultRow['BIRTHDATE'] ?></td>
                                                <td align='center'>
                                                    <?php
                                                        if($resultRow['STATUS'] == 1) echo"Alive";
                                                        else if ($resultRow['STATUS'] == 0) echo "Deceased";
                                                    ?>     
                                                </td>
                                            </tr>
                                <?php
                                    }
                                ?>

                                

                            </tbody>

                        </table>
                            <?php
                        }
                        ?>

                        <?php


                        $query = "SELECT * FROM CHILDREN WHERE MEMBER_ID =" . $_SESSION['idnum'].";";
                        $result = mysqli_query($dbc, $query);

                        if(!empty($row)) {


                        foreach ($result as $resultRow) {
                        ?>

                        <p class="healthlabel" align="center">Children Details</p>

                        <table class="table table-bordered table-stripped">

                            <thead>

                                <tr>

                                <td align="center"><b>Last Name</b></td>
                                <td align="center"><b>First Name</b></td>
                                <td align="center"><b>Middle Name</b></td>
                                <td align="center"><b>Sex</b></td>
                                <td align="center"><b>Birthday</b></td>
                                <td align="center"><b>Status</b></td>

                                </tr>

                            </thead>

                            <tbody>

                                <tr>


                                            <tr>
                                                <td align='center'><?php $resultRow['LASTNAME'] ?></td>
                                                <td align='center'><?php $resultRow['FIRSTNAME'] ?></td>
                                                <td align='center'><?php $resultRow['MIDDLENAME'] ?></td>
                                                <td align='center'><?php $resultRow['SEX'] ?></td>
                                                <td align='center'><?php $resultRow['BIRTHDATE'] ?></td>
                                                <td align='center'>
                                                    <?php
                                                        if($resultRow['STATUS'] == 1) echo"Alive";
                                                        else if ($resultRow['STATUS'] == 0) echo "Deceased";
                                                    ?>       
                                                </td>
                                            </tr>
                                <?php
                                    }
                                ?>

                                </tr>

                            </tbody>

                        </table>
                            <?php
                            }
                        ?>

                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <hr>

                        <div align="center">
                            <a href="MEMBER dashboard.php" class="btn btn-default" role="button">Back to home</a>
                        </div>

                    </div>

                </div>

            </form>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>