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

                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $displayName; ?> <b class="caret"></b></a>

                <ul class="dropdown-menu">

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

                <a href="MEMBER dashboard.php"><i class="fa fa-area-chart" aria-hidden="true"></i> Overview</a>

            </li>

            <li>

                <a href="javascript:" data-toggle="collapse" data-target="#applicationformsdd"><i class="fa fa-wpforms" aria-hidden="true"></i> Application Forms <i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="applicationformsdd" class="collapse">

                    <li>
                        <a href="MEMBER FALP application.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Application</a>
                    </li>

                    <li>
                        <a href="MEMBER HA application.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Application</a>
                    </li>

                    <li>
                        <a href="MEMBER LIFETIME form.php"><i class="fa fa-handshake-o" aria-hidden="true"></i>&nbsp;&nbsp;Lifetime Member Application</a>
                    </li>

                </ul>

            </li>

            <li>

                <a href="MEMBER DEDUCTION summary.php"><i class="fa fa-book" aria-hidden="true"></i> Salary Deduction Summary</a>

            </li>

            <li>

                <a href="javascript:" data-toggle="collapse" data-target="#loantrackingdd"><i class="fa fa-money" aria-hidden="true"></i> Loan Tracking <i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="loantrackingdd" class="collapse">

                    <li>
                        <a href="MEMBER FALP summary.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loan</a>
                    </li>

                </ul>

            </li>

            <li>

                <a href="javascript:" data-toggle="collapse" data-target="#servicessummarydd"><i class="fa fa-university" aria-hidden="true"></i> Services Summary <i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="servicessummarydd" class="collapse">

                    <li>
                        <a href="MEMBER HA summary.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Summary</a>
                    </li>


                </ul>

            </li>

            <li>

                <a href="MEMBER AUDITRAIL.php"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

            </li>

        </ul>

    </div>
    <!-- /.navbar-collapse -->
</nav>