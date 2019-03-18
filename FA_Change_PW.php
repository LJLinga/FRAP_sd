<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Change Password</title>

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


    if (isset($_POST['submit'])) {

            $query = "UPDATE MEMBER_ACCOUNT SET password = password({$_POST['pass']}), FIRST_CHANGE_PW = 2 WHERE MEMBER_ID = {$_SESSION['idnum']}";
            mysqli_query($dbc,$query);
            session_destroy();
             header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/FA_Change_PW.php");
    
  }
 $page_title = 'Profile ';


?>

<body>
    <button onclick="topFunction()" id="scrollToTop" title="Go to top">Scroll to Top</button>
    
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">

            <div class="navbar-header">

                <a href = "index.php" >
                    <img src="images/I-FA Logo Edited.png" id="ifalogo">
                </a>
            </div>

        </nav>
        

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            Change Password
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <p><i>Fields with <big class="req">*</big> are required to be filled out and those without are optional.</i></p>
                        
                        <!--Insert success page--> 
                        
                        <form method="POST" action="FA_Change_PW.php" id="addAccount" onSubmit="return checkform()">

                            <div class="panel panel-green" name = "personalInfo">

                                <div class="panel-heading">

                                    <b></b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">
                                                <span class="labelspan"><b>New Password </b><big class="req"> *</big></span>
                                                <input type="password"  class="form-control memname" placeholder="new password" name="pass" id = "pass" >
                                                </label>

                                                <span class="labelspan"><b>Confirm Password </b><big class="req"> *</big></span>
                                                <input type="password"  class="form-control memname" placeholder="new password" name="cpass" id = "cpass" >
                                                </label>
                                                <div id = "chk"></div>

                                             </div>  

                                    </div>

                                 

                                  </div>
                            

                            <input id = "submit"  type="submit" name="submit" value="Sumbit" disabled></p>

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
    <script>
    $(document.getElementById("cpass")).keyup(function(){
         var pass = document.getElementById("pass").value;
         var cpass = document.getElementById("cpass").value;
         if(pass!=cpass){
        
        document.getElementById("chk").innerHTML = '<font  color = "red">Password is not the same</font>';
        document.getElementById("submit").disabled = true;
         }
         else{
          document.getElementById("chk").innerHTML = '<font  color = "green">Password can be used</font>';
          document.getElementById("submit").disabled = false;
         }


              
           
         
  
}

);
    
         
</script>
<script>
    $(document.getElementById("pass")).keyup(function(){
         
         document.getElementById("cpass").value = "";
         document.getElementById("chk").innerHTML = "";

              
           
         
  
}

);
    
         
</script>
    <!-- Scroll to top script-->
    <div id ="scrollToTopScript">
            </div>

</body>

</html>