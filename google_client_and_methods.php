<?php

//error_reporting(0);  //this is what makes notices and warnings disappear like your will to live
	
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
	
	
	
	//$sharedFolderId = '1HyfFzGW48DJfK26lN_cYtKBhRCrQJbso'; // id of the AFED File repo folder. VERY IMPORTANT. 
	
	$fileDetailsArray = retrieveEverything($service); //contains the necessary details ffrom our google drive. Do not forget this holy grail please.
	
	
	/*----------------------------------------------HERE ARE THE FUNCTIONS YALL. -------------------------------------------- */
	
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
		printf("File ID: %s\n", $fileID);
		
		
		
	}
	
	function downloadFile($fileId, $service){ //gets the file id and downloads it
		
		$fileId = '1ZdR3L3qP4Bkq8noWLJHSr_iBau0DNT4Kli4SxNc2YEo';
		$response = $driveService->files->export($fileId, 'application/pdf', array(
			'alt' => 'media'));
		$content = $response->getBody()->getContents();
		
	}
	
	
	
	
	function updateAudit_Upload($fileName){ //accepts a string, that contains the name of the file that the admin just uploaded. 

		//get the user
		//actionID, id, name, description, dateTime
		//then put in the database what did he do, in this case uploaded.
		// desc example shoud be : Melton Jo added  _______ - blank is the file type 
		//getname
		
		
		$now  = date("Y-m-d h:i:sa");
		$name = getName($_SESSION['idnum']);
		$desc = $name." uploaded".$fileName. "to AFED File Repository";
		
		$query = "INSERT INTO file_audit_table(id,name,description,dateTime)

        values({$_SESSION['idnum']},{$name},{$desc} ,{$now});";

        mysqli_query($dbc,$query);
		
	}
	
	function updateAudit_Download($fileName){ //accepts a string, that contains the name of the file that the admin just uploaded. 
		
		
		$now  = date("Y-m-d h:i:sa");
		$name = getName($_SESSION['idnum']);
		$desc = $name." downloaded".$fileName. "from AFED File Repository";
		
		$query = "INSERT INTO file_audit_table(id,name,description,dateTime)

        values({$_SESSION['idnum']},{$name},{$desc},{$now});";

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
	
	
?>