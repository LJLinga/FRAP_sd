<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Welcome to FRAP!</title>

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

    session_start();
    require('mysql_connect_FA.php');

/*****
 * So what these code do is that they are
 */
    if (isset($_POST['submit'])) {

        $message = NULL;

        if (empty($_POST['idnum'])) {

            $_SESSION['idnum'] = NULL;
            $message.='<p>You forgot to enter your ID Number!';

        }

        else {
            $_SESSION['idnum'] = $_POST['idnum'];
        }

        if (empty($_POST['password'])) {

            $_SESSION['password'] = NULL;
            $message.='<p>You forgot to enter your password!';

        }

        else {
            $_SESSION['password'] = $_POST['password'];
        }
 
        if (!isset($message)) {

                $idnum = $_SESSION['idnum'];
                $password = $_SESSION['password'];

                //require_once('mysql_connect_FA.php');

                /*
                    $queryMem = "SELECT MEMBER_ID, PASSWORD, FIRST_CHANGE_PW FROM MEMBER_ACCOUNT WHERE MEMBER_ID = '{$idnum}' AND PASSWORD = PASSWORD('{$password}')";
                    $resultMem = mysqli_query($dbc, $queryMem);
                    $rowMem = mysqli_fetch_array($resultMem);

                    $queryEmp = "SELECT EMP_ID, PASSWORD, ACC_STATUS, FIRST_CHANGE_PW FROM EMPLOYEE WHERE EMP_ID = '{$idnum}' AND PASSWORD = PASSWORD('{$password}')";
                    $resultEmp = mysqli_query($dbc, $queryEmp);
                    $rowEmp = mysqli_fetch_array($resultEmp);
                */

                /*
                    $idnum = "hellll";
                    $pass = "fww";

                    include_once('GLOBAL_CLASS_CRUD.php');
                    $crud = new GLOBAL_CLASS_CRUD();
                    //checks if there is a person like this in the database.

                    $account = $crud->getData("SELECT * from employee WHERE  MEMBER_ID = '{$idnum}' &&  PASS_WORD = PASSWORD('{$password}') " );

                    foreach ((array) $account as $key => $row) {
                        $idnum = $row['MEMBER_ID'];
                        $pass = $row['PASS_WORD'];
                    }

                    echo $idnum;
                    echo $pass;
                */


                $queryMem = "SELECT * FROM employee WHERE MEMBER_ID = '{$idnum}' AND PASS_WORD = PASSWORD('{$password}')";
            $resultMem = mysqli_query($dbc, $queryMem);
            if (!$resultMem) {
                printf("Error: %s\n", mysqli_error($dbc));
                exit();
            }
            $rowMem = mysqli_fetch_array($resultMem);



                if(empty($rowMem)){ //if the account does not exist

                    $message .= "This account is not recognized by the Faculty Association. Please contact the administrator.";

                }else if($rowMem['FIRST_CHANGE_PW'] == 1 && $rowMem['ACC_STATUS']==2){ // if the account has not changed its password yet.

                    $query = "SELECT FRAP_ROLE, EDMS_ROLE, CMS_ROLE FROM employee WHERE MEMBER_ID = '{$_SESSION['idnum']}' ";
                    $row = mysqli_query($dbc, $query);
                    $result = mysqli_fetch_array($row);

                    $_SESSION['FRAP_ROLE'] =  $result['FRAP_ROLE'];
                    $_SESSION['CMS_ROLE'] =  $result['CMS_ROLE'];
                    $_SESSION['EDMS_ROLE'] =  $result['EDMS_ROLE'];
                    $_SESSION['SYS_ROLE'] =  $result['SYS_ROLE'];

                 header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/FA_Change_PW.php");


                    //insert code here/



                }else if($rowMem['ACC_STATUS']==2){ //sends it to the most appropriate account. and adds the

                    $query = "SELECT FRAP_ROLE, EDMS_ROLE, CMS_ROLE, SYS_ROLE FROM employee WHERE MEMBER_ID = '{$_SESSION['idnum']}' ";
                    $row = mysqli_query($dbc, $query);
                    $result = mysqli_fetch_array($row);

                    $_SESSION['FRAP_ROLE'] =  $result['FRAP_ROLE'];
                    $_SESSION['CMS_ROLE'] =  $result['CMS_ROLE'];
                    $_SESSION['EDMS_ROLE'] =  $result['EDMS_ROLE'];
                    $_SESSION['SYS_ROLE'] =  $result['SYS_ROLE'];
                    //$_SESSION['SYS_ROLE'] =  $result['SYS_ROLE'];

                        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/feed.php");

                }
                else if ($rowMem['ACC_STATUS']==1){
                     $message .= "This account is not yet approved by the Faculty Association. Please contact the administrator for the status of your account.";
                }
                else
                    $message .= "An error has occured. Plese contact the administrator if error persists.";
                /*
                if ($rowMem['MEMBER_ID'] == $_SESSION['idnum']) {

                	$queryPending = "SELECT MEMBERSHIP_STATUS, USER_STATUS FROM MEMBER WHERE MEMBER_ID = '{$_SESSION['idnum']}'";
                	$resultPending = mysqli_query($dbc, $queryPending);
                	$rowPending = mysqli_fetch_array($resultPending);

                	if ($rowPending['MEMBERSHIP_STATUS'] == 2 && $rowPending['USER_STATUS'] == 1 && $rowMem['FIRST_CHANGE_PW'] == 1) {

                    	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER dashboard.php");
                    	$_SESSION['usertype'] = "1";

                	}

                	else if ($rowPending['MEMBERSHIP_STATUS'] == 2 && $rowPending['USER_STATUS'] == 1 && $queryMem['FIRST_CHANGE_PW'] == 0) {

                		header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER changepw.php");
                    	$_SESSION['usertype'] = "1";

                	}




                }



                else if ($rowEmp['EMP_ID'] == $_SESSION['idnum']) {

                	if ($rowEmp['ACC_STATUS'] == 1 && $rowEmp['FIRST_CHANGE_PW'] == 1) {

                    	header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/ADMIN dashboard.php");
                    	$_SESSION['usertype'] = "2";

                	}

                	else if ($rowEmp['ACC_STATUS'] == 1 && $rowEmp['FIRST_CHANGE_PW'] === 0) {

                		header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER changepw.php");
                    	$_SESSION['usertype'] = "2";

                	}

                	else {

                		$message .= "This account is not recognized by the Faculty Association. Please contact the administrator.";

                	}

                }

                else {

                	$message .= "This account is not recognized by the Faculty Association.";

                }

                */
        }

    }

?>

<body>

    <div id="wrappersignup">

        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header">

                <img src="images/iafedlogo normal Edited.png" id="ifalogo">

            </div>

        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row"> <!-- Image -->

                    <div class="col-lg-3 col-1"> <!-- For center alignment -->

                    </div>

                    <div class="col-lg-6 col-2"> <!-- The center of the page -->

                        <img src="images/iafedlogo normal.png" id="falogonorm">

                    </div>

                    <div class="col-lg-3 col-3"> <!-- For center alignment -->             

                    </div>
                   
                </div>

                <?php if (isset($message)) { ?>

                <div class="row">

            		<div class="col-lg-3 col-1"> <!-- For center alignment -->

                    </div>

                    <div class="col-lg-6">

                    		<div class="alert alert-info">

                            	<strong><?php echo $message ?></strong>

                        	</div>

                    </div>

            	</div>

            	<?php } ?>

                <div class="row"> <!-- Fields -->

                    <div class="col-lg-3 col-1"> <!-- For center alignment -->


                    </div>

                    <div class="col-lg-6 col-2"> <!-- The center of the page -->

                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="loginform">

                            <div>
                                <label id="emaillabel">ID Number</label>
                                <input type="text" id="emaillogin" class="form-control" minlength="8" maxlength="8" placeholder="e.g. 11700000" name="idnum" required>
                            </div>

                            <div>
                                <label id="passlabel">Password</label>
                                <input type="password" id="passlogin" class="form-control" placeholder="Password" name="password" font size = "9" required>
                            </div>

                            <div id="loginsubmitbutton">

                                <input type="submit" value="Log In" class="btn btn-success" name="submit">

                            </div>

                        </form>

                        <div id="signupdiv">

                            <p id="noacc">No account yet?</p>
                
                            <a href="FA membership.php" id="loginsignupbutton" class="btn btn-default" role="button">Apply Here!</a>

                        </div>

                    </div>

                    <div class="col-lg-3 col-3"> <!-- For center alignment -->


                    </div>

                </div>

                <div class="row"> <!-- Extra Row -->

                    <div class="col-lg-4 col-1"> <!-- For center alignment -->


                    </div>

                    <div class="col-lg-4 col-2"> <!-- The center of the page -->

                        

                    </div>

                    <div class="col-lg-4 col-3"> <!-- For center alignment -->


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