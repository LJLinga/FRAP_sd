<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Profile</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="buttonsstyle.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src = "https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js">
    </script>



    <script> $(function(){
        $('#scrollToTopScript').load('scrollToTop.html');
     });
    </script>
</head>

<?php

session_start();
require_once("mysql_connect_FA.php");
include 'GLOBAL_USER_TYPE_CHECKING.php';



/*-------FILE REPO STUFF------*/
$_SESSION['parentFolderID']="";
$_SESSION['currentFolderID']="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso";
/*-------FILE REPO STUFF END------*/

$success = null;
    

    $queryDept = "SELECT * FROM REF_DEPARTMENT";
    $resultDept = mysqli_query($dbc, $queryDept);

    $queryCiv = "SELECT * FROM CIV_STATUS";
    $resultCiv = mysqli_query($dbc, $queryCiv);

    if (isset($_POST['submit'])) {

            ; 
            $email = $_POST['email'];
            $failedLife = null;

            $failedFalp = null;
            //first and last names
            $fName = NULL;
            $lName = NULL;
            $mName = NULL;
            $civStat = NULL;
            //bdate
            $bYear = $_POST['bYear'];
            $bMonth =$_POST['bMonth'];
            $bDay = $_POST['bDay'];
            $sex = NULL; 
            
            //hiredate
            $hYear = $_POST['hYear'];
            $hMonth = $_POST['hMonth'];
            $hDay = $_POST['hDay'];
           $empStat = $_POST['empStat'];
            $empType = $_POST['empType'];
            $empStatus = $_POST['empStatus'];
            $dept = NULL;

         
            $honum = NULL;
            $bunum = NULL;
            // hadresses
            $haddress = NULL;
            $badress = NULL;
            // Date Approved
            $aYear = $_POST['aYear'];
            $aMonth = $_POST['aMonth'];
            $aDay = $_POST['aDay'];
            $fYear = $_POST['fYear'];
            $fMonth = $_POST['fMonth'];
            $fDay = $_POST['fDay'];
            $lYear = $_POST['lYear'];
            $lMonth = $_POST['lMonth'];
            $lDay = $_POST['lDay'];
            //Date applied
            // Date applied
            // Date applied
            $appYear = $_POST['appYear'];
            $appMonth = $_POST['appMonth'];
            $appDay = $_POST['appDay'];
            $faYear = $_POST['faYear'];
            $faMonth = $_POST['faMonth'];
            $faDay = $_POST['faDay'];
            $laYear = $_POST['laYear'];
            $laMonth = $_POST['laMonth'];
            $laDay = $_POST['laDay'];

            // Employee ID
            $emp_ID = $_POST['emp_ID'];
           

           
            // first check if the id is unique and if the ID is  

            $queryID = "SELECT MEMBER_ID, MEMBERSHIP_STATUS FROM member WHERE member_id = {$idNum}";
            $resultID = mysqli_query($dbc, $queryID);
            $row = mysqli_fetch_array($resultID);

            if(!empty($row) && ($row['status'] = "PENDING" || $row['status'] = "APPROVED") ){

                    echo '<script language="javascript">';
                    echo 'alert("Your chosen ID is already in use by an Active/Pending User!" . $memid)';
                    echo '</script>';

            }else if (empty($_POST['fName']) || empty($_POST['lName'])){ // check if any of the names is empty 

                    echo '<script language="javascript">';
                    echo 'alert("You forgot to fill up a name portion!")';
                    echo '</script>';

            }else if( empty($_POST['sex'])){ // check if the sex is checked


                    echo '<script language="javascript">';
                    echo 'alert("Please choose a sex!")';
                    echo '</script>';


            }else if(empty($_POST['honum'])){// checks if any of the numbers are empty 


                    echo '<script language="javascript">';
                    echo 'alert("Please fill up a number portion!")';
                    echo '</script>';


            }else if(empty($_POST['haddress'])){ // checks if any of the adresses are empty 

                    echo '<script language="javascript">';
                    echo 'alert("Please fill up the addresses! ")';
                    echo '</script>';

            }
            
            else if(isset($_POST['hasFALP'])&&(empty($_POST['amount']) || empty($_POST['terms']))){
                    
                    
                    
               
                    
                    echo '<script language="javascript">';
                    echo 'alert("You forgot to fill up the FALP portion!")';
                    echo '</script>';
                        
                    }


                

            else if(isset($_POST['hasLifetime'])&&(empty($_POST['primary']) )){
                    echo '<script language="javascript">';
                    echo 'alert("You forgot to fill up the Life portion!")';
                    echo '</script>';
                    

                }
            else {

               $fName = $_POST['fName'];
               $mName = $_POST['mName'];
               $lName = $_POST['lName'];
               $sex2 = $_POST['sex'];
               $sex; 
               if($sex2 = "Male"){
                $sex = 1;

               }else{
                $sex = 2;
                }               

               $dept = $_POST['dept'];

               $civStat = $_POST['civStat'];

               $haddress = $_POST['haddress'];
               $honum = $_POST['honum'];
               $campus = $_POST['campus'];
               //set dates

               $birthdate = $bYear . "-" . $bMonth . "-" . $bDay;
               $datehired = $hYear . "-" . $hMonth . "-" . $hDay;
               $dateapp = $aYear . "-" . $aMonth . "-" . $aDay;
               $dateappl = $appYear . "-" . $appMonth . "-" . $appDay;
               $fdateapp = $fYear . "-" . $fMonth . "-" . $fDay;
               $fdateappl = $faYear . "-" . $faMonth . "-" . $faDay;
               $ldateapp = $lYear . "-" . $lMonth . "-" . $lDay;
               $ldateappl = $laYear . "-" . $laMonth . "-" . $laDay;
               if(!empty($_POST['bunum']) && !empty($_POST['baddress'])){ //if the business number is not empty
                     $bunum = $_POST['bunum'];
                     $baddress = $_POST['baddress'];         

               }

               else if(!empty($_POST['bunum']) ){ //if the bnum isnt empty
                    $bunum = $_POST['bunum'];

                  

               }

               else if(!empty($_POST['baddress'])){ // if the Business address isnt empty
                    $baddress = $_POST['baddress'];

          



               }

              $query1 = "UPDATE MEMBER 
              SET 
              FIRSTNAME ='{$fName}',
              LASTNAME = '{$lName}',
              CIV_STATUS = {$civStat},
              MIDDLENAME = '{$mName}',
              SEX = {$sex},
              BIRTHDATE = '{$birthdate}',
              HOME_NUM = {$honum},
              BUSINESS_NUM = {$bunum},
              HOME_ADDRESS = '{$haddress}',
              BUSINESS_ADDRESS = '{$baddress}',
              DEPT_ID = {$dept},
              EMP_TYPE = {$empStat},
              TYPE = '{$empType}',
              EMP_STATUS='{$empStatus}'
              WHERE MEMBER_ID = {$_SESSION['idnum']};";

              $result = mysqli_query($dbc,$query1);

            
                if(isset($_POST['hasFALP'])){
                    $falpPaid = '0';
                    
                    
                    
                        if(!empty($_POST['fAmountPaid'])){
                            $falpPaid = $_POST['fAmountPaid'];
                    }

                $query = "INSERT INTO loans(MEMBER_ID,LOAN_DETAIL_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED,DATE_APPROVED,PICKUP_STATUS,AMOUNT_PAID,EMP_ID)
                                  values({$_POST['idNum']},1,{$_POST['amount']},5,{$_POST['terms']},{$_POST['amount']}+{$_POST['amount']}*5/100,({$_POST['amount']}+{$_POST['amount']}*5/100)/{$_POST['terms']}/2,2,2,'{$fdateappl}','{$fdateapp}',{$_POST['pickupStatus']},{$falpPaid},99999999);";

                mysqli_query($dbc,$query);
                       
                    

                }
                if(isset($_POST['hasLifetime'])){
                    
                    
                        $primary = $_POST['primary'];
                        $secondary = 'null';
                        $org = 'null';
                        if(!empty($_POST['secondary'])){
                            $secondary = $_POST['secondary'];
                        }
                        if(!empty($_POST['org'])){
                            $org = "'".$_POST['org']."'";
                        }
                        $query4 = "INSERT INTO lifetime(MEMBER_ID,`PRIMARY`,SECONDARY,ORG,APP_STATUS,DATE_ADDED,EMP_ID) values({$_POST['idNum']},'{$primary}',{$secondary},{$org},2,'{$ldateapp}',99999999);";

                       mysqli_query($dbc,$query4);
                       
                    

                }
               

            }

    }
    $queryMember = "SELECT * FROM MEMBER where member_id = {$_SESSION['idnum']}";
    $resultMember = mysqli_query($dbc, $queryMember);
 $page_title = 'Profile ';
include 'GLOBAL_HEADER.php';
if($_SESSION['FRAP_ROLE']!=1)
include 'FRAP_ADMIN_SIDEBAR.php';
else
include 'FRAP_USER_SIDEBAR.php';
?>

<body>
    <button onclick="topFunction()" id="scrollToTop" title="Go to top">Scroll to Top</button>
    
        
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Member
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>
                        
                        <!--Insert success page--> 
                        
                        <form method="POST" action="FA_Profile.php" id="addAccount" onSubmit="return checkform()">

                            <div class="panel panel-green" name = "personalInfo">

                                <div class="panel-heading">

                                    <b>Personal Information </b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">
                                                <span class="labelspan"><b>ID Number </b><big class="req"> *</big></span>
                                                <input type="text" minlength = "8" maxlength="8" class="form-control memname" placeholder="e.g. 09000000" name="idNum" <?php 
                                                    $rowMember = mysqli_fetch_assoc($resultMember);
                                                    echo "value = '{$rowMember['MEMBER_ID']}'";
                                                 ?>
                                                disabled>
                                                </label>

                                                <label>
                                                <span class="labelspan">Last Name<big class="req"> *</big>
                                                <input type="text" class="form-control memname" placeholder="Last Name" name="lName" <?php 
                                                  echo "value = '{$rowMember['LASTNAME']}'";
                                                 ?>>
                                                </label>

                                                <label>
                                                <span class="labelspan">First Name<big class="req"> *</big></span>
                                                <input type="text" class="form-control memname" placeholder="First Name" name="fName"  <?php 
                                                    echo "value = '{$rowMember['FIRSTNAME']}'";
                                                 ?>>
                                                </label>

                                                <label>
                                                <span class="labelspan">Middle Name</span>
                                                <input type="text" class="form-control memname" placeholder="Middle Name" name="mName" <?php 
                                                  echo "value = '{$rowMember['MIDDLENAME']}'";
                                                ?>>
                                                </label>
                                                <div>
                                                 <span class="labelspan">DLSU Email<big class="req"> *</big>
                                                <input type="text" class="form-control memname" placeholder="Email" name="email" <?php 
                                                  echo "value = '{$rowMember['EMAIL']}'";
                                                 ?> disabled>
                                                </span>
                                              </div>
                                               

                                        </div>

                                    </div>

                                 

                                    <div class="row">

                                        <div class="col-lg-2">

                                                <label class="memfieldlabel">Civil Status</label>
                                                <select class="form-control" name ="civStat">

                                                    <?php foreach($resultCiv as $civstatusArray) { ?>

                                                        <option value="<?php echo $civstatusArray['STATUS_ID']; ?>" <?php 
                                                            if($civstatusArray['STATUS_ID'] == $rowMember['CIV_STATUS'])
                                                                ECHO "selected";
                                                        ?> > <?php echo $civstatusArray['STATUS'] ?></option>

                                                    <?php } ?>

                                                </select>

                                        </div>

                                    </div><p>

                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date of Birth</b><big class="req"> *</big></p>

                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "bYear">

                                                    <?php for($y = 2025; $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>" <?php if(date('Y', strtotime($rowMember['BIRTHDATE'])) == $y) echo "selected";?>><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>


                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "bMonth">

                                                   <option value = 1 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 1) echo "selected";?>>January</option>
                                                    <option value = 2 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 2) echo "selected";?>>February</option>
                                                    <option value = 3 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 3) echo "selected";?>>March</option>
                                                    <option value = 4 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 4) echo "selected";?>>April</option>
                                                    <option value = 5 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 5) echo "selected";?>>May</option>
                                                    <option value = 6 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 6) echo "selected";?>>June</option>
                                                    <option value = 7 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 7) echo "selected";?>>July</option>
                                                    <option value = 8 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 8) echo "selected";?>>August</option>
                                                    <option value = 9 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 9) echo "selected";?>>September</option>
                                                    <option value = 10 <?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 10) echo "selected";?>>October</option>
                                                    <option value = 11<?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 11) echo "selected";?>>November</option>
                                                    <option value = 12<?php if(date('m', strtotime($rowMember['BIRTHDATE'])) == 12) echo "selected";?>>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "bDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>" <?php if(date('d', strtotime($rowMember['BIRTHDATE'])) == $x) echo "selected";?>><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>
                                               


                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <p id="glabel"><b>Sex</b></p>
                                            <div class="radio">
                                                <?php 
                                                $female = null;
                                                $male = null;
                                                    if($rowMember['SEX']==1){
                                                        $male = "checked";

                                                    }
                                                    else{
                                                        $female = "checked";
                                                    }
                                                ?>
                                                <label><input type="radio" name="sex" value = "male" <?php echo $male;?>>Male</label>
                                            </div>

                                            <div class="radio" >
                                                <label><input type="radio" name="sex" value = "female" <?php echo $female;?>>Female</label>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Member since:</b><big class="req"> *</big></p>

                                                 <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "aYear" disabled>

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>" <?php if(date('Y', strtotime($rowMember['DATE_APPROVED'])) == $y) echo "selected";?>><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    

                                                </select>
                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "aMonth" disabled>
                                                    <?php $selected = date('m', strtotime($rowMember['DATE_APPROVED']));?>
                                                    <option value = 1 <?php if($selected ==1)echo "selected";?>>January</option>
                                                    <option value = 2 <?php if($selected ==2)echo "selected";?>>February</option>
                                                    <option value = 3 <?php if($selected ==3)echo "selected";?>>March</option>
                                                    <option value = 4 <?php if($selected ==4)echo "selected";?>>April</option>
                                                    <option value = 5 <?php if($selected ==5)echo "selected";?>>May</option>
                                                    <option value = 6 <?php if($selected ==6)echo "selected";?>>June</option>
                                                    <option value = 7 <?php if($selected ==7)echo "selected";?>>July</option>
                                                    <option value = 8 <?php if($selected ==8)echo "selected";?>>August</option>
                                                    <option value = 9 <?php if($selected ==9)echo "selected";?>>September</option>
                                                    <option value = 10 <?php if($selected ==10)echo "selected";?>>October</option>
                                                    <option value = 11 <?php if($selected ==11)echo "selected";?>>November</option>
                                                    <option value = 12 <?php if($selected ==12)echo "selected";?>>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "aDay" disabled>

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>" <?php if(date('d', strtotime($rowMember['DATE_APPROVED'])) == $x) echo "selected";?>><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>
                                               




                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date Applied:</b><big class="req"> *</big></p>

                                               <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "appYear" disabled>

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>" <?php if(date('Y', strtotime($rowMember['DATE_APPROVED'])) == $y) echo "selected";?>><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    
                                                </select>

                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "appMonth" disabled>

                                                   
                                                    <?php $selected = date('m', strtotime($rowMember['DATE_APPLIED']));?>
                                                    <option value = 1 <?php if($selected ==1)echo "selected";?>>January</option>
                                                    <option value = 2 <?php if($selected ==2)echo "selected";?>>February</option>
                                                    <option value = 3 <?php if($selected ==3)echo "selected";?>>March</option>
                                                    <option value = 4 <?php if($selected ==4)echo "selected";?>>April</option>
                                                    <option value = 5 <?php if($selected ==5)echo "selected";?>>May</option>
                                                    <option value = 6 <?php if($selected ==6)echo "selected";?>>June</option>
                                                    <option value = 7 <?php if($selected ==7)echo "selected";?>>July</option>
                                                    <option value = 8 <?php if($selected ==8)echo "selected";?>>August</option>
                                                    <option value = 9 <?php if($selected ==9)echo "selected";?>>September</option>
                                                    <option value = 10 <?php if($selected ==10)echo "selected";?>>October</option>
                                                    <option value = 11 <?php if($selected ==11)echo "selected";?>>November</option>
                                                    <option value = 12 <?php if($selected ==12)echo "selected";?>>December</option>
                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "appDay" disabled>

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>" <?php if(date('d', strtotime($rowMember['DATE_APPLIED'])) == $x) echo "selected";?>><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>
                                                 


                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <!--<span class="labelspan"><b>ID of Employee Hired</b><big class="req"> *</big></span>
                                                <input type="text" minlength = "8" maxlength="8" class="form-control memname" placeholder="e.g. 09000000" name="emp_ID" <?php if(isset($_POST['emp_ID'])){
                                                    echo "value = '{$_POST['emp_ID']}'";
                                                } ?>>
                                                </label>-->

                                                 
                                        </div>

                                    </div>


                                </div>

                            </div>

                            <div class="panel panel-green" name = "emplymentInfo">

                                <div class="panel-heading">

                                    <b>Employment Information</b>

                                </div>

                                <div class="panel-body">
                                    <div class="row">

                                        <div class="col-lg-2">
                                                <label class="memfieldlabel">Employment Category</label>
                                                <select class="form-control" name ="empStat" disabled>

                                                    <?php 
                                                    $selected = $rowMember['EMP_TYPE'];
                                                    ?>
                                                        <option value = 1 <?php if($selected ==1)echo "selected";?>>Full-Time</option>
                                                        <option value = 2 <?php if($selected ==2)echo "selected";?>>Part-Time</option>
                                        
                                                </select>
                                                <label class="memfieldlabel">Employment Type</label>
                                                <select class="form-control" name ="empType" >

                                                     <?php 
                                                    $selected = $rowMember['TYPE'];
                                                    ?>
                                                        <option value = "Teaching" <?php if($selected =="Teaching")echo "selected";?>>Teaching</option>
                                                        <option value = "ASF" <?php if($selected =="ASF")echo "selected";?>>Academic Service Faculty</option>
    
                                                </select>
                                                <label class="memfieldlabel">Employment Status</label>
                                                <select class="form-control" name ="empStatus" >
 <?php 
                                                    $selected = $rowMember['EMP_STATUS'];
                                                    ?>
                                                    
                                                        <option value = "Permanent"<?php if($selected =="Permanent")echo "selected";?>>Permanent</option>
                                                        <option value = "Probationary"<?php if($selected =="Probationary")echo "selected";?>>Probationary</option>
                                                        <option value = "Contractual"<?php if($selected =="Contractual")echo "selected";?>>Contractual</option>
                                                        
                                                </select>

                                        </div>

                                    </div><p>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Term Hired</b></p>


                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown" name = "hYear" disabled>

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>" <?php if(date('Y', strtotime($rowMember['DATE_HIRED'])) == $y) echo "selected";?>><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>





                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name = "hMonth" disabled>

                                                
                                                    <?php $selected = date('m', strtotime($rowMember['DATE_HIRED']));?>
                                                    <option value = 1 <?php if($selected ==1)echo "selected";?>>January</option>
                                                    <option value = 2 <?php if($selected ==2)echo "selected";?>>February</option>
                                                    <option value = 3 <?php if($selected ==3)echo "selected";?>>March</option>
                                                    <option value = 4 <?php if($selected ==4)echo "selected";?>>April</option>
                                                    <option value = 5 <?php if($selected ==5)echo "selected";?>>May</option>
                                                    <option value = 6 <?php if($selected ==6)echo "selected";?>>June</option>
                                                    <option value = 7 <?php if($selected ==7)echo "selected";?>>July</option>
                                                    <option value = 8 <?php if($selected ==8)echo "selected";?>>August</option>
                                                    <option value = 9 <?php if($selected ==9)echo "selected";?>>September</option>
                                                    <option value = 10 <?php if($selected ==10)echo "selected";?>>October</option>
                                                    <option value = 11 <?php if($selected ==11)echo "selected";?>>November</option>
                                                    <option value = 12 <?php if($selected ==12)echo "selected";?>>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown" name = "hDay" disabled>

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>" <?php if(date('d', strtotime($rowMember['DATE_HIRED'])) == $x) echo "selected";?>><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>

                                                

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Department</label>
                                            <select class="form-control gendropdown" name = "dept">

                                               <?php foreach($resultDept as $deptArray) { ?>

                                                    <option value="<?php echo $deptArray['DEPT_ID']; ?>" <?php 
                                                            if($deptArray['DEPT_ID'] == $rowMember['DEPT_ID'])
                                                                ECHO "selected";
                                                        ?>><?php echo $deptArray['DEPT_NAME']; ?></option>

                                                <?php } ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Contact Information</b>
                                </div>

                                <div class="panel-body">

                                  

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Home Phone Number</label><big class="req"> *</big>
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="honum" <?php 
                                                    echo "value = '{$rowMember['HOME_NUM']}'";
                                                ?>>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Phone Number</label>
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="bunum" <?php 
                                            echo "value = '{$rowMember['BUSINESS_NUM']}'";
                                                 ?>>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Home Address</label><big class="req"> *</big>
                                            <textarea class="form-control memfields address" placeholder="Address" name="haddress" rows="2"  ><?php echo "{$rowMember['HOME_ADDRESS']}";
                                                ?></textarea> 

                                        </div>

                                    </div>

                                    <div class="row" id = "falpInfo">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Address</label>
                                            <textarea  class="form-control memfields address" placeholder="Address" name="baddress" rows="2" ><?php echo "{$rowMember['BUSINESS_ADDRESS']}";
                                                 ?></textarea> 

                                        </div>

                                    </div>

                                </div>

                            </div>

                            
                            

                            </div>
                            

                            <input id = "submit"  type="submit" name="submit" value="Sumbit"></p>

                       </form>

                    </div>
                </div>


            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
       
        
   
    <?php /*if (!empty($success)) {
        
        echo "<script type='text/javascript'>alert('Success!');</script>";
        
    }
    else if(empty($success) && isset($_POST['submit'])){
        $string = 'Failed to Add!';
        if(!empty($failedFalp)){
            $string = $string.'Missing fields in FALP';
        }
        if(!empty($failedLife)){
            $string = $string.'Missing fields in Lifetime';
        }
        echo "<script type='text/javascript'>alert('{$string}');</script>";
        
    }
*/

    ?>
    <!-- Bootstrap Core JavaScript -->
    
<script>
        document.getElementById("falpcompute").onclick = function() {calculate()};
        
        function calculate(){
            
            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);
            var interest = 5;
            
            document.getElementById("totalI").innerHTML ="<b>Total Interest Payable: </b>₱"+ parseFloat((amount*interest/100)).toFixed(2);
            document.getElementById("totalP").innerHTML ="<b>Total Amount Payable: </b> ₱"+ parseFloat((amount+amount*interest/100)).toFixed(2);
            document.getElementById("PerP").innerHTML ="<b>Per Payment Period Payable: </b> ₱ "+ parseFloat(((amount+amount*interest/100)/terms/2)).toFixed(2);
            document.getElementById("Monthly").innerHTML ="<b>Monthly Payable: </b> ₱"+ parseFloat(((amount+amount*interest/100)/terms)).toFixed(2);
            
        }
        
        function checkform(){
            
            var amount = parseFloat(document.getElementById("amount").value);
            var terms = parseFloat(document.getElementById("terms").value);
           
            return true;
            
        }
    </script>
    <!-- Scroll to top script-->
    <div id ="scrollToTopScript">
            </div>

</body>

</html>
