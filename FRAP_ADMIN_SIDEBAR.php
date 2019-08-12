 <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
<!--                    <li id="top">-->
<!--                        <a href="ADMIN%20dashboard.php"> <i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard </a>-->
<!--                    </li>-->
                   
                   
                    <li id="top">
                        <a href="javascript:" data-toggle="collapse" data-target="#members"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;Members<i class="fa fa-fw fa-caret-down"></i></a>
                        <ul id="members" class="collapse">
                            <li>
                        <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Registered Members</a>
                    </li>
<!--                    <li>-->
<!--                        <a href="ADMIN MEMBER roles.php"><i class="fa fa-group" aria-hidden="true"></i> Member Roles </a>-->
<!--                    </li>-->
                        </ul>
                    </li>
                   
                   
                    
                    

                
                     
                    <li>
                        <a href="ADMIN%20Deductions.php"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;View All Deductions</a>
                    </li>
                    <!---
                    <li>
                        <a href="ADMIN MEMBER roles.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Roles </a>
                    </li>
                    --->
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
                            <li>
                                <a href="ADMIN MEMBERSHIP applications.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;Membership Applications</a>
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
                                <a href="ADMIN PREPORT completed.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;Matured Loans</a>
                            </li>
                            <li>
                                <a href="ADMIN PREPORT new.php"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;&nbsp;New Deductions</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="ADMIN MREPORT report.php"><i class="fa fa-table" aria-hidden="true"></i> Monthly Report</a>
                    </li>
                     <li>
                        <a href="javascript:" data-toggle="collapse" data-target="#mTools"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Migration Tools<i class="fa fa-fw fa-caret-down"></i></a>

                        <ul id="mTools" class="collapse">
                            <li>
                                <a href="ADMIN%20Upload%20Excel.php"><i class="fa fa-group" aria-hidden="true"></i> Import Member List </a>
                            </li>
                            <li>
                                <a href="ADMIN%20FALP%20addtomember.php"><i class="fa fa-dollar" aria-hidden="true"></i> Add FALP to Member </a>
                            </li>
                             <li>
                                <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Manually Add Member </a>
                            </li>
                        </ul>
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