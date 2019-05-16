  <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-nav side-nav">

            <li>

                <a href="FA_Profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>

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
                <?php 
                if($_SESSION['FRAP_ROLE']==5){?>

        

                    <li>
                        <a href="ADMIN HEALTHAID applications.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Application</a>
                    </li>
               
            <?php }?>

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
            <!--
            <li>

                <a href="MEMBER AUDITRAIL.php"><i class="fa fa-backward" aria-hidden="true"></i> Audit Trail</a>

            </li>
            -->
        </ul>

    </div>
    <!-- /.navbar-collapse -->
</nav>