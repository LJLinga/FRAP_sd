<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-nav side-nav">
            <br>
            <li>
                <a href="javascript:" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-folder-open"></i> Document Library <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse in">
                    <li id="navSideItemWorkspace">
                        <a href="EDMS_Workspace.php"><i class="fa fa-fw fa-folder"></i> Workspace </a>
                    </li>
                    <li id="navSideItemWorkspace">
                        <a href="EDMS_MyDocuments.php"><i class="fa fa-fw fa-folder"></i> My Documents </a>
                    </li>
                </ul>
            </li>



<!--            <li>-->
<!--                <a href="MANUAL_FacultyManual.php"><i class="fa fa-fw fa-book"></i> Faculty Manual</a>-->
<!--            </li>-->

            <?php
            $rows = $crud->doesUserHaveWorkflow($_SESSION['idnum'],7);
            if(!empty($rows)){?>
                <li>
                    <a href="MANUAL_ManualRevisions.php"><i class="fa fa-fw fa-pencil"></i> Manual Revisions</a>
                </li>
            <?php } ?>

            <li>
                <a href="GRP_Groups.php"><i class="fa fa-fw fa-group"></i> My Groups</a>
            </li>

        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
<!-- END OF NAV -->
