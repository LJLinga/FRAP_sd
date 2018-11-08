<?php
/**
 * Created by PhpStorm.
 * User: Serus Caligo
 * Date: 10/4/2018
 * Time: 2:09 PM
 */

//check which user type


$cmsRole = 2;
// 1 for Reader
// 2 for Author
// 3 for Editor

$edmsRole = 1;
$frapRole = 1;

?>
<script>
    $(document).ready(function(){

        var cmsRole = "<?php echo $cmsRole ?>";
        var edmsRole = "<?php echo $edmsRole ?>";
        var frapRole = "<?php echo $frapRole ?>";

        if(cmsRole>1 || edmsRole>1 || frapRole>1){

        }

    });
</script>
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header"> <!-- Logo -->

        <img src="images/I-FA Logo Edited.png" id="ifalogo">


        <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->

            <li>
                <a href="#"> Home </a>
            </li>

            <li class="dropdown sideicons">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Admin Tools <b class="caret"></b></a>

                <ul class="dropdown-menu alert-dropdown">


                    <li>
                        <a href="#"> <i class="fa fa-money" aria-hidden="true"></i> Loans </a>
                    </li>

                    <li>
                        <a href="#"> <i class="fa fa-newspaper-o" aria-hidden="true"></i> Santinig Content </a>
                    </li>

                    <li>
                        <a href="#"> <i class="fa fa-file-text" aria-hidden="true"></i> Documents</a>
                    </li>

                </ul>

            </li>



            <li class="dropdown sideicons">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bell"></i> <b class="caret"></b></a>

                <ul class="dropdown-menu alert-dropdown">

                    <li>
                        <a href="#">Alert Name <span class="label label-default">Alert Badge</span></a>
                    </li>

                    <li>
                        <a href="#">Alert Name <span class="label label-primary">Alert Badge</span></a>
                    </li>

                    <li>
                        <a href="#">Alert Name <span class="label label-success">Alert Badge</span></a>
                    </li>

                    <li>
                        <a href="#">Alert Name <span class="label label-info">Alert Badge</span></a>
                    </li>

                    <li>
                        <a href="#">Alert Name <span class="label label-warning">Alert Badge</span></a>
                    </li>

                    <li>
                        <a href="#">Alert Name <span class="label label-danger">Alert Badge</span></a>
                    </li>

                    <li class="divider"></li>

                    <li>
                        <a href="#">View All</a>
                    </li>

                </ul>

            </li>

            <li class="dropdown sideicons">

                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> Jo, Melton <b class="caret"></b></a>

                <ul class="dropdown-menu">

                    <li>

                        <a href="#"><i class="fa fa-fw fa-user"></i> Profile</a>

                    </li>

                    <li class="divider"></li>

                    <li>

                        <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>

                    </li>

                </ul>

            </li>

        </ul>

    </div>