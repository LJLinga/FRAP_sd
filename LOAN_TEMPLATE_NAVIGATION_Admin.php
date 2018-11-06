
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="navbar-header"> <!-- Logo -->
                <img src="images/I-FA Logo Edited.png" id="ifalogo">
                <ul class="nav navbar-right top-nav"> <!-- Top Menu Items / Notifications area -->
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
                        <?php
                        $query = "SELECT LASTNAME, FIRSTNAME FROM employee
                                    
                        WHERE MEMBER_ID =" . $_SESSION['idnum'].";";

                        $result = mysqli_query($dbc, $query);
                        $row = mysqli_fetch_array($result);

                        $displayName = $row['LASTNAME']." , ".$row['FIRSTNAME'][0].". ";

                        ?>

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName ?> <b class="caret"></b></a>
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
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
                    <li id="top">
                        <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Add Member & FALP Account</a>
                    </li>
                    <li>
                        <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
                    </li>
                    <li>
                        <a href="javascript:" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;On-going Loans<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="loans" class="collapse">
                            <li>
                                <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loans</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="javascript:" data-toggle="collapse" data-target="#applications"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;Applications<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="applications" class="collapse">
                            <li>
                                <a href="ADMIN%20FALP%20applications.php"><i class="fa fa-dollar" aria-hidden="true"></i>   FALP Applications</a>
                            </li>

                            <li>
                                <a href="ADMIN%20HEALTHAID%20applications.php"><i class="fa fa-dollar" aria-hidden="true"></i>  Health Aid Applications</a>
                            </li>

                        </ul>
                    </li>

                    <li>
                        <a href="javascript:" data-toggle="collapse" data-target="#dreports"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Deduction Reports<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="dreports" class="collapse">
                            <li>
                                <a href="ADMIN DREPORT general.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;General Deductions</a>
                            </li>
                            <li>
                                <a href="ADMIN DREPORT detailed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Detailed Deductions</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:" data-toggle="collapse" data-target="#preports"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;Periodical Reports<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="preports" class="collapse">
                            <li>
                                <a href="ADMIN PREPORT completed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Completed Loans</a>
                            </li>
                            <li>
                                <a href="ADMIN PREPORT new.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;New Deductions</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="ADMIN MREPORT report.php"><i class="fa fa-table" aria-hidden="true"></i> Monthly Report</a>
                    </li>

                    <!--<li>
                    <a href="javascript:;" data-toggle="collapse" data-target="#repo"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;File Repository<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="repo" class="collapse">
                            <li>
                                <a href="ADMIN FILEREPO.php"><i class="fa fa-files-o" aria-hidden="true"></i>&nbsp;&nbsp;View Documents</a>
                            </li>
                            <li>
                                <a href="ADMIN FILEREPO upload.php"><i class="fa fa-upload" aria-hidden="true"></i> Upload Documents</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="ADMIN MANAGE.php"><i class="fa fa-gears" aria-hidden="true"></i> Admin Management</a>
                    </li>-->
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>