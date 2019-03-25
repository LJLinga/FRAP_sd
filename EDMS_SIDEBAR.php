<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-nav side-nav">
            <br>
            <!--
            <form>
                <div class="form-group">
                    <input class="form-control" placeholder="Search">
                </div>
            </form>

            <li class="active">
                <a href="javascript:;" data-toggle="collapse" data-target="#demo"><i class="fa fa-fw fa-folder-open"></i> Documents <i class="fa fa-fw fa-caret-down"></i></a>
                <ul id="demo" class="collapse"> -->
            <li>
                <a href="EDMS_Workspace.php"><i class="fa fa-fw fa-folder"></i> Workspace </a>
            </li>
            <li>
                <a href="EDMS_MyDocuments.php"><i class="fa fa-fw fa-folder"></i> My Documents </a>
            </li>
            <li>
                <a href="EDMS_FacultyManual.php"><i class="fa fa-fw fa-folder"></i> Faculty Manual</a>
            </li>

            <?php
                if($_SESSION['EDMS_ROLE'] != 3 && $_SESSION['EDMS_ROLE'] != 4 && $_SESSION['EDMS_ROLE'] != 5 && $_SESSION['EDMS_ROLE'] != 6){

                }else{
                    echo '<li>
                                <a href="EDMS_ManualRevisions.php"><i class="fa fa-fw fa-folder"></i> Manual Revisions</a>
                            </li>';
                }
            ?>

        </ul>
    </div>
    <!-- /.navbar-collapse -->
</nav>
<!-- END OF NAV -->
