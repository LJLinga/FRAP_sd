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
    <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
    <div class="collapse navbar-collapse navbar-ex1-collapse">

        <ul class="nav navbar-nav side-nav">

            <li>

                <a href="javascript:;" data-toggle="collapse" data-target="#authors"><i class="fa fa-fw fa-gear" aria-hidden="true"></i>Manage Authors<i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="authors" class="collapse">

                    <li>
                        <a href=""><i class="fa fa-users" aria-hidden="true"></i> View Authors</a>
                    </li>

                    <li>
                        <a href=""><i class="fa fa-user" aria-hidden="true"></i> Add Author </a>
                    </li>

                </ul>

            </li>

            <li>

                <a href="javascript:;" data-toggle="collapse" data-target="#posts"><i class="fa fa-fw fa-gear aria-hidden="true"></i>Manage Posts<i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="posts" class="collapse">

                    <li>
                        <a href="CMS_VIEW_PostsDashboard.php"><i class="fa fa-file-text" aria-hidden="true"></i> View Posts</a>
                    </li>

                    <li>
                        <a href="CMS_VIEW_AddPost.php"><i class="fa fa-pencil" aria-hidden="true"></i> Add Post</a>
                    </li>

                    <li>
                        <a href="CMS_VIEW_ArchivedPosts.php"><i class="fa fa-archive" aria-hidden="true"></i> Archived Posts</a>
                    </li>

                </ul>

            </li>

            <li>

                <a href="javascript:;" data-toggle="collapse" data-target="#events"><i class="fa fa-fw fa-gear aria-hidden="true"></i>Manage Events<i class="fa fa-fw fa-caret-down"></i></a>

                <ul id="events" class="collapse">

                    <li>
                        <a href="#"><i class="fa fa-file-text" aria-hidden="true"></i> View Events</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-pencil" aria-hidden="true"></i> Add Event</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-archive" aria-hidden="true"></i> Finished Events</a>
                    </li>

                </ul>

            </li>

        </ul>

    </div>
    <!-- /.navbar-collapse -->
</nav>