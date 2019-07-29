<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register Account</title>

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
<link rel="stylesheet" href="dialog.css">
<script>
function submitModal() {

    
        var idNum = document.getElementById('idNum').value +"</br>";
        var name = document.getElementsByName('lName')[0].value +","+document.getElementsByName('fName')[0].value +" "+document.getElementsByName('mName')[0].value +"</br>";
           
        var email =   document.getElementsByName('email')[0].value +"</br>";
        var civStat =  document.getElementsByName('civStat')[0].options[ document.getElementsByName('civStat')[0].value-1].text +"</br>";
        var bDate =   document.getElementsByName('bYear')[0].value +" "+document.getElementsByName('bMonth')[0].options[ document.getElementsByName('bMonth')[0].value-1].text+" "+document.getElementsByName('bDay')[0].value+"</br>";
        var sex =   document.getElementsByName('sex')[0].value +"</br>";
        var aDate  = document.getElementsByName('aYear')[0].value +" "+document.getElementsByName('aMonth')[0].options[ document.getElementsByName('aMonth')[0].value-1].text+" "+document.getElementsByName('aDay')[0].value+"</br>";
        var appDate  = document.getElementsByName('appYear')[0].value +" "+document.getElementsByName('appMonth')[0].options[ document.getElementsByName('appMonth')[0].value-1].text+" "+document.getElementsByName('appDay')[0].value+"</br>";
        var empStat =    $("input[name='empStat']:checked").parent('label').text() +"</br>";
        var empType =    $("input[name='empType']:checked").parent('label').text() +"</br>";
        var empStatus =    $("input[name='empStatus']:checked").parent('label').text() +"</br>";
        var hDate  = document.getElementsByName('hYear')[0].value +" "+document.getElementsByName('hMonth')[0].options[ document.getElementsByName('hMonth')[0].value-1].text+" "+document.getElementsByName('hDay')[0].value+"</br>";
        var honum =    document.getElementsByName('honum')[0].value +"</br>";
        var bunum =    document.getElementsByName('bunum')[0].value +"</br>";
        var haddress =    document.getElementsByName('haddress')[0].value +"</br>";
        var baddress =    document.getElementsByName('baddress')[0].value +"</br>";
        var lifeInfo = "";
        if(document.getElementsByName('hasLifetime')[0].checked){
            var primary = document.getElementsByName('primary')[0].value +"</br>";
            var secondary = document.getElementsByName('secondary')[0].value +"</br>";
            var org = document.getElementsByName('org')[0].value +"</br>";
            var laDate = document.getElementsByName('laYear')[0].value +" "+document.getElementsByName('laMonth')[0].options[ document.getElementsByName('laMonth')[0].value-1].text+" "+document.getElementsByName('laDay')[0].value+"</br>";
            var lDate = document.getElementsByName('lYear')[0].value +" "+document.getElementsByName('lMonth')[0].options[ document.getElementsByName('lMonth')[0].value-1].text+" "+document.getElementsByName('lDay')[0].value+"</br>";

            lifeInfo = 
            "</br><b style = 'font-size:20px;'>Lifetime Information</b></br>"+
            "<b>Primary:</b> "+primary+
            "<b>Secondary:</b> "+secondary+
            "<b>Organization:</b> "+org+
            "<b>Date Applied:</b> "+laDate+
            "<b>Date Approved:</b> "+lDate;
        }
        document.getElementById('dialogboxbody1').innerHTML = 
        "<b style = 'font-size:20px;'>Personal Information</b></br>"+
        "<b>ID Number:</b> "+idNum+
        "<b>Name: </b>"+name+
        "<b>Email:</b> "+email+
        "<b>Civil Status: </b>"+civStat+
        "<b>Date of Birth: </b></b>"+bDate+
        "<b>Sex: </b></b>"+sex+
        "<b>Member Since:</b> "+aDate+
        "<b>Date applied:</b> "+appDate+
        "<br><b style = 'font-size:20px;'>Employment Information</b>"+
        "</br><b>Employment Category:</b> "+empStat+
        "<b>Employment Type:</b> "+empType+
        "<b>Employment Status:</b> "+empStatus+
        "<b>Hired Date:</b> "+hDate+
        "<br><b style = 'font-size:20px;'>Contact Information</b></br>"+
        "<b>Home Number:</b> "+honum+
        "<b>Business Number:</b> "+bunum+
        "<b>Home Address:</b> "+haddress+
        "<b>Business Address:</b> "+baddress
        +lifeInfo
           
        ;
        
        document.getElementById('dialogboxfoot1').innerHTML = 'Is the information correct? <button type = button onclick="closeModal()" class = "btn btn-danger">No</button> <button class = "btn btn-success" name = "submit" id = "submit">Yes</button>';

$('#exampleModalScrollable').modal('show',$(this));  

    }


</script>
<script>
    function closeModal(){
    $('#exampleModalScrollable').modal('toggle');
}    

</script>
    <script> $(function(){
        $('#scrollToTopScript').load('scrollToTop.html');
     });
    </script>
</head>

<?php
error_reporting(0);
session_start();
require_once("mysql_connect_FA.php");


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

            $idNum = $_POST['idNum']; 
           
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

            if(empty($idNum) ){

                    echo '<script language="javascript">';
                    echo 'alert("You forgot to fill up the ID portion!")';
                    echo '</script>';
                    
            
            }else if(!empty($row) && ($row['status'] = "PENDING" || $row['status'] = "APPROVED") ){

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
            else if(empty($_POST['email'])){ // checks if any of the adresses are empty 

                    echo '<script language="javascript">';
                    echo 'alert("Please put your  DLSU email address! ")';
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

               
                 $query1 = "INSERT INTO MEMBER (MEMBER_ID, FIRSTNAME, LASTNAME, CIV_STATUS,  MIDDLENAME,SEX, BIRTHDATE ,DATE_HIRED, HOME_NUM, HOME_ADDRESS, DEPT_ID, USER_STATUS,MEMBERSHIP_STATUS,DATE_APPLIED,DATE_APPROVED,EMP_ID_APPROVE,EMAIL,EMP_TYPE,TYPE,EMP_STATUS) 
                        VALUES ('{$idNum}','{$fName}','{$lName}',{$civStat}, '{$mName}','{$sex}','{$birthdate}','{$datehired}','{$honum}','{$haddress}',{$dept},{$_POST['empStat']},1,'{$dateappl}','{$dateapp}','99999999','{$_POST['email']}',{$_POST['empStat']},'{$empType}','{$empStatus}')"; 

                    $result = mysqli_query($dbc,$query1); 


                if(isset($_POST['hasFALP'])){
                    $falpPaid = '0';
                    
                    
                    
                        if(!empty($_POST['fAmountPaid'])){
                            $falpPaid = $_POST['fAmountPaid'];
                        }

                        $query = "INSERT INTO loans(MEMBER_ID,LOAN_DETAIL_ID,AMOUNT,INTEREST,PAYMENT_TERMS,PAYABLE,PER_PAYMENT,APP_STATUS,LOAN_STATUS,DATE_APPLIED,DATE_APPROVED,PICKUP_STATUS,AMOUNT_PAID,EMP_ID)
                                  values({$_POST['idNum']},1,{$_POST['amount']},5,{$_POST['terms']},{$_POST['amount']}+{$_POST['amount']}*5/100,({$_POST['amount']}+{$_POST['amount']}*5/100)/{$_POST['terms']}/2,1,2,'{$fdateappl}','{$fdateapp}',{$_POST['pickupStatus']},{$falpPaid},99999999);";

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
                        $query4 = "INSERT INTO lifetime(MEMBER_ID,`PRIMARY`,SECONDARY,ORG,APP_STATUS,DATE_ADDED,EMP_ID) values({$_POST['idNum']},'{$primary}',{$secondary},{$org},1,'{$ldateapp}',99999999);";

                       mysqli_query($dbc,$query4);
                       
                    

                }
                $query3 = " SELECT * FROM MEMBER WHERE MEMBER_ID = {$_POST['idNum']}";

                $result = mysqli_query($dbc,$query3);
                $row = mysqli_fetch_assoc($result);
                if(!empty($row)){
                  $success = "yes";
                }



            }

    }
 $page_title = 'Register Account ';


?>

<body>
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header">

                <a href = "index.php" >
                    <img src="images/I-FA Logo Edited.png" id="ifalogo">
                </a>
            </div>

        </nav>
    <button onclick="topFunction()" id="scrollToTop" title="Go to top">Scroll to Top</button>
    
        
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Register Account

                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>
                       
                        <a href="#lifetimeInfo">Jump to Lifetime</a>
                        <!--Insert success page--> 
                        
                        <form method="POST" action="FA membership.php" id="addAccount" onSubmit="return checkform()">

                            <div class="panel panel-green" name = "personalInfo">

                                <div class="panel-heading">

                                    <b>Personal Information</b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">
                                                <label>
                                                <span class="labelspan"><b>ID Number</b><big class="req"> *</big></span>

                                                <input type="text" minlength = "8" maxlength="8" class="form-control memname" id = "idNum" placeholder="e.g. 09000000" name="idNum" <?php if(isset($_POST['idNum'])){
                                                    echo "value = '{$_POST['idNum']}'";
                                                } ?>
                                                       required>
                                                </label>
                                                <label ><div  id="chk"></div></label>
                                                    <br>
                                                <label>
                                                <span class="labelspan">Last Name<big class="req"> *</big>
                                                <input type="text" class="form-control memname" placeholder="Last Name" name="lName" id="lName"<?php if(isset($_POST['lName'])){
                                                  echo "value = '{$_POST['lName']}'";
                                                } ?> required>
                                                </label>

                                                <label>
                                                <span class="labelspan">First Name<big class="req"> *</big></span>
                                                <input type="text" class="form-control memname" placeholder="First Name" name="fName"  <?php if(isset($_POST['fName'])){
                                                    echo "value = '{$_POST['fName']}'";
                                                } ?> required>
                                                </label>

                                                <label>
                                                <span class="labelspan">Middle Name</span>
                                                <input type="text" class="form-control memname" placeholder="Middle Name" name="mName" <?php if(isset($_POST['mName'])){
                                                  echo "value = '{$_POST['mName']}'";
                                                } ?>>
                                                </label>
                                                 <br>
                                                <label>
                                                <span class="labelspan">DLSU Email<big class="req"> *</big>
                                                <input type="text" class="form-control memname" placeholder="Email" name="email" <?php if(isset($_POST['email'])){
                                                  echo "value = '{$_POST['email']}'";
                                                } ?> required>
                                                </label>

                                               

                                        </div>

                                    </div>

                                 

                                    <div class="row">

                                        <div class="col-lg-2">

                                                <label class="memfieldlabel">Civil Status</label>
                                                <select class="form-control" name ="civStat" required>

                                                    <?php foreach($resultCiv as $civstatusArray) { ?>

                                                        <option value="<?php echo $civstatusArray['STATUS_ID'] ?>"><?php echo $civstatusArray['STATUS'] ?></option>

                                                    <?php } ?>

                                                </select>

                                        </div>

                                    </div><p>

                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date of Birth</b><big class="req" > *</big></p>

                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "bYear" required>

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>


                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "bMonth" required>

                                                   <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "bDay" required>

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>
                                               


                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <p id="glabel"><b>Sex</b></p>
                                            <div class="radio">
                                                <label><input type="radio" name="sex" value = "male" required <?php if(isset($_POST['sex'])){
                                                    if($_POST['sex']=='male')
                                                        echo "checked";

                                                } ?>>Male</label>
                                            </div>

                                            <div class="radio" >
                                                <label><input type="radio" name="sex" value = "female" <?php if(isset($_POST['sex'])){
                                                    if($_POST['sex']=='female')
                                                        echo "checked";

                                                } ?>>Female</label>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Member since:</b><big class="req"> *</big></p>

                                                 <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "aYear">

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    

                                                </select>
                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "aMonth">

                                                    <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "aDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>
                                               




                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date Applied:</b><big class="req"> *</big></p>

                                               <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "appYear">

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    
                                                </select>

                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "appMonth">

                                                    <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>
                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "appDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

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
                                                <div class="radio">
                                                <label><input type="radio" name="empStat" value = 1 required <?php if(isset($_POST['empStat'])){
                                                    if($_POST['empStat']==1)
                                                        echo "checked";

                                                } ?>>Full Time</label>
                                            </div>

                                             <div class="radio">
                                                <label><input type="radio" name="empStat" value = 2  <?php if(isset($_POST['empStat'])){
                                                    if($_POST['empStat']==2)
                                                        echo "checked";

                                                } ?>>Part Time</label>
                                            </div>
                                                <label class="memfieldlabel">Employment Type</label>
                                                <div class="radio">
                                                <label><input type="radio" name="empType" value = "Teaching" required <?php if(isset($_POST['empType'])){
                                                    if($_POST['empType']=="Teaching")
                                                        echo "checked";

                                                } ?>>Teaching</label>
                                            </div>

                                             <div class="radio">
                                                <label><input type="radio" name="empType" value = "ASF"  <?php if(isset($_POST['empType'])){
                                                    if($_POST['empType']=="ASF")
                                                        echo "checked";

                                                } ?>>Academic Service Faculty</label>
                                            </div>

                                                <label class="memfieldlabel">Employment Status</label>
                                                <div class="radio">
                                                <label><input type="radio" name="empStatus" value = "Permanent" required <?php if(isset($_POST['empStatus'])){
                                                    if($_POST['empStatus']=="Permanent")
                                                        echo "checked";

                                                } ?>>Permanent</label>
                                                    </div>
                                                <div class="radio">
                                                <label><input type="radio" name="empStatus" value = "Probationary"  <?php if(isset($_POST['empStatus'])){
                                                    if($_POST['empStatus']=="Probationary")
                                                        echo "checked";

                                                } ?>>Probationary</label>
                                                    </div>
                                                <div class="radio">
                                                <label><input type="radio" name="empStatus" value = "Contractual"  <?php if(isset($_POST['empStatus'])){
                                                    if($_POST['empStatus']=="Contractual")
                                                        echo "checked";

                                                } ?>>Contractual</label>
                                                    </div>

                                        </div>

                                    </div><p>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Term Hired</b></p>


                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown" name = "hYear">

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>





                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name = "hMonth">

                                                
                                                    <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown" name = "hDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>

                                                

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Department</label>
                                            <select class="form-control gendropdown" name = "dept">

                                               <?php foreach($resultDept as $deptArray) { ?>

                                                    <option value="<?php echo $deptArray['DEPT_ID']; ?>"><?php echo $deptArray['DEPT_NAME']; ?></option>

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
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="honum" <?php if(isset($_POST['honum'])){
                                                    echo "value = '{$_POST['honum']}'";
                                                } ?>>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Phone Number</label>
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="bunum" <?php if(isset($_POST['bunum'])){
                                                    echo "value = '{$_POST['bunum']}'";
                                                } ?>>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Home Address</label><big class="req"> *</big>
                                            <textarea class="form-control memfields address" placeholder="Address" name="haddress" rows="2"  ><?php if(isset($_POST['haddress'])){
                                                    echo $_POST['haddress'];
                                                } ?></textarea> 

                                        </div>

                                    </div>

                                    <div class="row" id = "falpInfo">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Address</label>
                                            <textarea  class="form-control memfields address" placeholder="Address" name="baddress" rows="2" ><?php if(isset($_POST['baddress'])){
                                                    echo $_POST['baddress'];
                                                } ?></textarea> 

                                        </div>

                                    </div>

                                </div>

                            </div>

                            
                                    
                            <div class="panel panel-green" id = "lifetimeInfo">

                                <div class="panel-heading">

                                    <b>Lifetime Information</b>
                                </div>

                                <div class="panel-body">
                                    <div class="row">

                                        <div class="col-lg-4">

                                           
                                             <input type="checkbox" name="hasLifetime" value="1" <?php if(isset($_POST['hasLifetime'])){
                                                    echo "checked";
                                                } ?>>Check box if you have lifetime<p>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-4">

                                            <label class="memfieldlabel">Primary Beneficiary</label><big class="req">*</big>
                                            <input type="text" class="form-control" name="primary" id="primary" <?php if(isset($_POST['primary'])){
                                                    echo "value = '{$_POST['primary']}'";
                                                } ?>>

                                        </div>

                                    </div>

                                    <p>
                                    
                                    <div class="row">

                                        <div class="col-lg-4">
                                            
                                            <label class="memfieldlabel">Secondary Beneficiary</label>
                                            <input type="text" class="form-control"  name="secondary"  id="secondary" <?php if(isset($_POST['secondary'])){
                                                    echo "value = '{$_POST['secondary']}'";
                                                } ?>>
                                           

                                            
                                        </div>

                                    </div>
                                     <div class="row">

                                        <div class="col-lg-4">

                                            <label class="memfieldlabel">Organization</label>
                                            <input type="text" class="form-control"  name="org" id="org" <?php if(isset($_POST['org'])){
                                                    echo "value = '{$_POST['org']}'";
                                                }?>>

                                        </div>

                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date Applied</b></p>


                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown" name = "laYear">

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    

                                                </select>





                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name = "laMonth">

                                                
                                                  <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown" name = "laDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>

                                                

                                        </div>
                                    </div>
                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date Approved</b></p>


                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown" name = "lYear">

                                                    <?php for($y = date("Y"); $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>
                                                    

                                                </select>





                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name = "lMonth">

                                                
                                                   <option value = 1>January</option>
                                                    <option value = 2>February</option>
                                                    <option value = 3>March</option>
                                                    <option value = 4>April</option>
                                                    <option value = 5>May</option>
                                                    <option value = 6>June</option>
                                                    <option value = 7>July</option>
                                                    <option value = 8>August</option>
                                                    <option value = 9>September</option>
                                                    <option value = 10>October</option>
                                                    <option value = 11>November</option>
                                                    <option value = 12>December</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown" name = "lDay">

                                                    <?php for($x = 1; $x <= 31; $x++) { ?>

                                                        <option value="<?php echo $x; ?>"><?php echo $x; ?></option>

                                                    <?php } ?>

                                                </select>

                                                

                                        </div>
                                        <div class="col-lg-4">
                                             <!--<span class="labelspan"><b>ID of Employee Approved</b></span><big class="req">*</big>
                                                <input type="text" minlength = "8" maxlength="8" class="form-control memname" placeholder="e.g. 09000000" name="lEmp_ID" <?php if(isset($_POST['lEmp_ID'])){
                                                    echo "value = '{$_POST['lEmp_ID']}'";
                                                } ?>>
                                                </label>-->
      

                                    </div>
                                    </div>
                                    

                                </div>

                            </div>
                            <div align = "center" style = "font-size: 20px;">
                                Please check your Email for more instructions
                            </div>
                            <div id="dialogoverlay"></div>
                            <div id="dialogbox">
  <div>
    <div id="dialogboxhead"></div>
    <div id="dialogboxbody"></div>
    <div id="dialogboxfoot"></div>
  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header" style = "background: #47d147;color:#fff;">
        <h1 class="modal-title" id="exampleModalScrollableTitle" style = "font-size:20px;">Please check the information provided</h1>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="dialogboxbody1">
        
      </div>
      <div class="modal-footer" id="dialogboxfoot1" style = "background: #47d147;">
        
      </div>
    </div>
  </div>
</div>

<div style = "position:relative;top:30px;left:50%">
<button type = "button" class = "btn btn-success" onclick="submitModal()" >Submit</button>

                            <!--<input id = "submit"  type="submit" name="submit" value="Sumbit"></p>-->
</div>
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
       
        
   
    <?php if (!empty($success)) {
        
        echo "<script type='text/javascript'>alert('Success!');</script>";

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
        
    }
    else if(empty($success) && isset($_POST['submit'])){
        $string = 'Failed to Add!';
        if(!empty($failedFalp)){
            $string = $string.'Missing fields in FALP';
        }
        if(!empty($failedLife)){
            $string = $string.'Missing fields in Lifetime';
        }
        else{
            $string = $string.'Possible error is date is unrealistic';
        }
        echo "<script type='text/javascript'>alert('{$string}');</script>";
        
    }


    ?>
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
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

<script>
    $(document.getElementById("idNum")).keyup(function(){
         var xhttp;    
         var str;
         str = document.getElementById("idNum").value;
          if (str.length<8) {
            document.getElementById("chk").innerHTML = "";
            return;
          }
          xhttp = new XMLHttpRequest();
          xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              document.getElementById("chk").innerHTML = this.responseText;
              
            }
          };
          xhttp.open("GET", "getIDNum.php?id="+str, true);
          xhttp.send();
  
});
    
         
</script>
    <!-- Scroll to top script-->
    <div id ="scrollToTopScript">
            </div>

</body>

</html>
