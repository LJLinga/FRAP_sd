<?php

	
	session_start();
	//!-- IMPORTANT - These session variables are found in the ADMIN FALP manual. The default homepage.
	//$_SESSION['parentFolderID']="";
	//$_SESSION['currentFolderID']="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso";
	
	require_once("mysql_connect_FA.php");
	if ($_SESSION['usertype'] == 1||!isset($_SESSION['usertype'])) {

		header("Location: http://".$_SERVER['HTTP_HOST']. dirname($_SERVER['PHP_SELF'])."/index.php");

	}
	
	error_reporting(0);  //this is what makes notices and warnings disappear like your will to live
	
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
	
	$fileDetailsArray = retrieveEverything($service);
	
	//$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso'; // id of the AFED File repo folder. VERY IMPORTANT. 
	
	/*----------------------Link check-------------------------------------------------------------------------------*/
	
	/*
	if (isset($_GET['run'])){
		//$linkchoice=$_GET['run'];
		$linkChoice = substr($_GET['run'],0,4);
		$idChoice = substr($_GET['run'],4);
		
	} 
	else{
		$linkChoice='';
		$fileDetailsArray = retrieveEverything($service); //contains the necessary details ffrom our google drive. Do not forget this holy grail please.
	} 

	switch($linkChoice){

	case 'next' :
		$_SESSION['parentFolderID'] = $_SESSION['currentFolderID'];
		$_SESSION['currentFolderID'] = $idChoice;
		$fileDetailsArray = retrieveEverything($service); //contains the necessary details ffrom our google drive. Do not forget this holy grail please.
		echo 	'<script language="javascript">
				alert("'.$idChoice.'")
				</script>';
		//myFirst();
		break;
	
	case 'back' :
		$_SESSION['currentFolderID'] = $_SESSION['parentFolderID'];
		$_SESSION['parentFolderID'] = "";
		$fileDetailsArray = retrieveEverything($service); //contains the necessary details ffrom our google drive. Do not forget this holy grail please.
		break;

	default :
		echo 'no run';

	}
	*/
	/*------------------------------SUBMITS, FILES, UPLOAD OR DOWNLOAD AND FINALLY AUDIT ARGUMENTS--------------------------- */
	 
	if(isset($_POST['submit'])){
		
		//echo"Triggered ako";
		if($_POST['submit'] != null){ // This means that you clicked a submit button and its upload (duh)
			//first check if the field is empty. 
			if ($_FILES['upload_docu']['size'] == 0 && $_FILES['upload_docu']['error'] == 0){
				//prints out an alert. 
				echo '<script language="javascript">';

                echo 'alert("You forgot to upload a file good sir.")';

                echo '</script>';
   
			}else{ //then proceed to uploading
				
				$toUploadName = $_FILES['upload_docu']['name']; // to Upload Name basically Stores the name of the goddamn file you uploaded
				$toUploadTempName = $_FILES['upload_docu']['tmp_name'];
				$toUploadPath = realpath($_FILES['upload_docu']['name']); // this one stores the realpath of the document to be uploaded. 
				
				$AFED_Type = $_POST['types']; // gets the type of file the secretary is currently uploading. 
				
				$newFolderID = makeNewFolder($AFED_Type, $service); // creates a new folder in the gdrive, as well ass returning the file id of the new folder. 
				
				uploadFileInFolder($newFolderID,$toUploadTempName, $toUploadName, $service); //uploads the file in the specified folder which is new folder ID
				
				updateAudit_Upload($AFED_Type);
				/*
				$query1 = "SELECT FIRSTNAME, LASTNAME
						from employee
						where EMP_ID = {$_SESSION['idnum']};";
				$result1 = mysqli_query($dbc,$query1);
				$ans = mysqli_fetch_assoc($result1);
				
				$name = $ans['FIRSTNAME'].$ans['LASTNAME'];
				$now = date('Y-m-d H:i:s');
				//$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
				
				//echo $name;
				$desc = $name." uploaded".$AFED_Type. "to AFED File Repository";
				
				$query = "INSERT INTO file_audit_table(id,name,description,dateTime)

				VALUES('{$_SESSION['idnum']}','{$name}','{$desc}' ,'{$now}');";

				mysqli_query($dbc,$query);*/
			
			}
			
		}else{ // meaning youre downloading shit my dude. 
			
			// and do not forget to fucking audit okay?????
		}
		 
	}
	
	
	
	/*----------------------------------------------HERE ARE THE FUNCTIONS YALL. -------------------------------------------- */
	
	function retrieveEverything($service) { //this one returns the array of the owner of the file + their ID. 
	  $result = array();
	  $pageToken = null;
	  
		do {
			if($_SESSION['parentFolderID']== null){ // if the user is in the root directory then that means we need to get all the folders.
				$response = $service->files->listFiles(array( //this is an array. okay. 
				'q' => "mimeType='application/vnd.google-apps.folder'",
				'pageToken' => $pageToken,
				'fields' => 'nextPageToken, items(id,title,ownerNames,createdDate,parents)',
				));
			}
			else{
				$response = $service->files->listFiles(array( //this is an array. okay. 
				'q' => "mimeType='application/vnd.google-apps.document'or mimeType='application/vnd.google-apps.photo' or mimeType='application/vnd.google-apps.file' or mimeType='application/vnd.google-apps.unknown'",
				'pageToken' => $pageToken,
				'fields' => 'nextPageToken, items(id,title,ownerNames,createdDate,parents)',
				));
			}
			
			//print_r(array_values($response));
			
			foreach ($response as $array1) {
				foreach ($array1 as $info){
					
					$file_ID = $info['id'];
					
					$file_title = $info['title'];
					
					$file_owners = getOwners($info['ownerNames']);
					
					$file_date_created = $info['createdDate'];
					
					$file_parent_folders = $info['parents'];
					
					//print_r(array_values($file_parent_folders));
					
					
					if(isInFileRepo($file_parent_folders)){ // if the file is in the file repository of FA, then it will be pushed and presented to the user. 
					
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
			//print_r(array_values($response));
			$pageToken = $repsonse->pageToken;
			//echo "</br></br></br></br>";
		} while ($pageToken != null);
		//echo $pageToken;
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
		//$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso'; //Original code to point to the AFED Inc Directory
		$sharedFolderId = $_SESSION['currentFolderID'];
		
		foreach($parents as $parentId){
			if($parentId['id'] === $sharedFolderId){
				return true;
				
			}
			
		}
		
		return false; 
	}
	
	function makeNewFolder($title,$service){ //returns the newly created folder id. 
		$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso'; // folder id of the shared folder. 
		
		$fileMetadata = new Google_DriveFile(array(
			'title' => $title,
			'parents' => array(array('id' => $sharedFolderId)),
			'mimeType' => 'application/vnd.google-apps.folder'));
		$folder = $service->files->insert($fileMetadata, array(
			'fields' => 'id'));
		$folderId= implode(" ",$folder);
		
		return $folderId;
		
	}
	
	function uploadFileInFolder($folderId,$filePath, $fileName, $service){ //accepts a folderID, filepath ,file name and service, where in FOlder id is the parent. 
		//first get the filename of the newly uploaded file. 
		
		//createsa new file to be uploaded in a folder(folderid)
		
		$fileMetadata = new Google_DriveFile(array(
			'title' => $fileName,
			'parents' => array(array('id' => $folderId))
		));
		
		//get the  file address and file name 
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime_type = finfo_file($finfo, $filePath); //gets the mimetype of the file you want to upload. 
		
		//uploads the new $file into the database. 
		$content = file_get_contents($filePath); // gets the contents of the file path. 
		
		$file = $service->files->insert($fileMetadata, array(
			'data' => $content,
			'mimeType' => $mime_type,
			'uploadType' => 'multipart',
			'fields' => 'id'));
			
		$fileID = implode(" ",$file);
		
		
		
	}
	
	function downloadFile($fileId, $service){ //gets the file id and downloads it
		
		$fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
		$response = $driveService->files->export($fileId, 'application/pdf', array(
			'alt' => 'media'));
		$content = $response->getBody()->getContents();
		
	}
	

	
	function updateAudit_Upload($fileName){ //accepts a string, that contains the name of the file that the admin just uploaded. 
		global $dbc;
		//get the user
		//actionID, id, name, description, dateTime
		//then put in the database what did he do, in this case uploaded.
		// desc example shoud be : Melton Jo added  _______ - blank is the file type 
		//getname
		
		
		$query1 = "SELECT FIRSTNAME, LASTNAME
						from employee
						where EMP_ID = {$_SESSION['idnum']};";
		$result1 = mysqli_query($dbc,$query1);
		$ans = mysqli_fetch_assoc($result1);
				
		$name = $ans['FIRSTNAME'].$ans['LASTNAME'];
		$now = date('Y-m-d H:i:s');
		//$now = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
				
		//echo $name;
		$desc = $name." uploaded ".$fileName. " to AFED File Repository";
				
		$query = "INSERT INTO file_audit_table(id,name,description,dateTime)

		VALUES('{$_SESSION['idnum']}','{$name}','{$desc}' ,'{$now}');";

		mysqli_query($dbc,$query);
		
	}
	
	function updateAudit_Download($fileName){ //accepts a string, that contains the name of the file that the admin just uploaded. 
		global $dbc;
		
		$now  = date("Y-m-d h:i:sa");
		$name = getName($_SESSION['idnum']);
		echo "test: ". $name;
		$desc = $name." downloaded ".$fileName. " from AFED File Repository";
		
		$query = "INSERT INTO file_audit_table(id,name,description,dateTime)

        values('{$_SESSION['idnum']}','{$name}','{$desc}','{$now}');";

        mysqli_query($dbc,$query);
		
	}
	
	function getFileUrl($file) {
		$ctr = 0;
		$ctr2 = 0;
		$downloadUrl;
		foreach ($file as $c){
			if($ctr == 19){
				foreach($c as $dlLinks){
					if($ctr2 == 3){
						$downloadUrl = $dlLinks;
					}
					$ctr2++;
				}
				echo "<br></br>";
			}
			$ctr++;
		}
		return $downloadUrl;
	}
	
	function getName($id){ //gets the id of the current user, returns name
		global $dbc;
		$query1 = "SELECT FIRSTNAME, LASTNAME
                from employee
                where EMP_ID = {$id};";
        $result1 = mysqli_query($dbc,$query1);
        $ans = mysqli_fetch_assoc($result1);
		
		$name = $ans['FIRSTNAME'].$ans['LASTNAME'];
		echo $name;
		return $name;
	}

$page_title = 'Loans - File Repository';
include 'GLOBAL_TEMPLATE_Header.php';
include 'LOAN_TEMPLATE_NAVIGATION_Admin.php';
?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <div class="row">
                
                    <div class="col-lg-12">

                        <h1 class="page-header">
                            File Repository <?php if($_SESSION['currentFolderID']!="1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso") echo '<a href="?run=back">Back</a>'; ?>
                        </h1>
                    
                    </div>
                    
                </div>
                <!-- alert -->
                <div class="row">
                    <div class="col-lg-12">
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
                        <div class="col-lg-6">
                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b>Upload a file</b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">

                                        <div class="col-lg-6">
                                                <div class="col-lg-12" align="center">
                                                    <input type="file" name="upload_docu" id = "upload_docu"></br>
                                                    <div class="col-lg-9"></div>
                                                    <div class="col-lg-3"><input type="submit" name="submit" value="Upload"></div>
                                                </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="panel panel-green">

                                <div class="panel-heading">

                                    <b></b>

                                </div>

                                <div class="panel-body">

                                    <div class="row">
										<div class="col-lg-3"></div>
                                        <div class="col-lg-6">
                                            
												
                                                <div class="row center" align="center">
                                                    <div class="col-lg-12">
													<div class="form-group">
													<label>Select File Type</label>
													<select class="form-control" name = "types" id = "types" >
														<option selected = "selected">Minutes of the Meeting</option>
														<option>Resolutions</option>
														<option>Faculty Member Guidelines Update</option>
														<option>Other</option>
													</select>
													</div>
													</div>
                                                    <div class="col-lg-12"></div>
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
					</form>

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

                                <td align="center"><?php echo $detail[1]; ?></td>
                                <td align="center"><?php echo $detail[2];?></td>
                                <td align="center"><?php echo $detail[3];?></td>
                                <td align="center"><?php echo $detail[0]; ?></td> <!-- this is where the download links are. -->

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

    <script type="text/javascript" src="DataTables/datatables.min.js"></script>
    <script>

        $(document).ready(function(){
    
            $('#table').DataTable();

        });

    </script>

</body>

</html>

