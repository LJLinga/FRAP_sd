
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>FRAP | Account Creation Information</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<?php
    
    require_once("mysql_connect_FA.php");   

    $queryDept = "SELECT * FROM REF_DEPARTMENT";
    $resultDept = mysqli_query($dbc, $queryDept);

    $queryCiv = "SELECT * FROM CIV_STATUS";
    $resultCiv = mysqli_query($dbc, $queryCiv);

    if (isset($_POST['submit'])) {

            $idNum = $_POST['idNum']; 
            
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
            $dept = NULL;

         
            $honum = NULL;
            $bunum = NULL;
            // hadresses
            $haddress = NULL;
            $badress = NULL;

           

           
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

            }else if (empty($_POST['fName']) || empty($_POST['lName']) || empty($_POST['mName'])){ // check if any of the names is empty 

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

            }else {

               $fName = $_POST['fName'];
               $mName = $_POST['mName'];
               $lName = $_POST['lName'];
               $sex2 = $_POST['sex'];
               $sex; 
               if($sex2 = "Male"){
                $sex = 1;

               }else{
                $sex = 0;
                }               

               $dept = $_POST['dept'];

               $civStat = $_POST['civStat'];

               $haddress = $_POST['haddress'];
               $honum = $_POST['honum'];
               $birthdate = $bYear . "-" . $bMonth . "-" . $bDay;
               $datehired = $hYear . "-" . $hMonth . "-" . $hDay;

               if(!empty($_POST['bunum']) && !empty($_POST['baddress'])){
                     $bunum = $_POST['bunum'];
                     $baddress = $_POST['baddress'];


                     $query = "INSERT INTO MEMBER (MEMBER_ID, FIRSTNAME, LASTNAME,CIV_STATUS, MIDDLENAME,SEX, BIRTHDATE ,DATE_HIRED, HOME_NUM, BUSINESS_NUM, HOME_ADDRESS, BUSINESS_ADDRESS, 
                          DEPT_ID,USER_STATUS,MEMBERSHIP_STATUS,DATE_APPLIED) VALUES ('{$idNum}','{$fName}','{$lName}',{$civStat},'{$mName}',{$sex},'{$birthdate}','{$datehired}',{$honum},{$bunum},'{$haddress}','{$baddress}',{$dept},1,1,NOW())"; 

                      $result = mysqli_query($dbc,$query);

                      $pw = "password";

                      $query2 = "INSERT INTO MEMBER_ACCOUNT (MEMBER_ID, PASSWORD, FIRST_CHANGE_PW) VALUES ('{$idNum}', PASSWORD('{$pw}'), '0');";
                $result2 = mysqli_query($dbc, $query2); 

               }

               else if(!empty($_POST['bunum']) ){
                    $bunum = $_POST['bunum'];

                    $query = "INSERT INTO MEMBER (MEMBER_ID, FIRSTNAME, LASTNAME,MIDDLENAME,CIV_STATUS, ,SEX, BIRTHDATE ,DATE_HIRED, HOME_NUM, BUSINESS_NUM, HOME_ADDRESS, 
                          DEPT_ID,USER_STATUS,MEMBERSHIP_STATUS,DATE_APPLIED) VALUES ('{$idNum}','{$fName}','{$lName}','{$mName}',{$civStat},{$sex},'{$birthdate}','{$datehired}',{$honum},{$bunum},'{$haddress}',{$dept},1,1,NOW())"; 


                      $result = mysqli_query($dbc,$query); 

                      $pw = "password";

                      $query2 = "INSERT INTO MEMBER_ACCOUNT (MEMBER_ID, PASSWORD, FIRST_CHANGE_PW) VALUES ('{$idNum}', PASSWORD('{$pw}'), '0');";
                $result2 = mysqli_query($dbc, $query2);

               }

               else if(!empty($_POST['baddress'])){
                    $baddress = $_POST['baddress'];

                    $query = "INSERT INTO MEMBER (MEMBER_ID, FIRSTNAME, LASTNAME, CIV_STATUS, MIDDLENAME, SEX, BIRTHDATE, DATE_HIRED, HOME_NUM, HOME_ADDRESS, BUSINESS_ADDRESS, 
                          DEPT_ID,USER_STATUS,MEMBERSHIP_STATUS,DATE_APPLIED) VALUES ('{$idNum}','{$fName}','{$lName}',{$civStat}, '{$mName}',{$sex},'{$birthdate}','{$datehired}',{$honum},'{$haddress}','{$baddress}',{$dept},1,1,NOW())"; 


                     $result = mysqli_query($dbc,$query); 

                     $pw = "password";

                     $query2 = "INSERT INTO MEMBER_ACCOUNT (MEMBER_ID, PASSWORD, FIRST_CHANGE_PW) VALUES ('{$idNum}', PASSWORD('{$pw}'), '0');";
                $result2 = mysqli_query($dbc, $query2);



               }

               else {

                    $query = "INSERT INTO MEMBER (MEMBER_ID, FIRSTNAME, LASTNAME, CIV_STATUS,  MIDDLENAME,SEX, BIRTHDATE ,DATE_HIRED, HOME_NUM, HOME_ADDRESS, DEPT_ID, USER_STATUS,MEMBERSHIP_STATUS,DATE_APPLIED) 
                        VALUES ('{$idNum}','{$fName}','{$lName}',{$civStat}, '{$mName}','{$sex}','{$birthdate}','{$datehired}','{$honum}','{$haddress}',{$dept},1,1,NOW())"; 

                    $result = mysqli_query($dbc,$query); 

                    $pw = "password";

                    $query2 = "INSERT INTO MEMBER_ACCOUNT (MEMBER_ID, PASSWORD, FIRST_CHANGE_PW) VALUES ('{$idNum}', PASSWORD('{$pw}'), '0');";
                    $result2 = mysqli_query($dbc, $query2);

                }

            }

    }
?>

<body>

    <div id="wrappersignup">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header">

                <img src="images/I-FA Logo Edited.png" id="ifalogo">

            </div>

        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">FA Account Creation Information</h1>
                    
                    </div>

                </div>

                <div class="row">

                    <div class="col-lg-12">

                        <p><i id="signhint">Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>

                        <form id="memform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Personal Information</b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">
                                                <span class="labelspan"><b>ID Number</b><big class="req"> *</big></span>
                                                <input type="text" minlength = "8" maxlength="8" class="form-control memname" placeholder="e.g. 09000000" name="idNum">
                                                </label>

                                                <label>
                                                <span class="labelspan">First Name<big class="req"> *</big></span>
                                                <input type="text" class="form-control memname" placeholder="First Name" name="fName">
                                                </label>

                                                <label>
                                                <span class="labelspan">Last Name<big class="req"> *</big></span>
                                                <input type="text" class="form-control memname" placeholder="Last Name" name="lName">
                                                </label>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                                <label class="memfieldlabel">Middle Name</label><big class="req"> *</big>
                                                <input type="text" class="form-control memfields" placeholder="Middle Name" name="mName">

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                                <label class="memfieldlabel">Civil Status</label>
                                                <select class="form-control" name ="civStat" id="civStat">

                                                    <?php foreach($resultCiv as $civstatusArray) { ?>

                                                        <option value="<?php echo $civstatusArray['STATUS_ID'] ?>"><?php echo $civstatusArray['STATUS'] ?></option>

                                                    <?php } ?>

                                                </select>

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                               
                                                


                                                <p id="dbirthlabel"><b>Date of Birth</b></p>




                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown"  name =  "bYear">

                                                    <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>

                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name =  "bMonth">

                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                    <option>10</option>
                                                    <option>11</option>
                                                    <option>12</option>

                                                </select>

                                                <label class="memfieldlabel">Day</label>
                                                <select class="form-control datedropdown"  name =  "bDay">

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
                                                <label><input type="radio" name="sex" value = "male">Male</label>
                                            </div>

                                            <div class="radio" >
                                                <label><input type="radio" name="sex" value = "female">Female</label>
                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Employment Information</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">

                                                <p id="dbirthlabel"><b>Date of Hiring</b></p>


                                                <label class="memfieldlabel">Year</label>
                                                <select class="form-control datedropdown" name = "hYear">

                                                    <?php for($y = 2017; $y >= 1900; $y--) { ?>

                                                        <option value="<?php echo $y; ?>"><?php echo $y; ?></option>

                                                    <?php } ?>

                                                </select>





                                                <label class="memfieldlabel">Month</label>
                                                <select class="form-control datedropdown" name = "hMonth">

                                                
                                                    <option>1</option>
                                                    <option>2</option>
                                                    <option>3</option>
                                                    <option>4</option>
                                                    <option>5</option>
                                                    <option>6</option>
                                                    <option>7</option>
                                                    <option>8</option>
                                                    <option>9</option>
                                                    <option>10</option>
                                                    <option>11</option>
                                                    <option>12</option>

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
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="honum">

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Phone Number</label><big class="req"> *</big>
                                            <input type="number" class="form-control memfields number" placeholder="Home Phone Number" name="bunum">

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Home Address</label><big class="req"> *</big>
                                            <textarea class="form-control memfields address" placeholder="Address" name="haddress" rows="2"></textarea> 

                                        </div>

                                    </div>

                                    <div class="row">

                                        <div class="col-lg-12">

                                            <label class="memfieldlabel">Business Address</label><big class="req"> </big>
                                            <textarea  class="form-control memfields address" placeholder="Address" name="baddress" rows="2"></textarea> 

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <input class="btn btn-success" type="submit" name="submit" value="Sumbit">
                            <a class="btn btn-default" href="index.php">Go Back</a><p><p>

                       </form>

                    </div>

                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>