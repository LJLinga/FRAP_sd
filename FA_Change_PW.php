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



/*-------FILE REPO STUFF------*/
$_SESSION['parentFolderID']="";
$_SESSION['currentFolderID']="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso";
/*-------FILE REPO STUFF END------*/


    if (isset($_POST['submit'])) {

            $query = "UPDATE employee SET pass_word = password('{$_POST['pass']}'), FIRST_CHANGE_PW = 2 WHERE MEMBER_ID = {$_SESSION['idnum']}";
            $queryMP = "UPDATE member_account SET password = password('{$_POST['pass']}'), FIRST_CHANGE_PW = 2 WHERE MEMBER_ID = {$_SESSION['idnum']}";
            mysqli_query($dbc,$query);
            mysqli_query($dbc,$queryMP);
            session_destroy();
            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/index.php");
    
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

                        <p><i>New password should:</i></p>
                        <ul>
                            <li>be alphanumeric,</li>
                            <li>contain a special character,</li>
                            <li>be at least 8 characters</li>
                        </ul>
                        
                        <!--Insert success page--> 
                        
                        <form method="POST" action="FA_Change_PW.php" onSubmit="return checkform()" id ="my_form">

                            <div class="panel panel-green" name = "personalInfo">

                                <div class="panel-heading">

                                    <b></b>

                                </div>
                               

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-12">
                                                <span class="labelspan"><b>New Password </b><big class="req"> *</big></span>
                                                <input type="password"  class="form-control memname" placeholder="new password" name="pass" id = "pass" font size = 9 required>
                                                </label>

                                                <span class="labelspan"><b>Confirm Password </b><big class="req"> *</big></span>
                                                <input type="password"  class="form-control memname" placeholder="new password" name="cpass" id = "cpass" font size = 9 required>
                                                </label>
                                                <div id = "chk"></div>

                                             </div>  

                                             

                                            <div class="col-lg-12">
                                                <br>
                                                <div class="col-lg-1">

                                              <input id = "submit"  type="submit" name="submit" value="Submit" disabled>
                                                </div>
                                                 <div class="col-lg-11"></div>
                                            </div>

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
         var pass = document.getElementById("pass").value;
         var format = /[ !@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
         var format2 = /[0-9]+/;
         document.getElementById("cpass").value = "";
         document.getElementById("chk").innerHTML = "";
         if(pass.length <8){
            document.getElementById("chk").innerHTML = '<font  color = "red">Password is less than 8 characters</font>';
            return;
         }
         else{
            if(!format2.test(pass)){
                document.getElementById("chk").innerHTML = '<font  color = "red">Password should be alphanumeric</font>';
                
            }
            else if(!format.test(pass)){
                document.getElementById("chk").innerHTML = '<font  color = "red">Password should contain at least 1 special character</font>';
                return;
            }
            

            
            
         }
        document.getElementById("submit").disabled = false;
              
    $('#submit').click (function (e) {
        //will stop the link href to call the blog page
        document.getElementById("chk").innerHTML = '<font  color = "green"><b>Password has been changed </b></font>';
         //will call the function after 2 secs.

});
         
  
}

);
    
         
</script>
    <!-- Scroll to top script-->
    <div id ="scrollToTopScript">
            </div>

</body>

</html>
