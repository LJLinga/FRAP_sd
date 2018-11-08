<?php

session_start();
require_once("mysql_connect_FA.php");

if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

    header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

}



$success = null;
if(isset($_POST['submit'])){
    $query="insert into employee(EMP_ID,PASSWORD,FIRSTNAME,LASTNAME,DATE_CREATED,ACC_STATUS,FIRST_CHANGE_PW)
values({$_POST['ID']},password({$_POST['password']}),'{$_POST['First']}','{$_POST['Last']}',date(now()),1,0)";
mysqli_query($dbc,$query);
$success = "yes";
}

$page_title = 'Loans - Add User Account';
include 'GLOBAL_HEADER.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>
        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Add Admin Account
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>

                        <!--Insert success page--> 
                        <form method="POST" action="ADMIN addaccount.php" id="addAccount" onSubmit="return checkform()">

                            <div class="addaccountdiv">
                                <label class="signfieldlabel">Admin ID Number</label><big class="req"> *</big>
                                <input name = "ID" type="text" id="ID" class="form-control signupfield" placeholder="e.g. 09700000">
                            </div><p>

                            <div class="addaccountdiv">
                                <label class="signfieldlabel">Password</label><big class="req"> *</big>
                                <input name = "password" type="password" id = "password" class="form-control signupfield" placeholder="Enter Password">
                            </div><p>

                            <div class="row">

                            	<div class="col-lg-3">

                            		<b>First Name:</b> <input name = "First" type="text" class="form-control" placeholder="First Name">

                            	</div>

                            	<div class="col-lg-3">

                                    <b>Last Name:</b> <input name = "Last" type="text" class="form-control" placeholder="Last Name">

                            	</div>

                            </div>&nbsp;

                            <div class="row">

                                <div class="col-lg-3">

                                    <div id="subbutton">

                                        <input type="submit" name = "submit" value="Create Admin" class="btn btn-success">

                                    </div>

                                </div>

                            </div>

                        </form>

                    </div>
                </div>


            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
   
    <?php if (!empty($success)){
        echo "<script type='text/javascript'>alert('Success!');</script>";
    }?>

    <script>
     function checkform(){
        var ID = document.getElementById("ID").value;
        var pass = document.getElementById("password").value;
        if(isEmpty(ID)||isEmpty(pass)){
            alert("A required field is empty!");
            return false;
        }
        return true;
     }

     function isEmpty(str) {
         return (!str || 0 === str.length);
     }

    </script>
</body>

</html>
