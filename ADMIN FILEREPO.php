<?php

	require_once("mysql_connect_FA.php");
	session_start();
	if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

		header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

	}
	
	
	error_reporting(0); 
	//****** THESE ARE THE MOST IMPORTANT PARTS - TO AUTHENTICATE THE SHIT THAT WE ARE ABOUT TO DO 
	$url_array = explode('?', 'http://'.$_SERVER ['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	$url = $url_array[0];

	require_once 'google-api-php-client/src/Google_Client.php';
	require_once 'google-api-php-client/src/contrib/Google_DriveService.php';
	$client = new Google_Client();
	//THESE CLIENT IDS, USE YOUR CREDENTIALS, GO TO GOOGLE CONSOLE AND CREATE A CREDENTIAL KUNG WALA KA PA 

	$client->setClientId('461399385355-vug45c5jf83aamark0m1li500gs0mrb3.apps.googleusercontent.com');
	$client->setClientSecret('-p4L8XV3lFeUzOPtQqA-XcXo');
	$client->setRedirectUri($url);
	$client->setScopes(array('https://www.googleapis.com/auth/drive'));
	if (isset($_GET['code'])) {
		$_SESSION['accessToken'] = $client->authenticate($_GET['code']);
		header('location:'.$url);exit;
	} elseif (!isset($_SESSION['accessToken'])) {
		$client->authenticate();

	}
	
	$client->setAccessToken($_SESSION['accessToken']); //gets the authentication token. 
    $service = new Google_DriveService($client); //gets the service
	
	
	
	//$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso'; // id of the AFED File repo folder. 
	
	$fileDetailsArray = retrieveEverything($service); //contains the necessary details

	
	
	function retrieveEverything($service) { //this one returns the array of the owner of the file + their ID. 
	  $result = array();
	  $pageToken = null;
	  
		do {
			$response = $service->files->listFiles(array( //this is an array. okay. 
				'q' => "mimeType='application/vnd.google-apps.folder'",
				'pageToken' => $pageToken,
				'fields' => 'nextPageToken, items(id,title,ownerNames,createdDate,parents)',
			));
			
			//print_r(array_values($response));
			
			foreach ($response as $array1) {
				foreach ($array1 as $info){
					
					$file_ID = $info[id];
					$file_title = $info[title];
					$file_owners = getOwners($info[ownerNames]);
					$file_date_created = $info[createdDate];
					$file_parent_folders = $info[parents];
					
					//print_r(array_values($file_parent_folders));
					if(isInFileRepo($file_parent_folders)){
						
						array_push($result,array($file_ID,$file_title, $file_owners, $file_date_created));
						
					}
					/*testing echos 
					echo "File ID: ".$file_ID."</br>";
					echo "File Title: ".$file_title."</br>";
					echo "File Owners: ".$file_owners."</br>";
					echo "File Date Created:".$file_date_created."</br>";
					echo "</br></br>";
					*/
					
					
				}
				
				
			
				
			}
			
			$pageToken = $repsonse->pageToken;
		} while ($pageToken != null);
		
		return $result;
	}
	
	function getOwners($owners){ //gets an array of names and stitches them all together into one string. 
		$result = "";
		
		foreach($owners as $owner){
			$result .= $owner;
		}
		
		return $result;
	}
	
	function isInFileRepo($parents){ //gets an array, and gives a boolean and if true, meaning that the file is in the repository and not some wild ass skank 
		$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso';
		
		foreach($parents as $parentId){
			if($parentId[id] === $sharedFolderId){
				return true;
				
			}
			
		}
		
		return false; 
	}
	
	
	
	
	
	
	/*
	function retrieveAllFileNames($service) {
	  $result = array();
	  $pageToken = null;
	  
		do {
			$response = $service->files->listFiles(array( //this is an array. okay. 
				'q' => "mimeType='application/vnd.google-apps.folder'",
				'pageToken' => $pageToken,
				'fields' => 'nextPageToken, items(id,title)',
			));
			

			//print_r(array_values($response));
			
			foreach ($response as $array1) {
				foreach ($array1 as $file){
					
					$file_info = implode(" ",$file);
					printf("File Info: %s\n", $file_info);
					echo"</br>";
				
					
					
					$file_info = implode(" ",$file); // makes it a string to convert it back to an array, because for some reason, walang nareretrieve.
					$file_info_array = explode(" ",$file_info); // turns it back into an array in order to read the values from the array. 
					$stringToBePassed = ""; //gets the title, full title duh
					$file_ID = $file_info_array[0];
					if(count($file_info_array) > 2){ // if the File Name/Title has spaces, then.... we add them all into one big string. 
						for($i=1; $i<count($file_info_array); $i++){
							$stringToBePassed = $stringToBePassed." ".$file_info_array[$i]; // patches together a goddamn title
						}
					}
					else{
						$stringToBePassed = $stringToBePassed." ".$file_info_array[1]; // patches together a goddamn title
					}
					
					array_push($result,array($file_ID,$stringToBePassed)); //creates a 2d array with the title of the file and the File id. 
				}
			/*foreach ($array1 as $file){
					$file_ID;
					$file_owners = " ";
					
					foreach ($file as $file2){
						
						$file_ID = explode(" ",$file2);
						
						$file_ID = $file_ID[0];
						
						$file_owners = implode(" ",$file2);
						//echo $file_owners." ";
						
					}
					
					array_push($result,array($file_ID, $file_owners));
					
					
				}
				
			}
			
			$pageToken = $repsonse->pageToken;
		} while ($pageToken != null);
		
		return $result;
	}
	*/
	
?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>

    <link href="css/montserrat.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="DataTables/datatables.min.css"/>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>



<body>

    <div id="wrapper">

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

    <li id="top">

        <a href="ADMIN FALP manual.php"><i class="fa fa-gears" aria-hidden="true"></i> Add Member & FALP Account</a>

    </li>

    <!--<li>

        <a href="ADMIN addaccount.php"><i class="fa fa-user-plus" aria-hidden="true"></i> Add Admin Account</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#applications"><i class="fa fa-wpforms" aria-hidden="true"></i>&nbsp;Applications<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="applications" class="collapse">

            <li>
                <a href="ADMIN MEMBERSHIP applications.php"><i class="fa fa-id-card-o" aria-hidden="true"></i>&nbsp;&nbsp;Membership Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN FALP applications.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN BANK applications.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loan Pending Applications</a>
            </li>

            <li>
                <a href="ADMIN HEALTHAID applications.php"><i class="fa fa-medkit" aria-hidden="true"></i>&nbsp;&nbsp;Health Aid Pending Applications</a>
            </li>

        </ul>

    </li>

    <li>

        <a href="ADMIN LIFETIME addmember.php"><i class="fa fa-id-card-o" aria-hidden="true"></i> Add Lifetime Member</a>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#bankloans"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;Bank Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="bankloans" class="collapse">

            <li>
                <a href="ADMIN BANK addbank.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Add Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK editbank.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Partner Bank</a>
            </li>

            <li>
                <a href="ADMIN BANK addplan.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;Add Bank Loan Plan</a>
            </li>

            <li>
                <a href="ADMIN BANK editplan.php"><i class="fa fa-gears" aria-hidden="true"></i>&nbsp;&nbsp;Enable/Disable Bank Loan Plan</a>
            </li>

        </ul>

    </li>-->
    
            <li>
                <a href="ADMIN MEMBERS viewmembers.php"><i class="fa fa-group" aria-hidden="true"></i>&nbsp;&nbsp;View All Members</a>
            </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#loans"><i class="fa fa-money" aria-hidden="true"></i>&nbsp;On-going Loans<i class="fa fa-fw fa-caret-down"></i></a>

        <ul id="loans" class="collapse">

            <li>
                <a href="ADMIN FALP viewactive.php"><i class="fa fa-dollar" aria-hidden="true"></i>&nbsp;&nbsp;FALP Loans</a>
            </li>

            <!--<li>
                <a href="ADMIN BANK viewactive.php"><i class="fa fa-institution" aria-hidden="true"></i>&nbsp;&nbsp;Bank Loans</a>
            </li>-->

        </ul>

    </li>

    <li>

    <a href="javascript:;" data-toggle="collapse" data-target="#dreports"><i class="fa fa-minus" aria-hidden="true"></i>&nbsp;Deduction Reports<i class="fa fa-fw fa-caret-down"></i></a>

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

    <a href="javascript:;" data-toggle="collapse" data-target="#preports"><i class="fa fa-table" aria-hidden="true"></i>&nbsp;Periodical Reports<i class="fa fa-fw fa-caret-down"></i></a>

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

    <li>

    <a href="ADMIN FILEREPO.php"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;File Repository</i></a>

    </li>
    <!--
    <li>

        <a href="ADMIN MANAGE.php"><i class="fa fa-gears" aria-hidden="true"></i> Admin Management</a>

    </li>-->

</ul>

            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            File Repository
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">

                        <div class="col-lg-6">
                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Upload a file</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-6">
                                            <form action="ADMIN FILEREPO.php" method="POST">
                                                <div class="col-lg-12" align="center">
                                                    <input type="file" name="upload_docu"></br>
                                                    <div class="col-lg-9"></div>
                                                    <div class="col-lg-3"><input type="submit" name="upload" value="Upload File"></div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

<!--                    Data Table Portion                          -->
                       <table id="table" class="table table-bordered table-striped">        
                            <thead>
                                <tr>
                                    <td align="center" width="250px"><b>Name</b></td>
                                    <td align="center"><b>Owner</b></td>
                                    <td align="center" width="200px"><b>Last Modified</b></td>
                                    <td align="center" width="200px"><b>Download</b></td>
                                </tr>
                            </thead>

                            <tbody>
                                <?php 
                                    foreach($fileDetailsArray as $detail){
										
                                ?>
                                <tr>

                                <td align="center"><?php echo $detail[1];?></td>
                                <td align="center"><?php echo $detail[2];?></td>
                                <td align="center"><?php echo $detail[3];?></td>
                                <td align="center"><?php echo $detail[0]; ?></td>

                                </tr>
                                <?php } ?>

                             

                            </tbody>

                        </table>
                    </div>
                </div>


            </div>

        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <!-- jQuery -->
    <script src="js/jquery.js"></script>
       
    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>

