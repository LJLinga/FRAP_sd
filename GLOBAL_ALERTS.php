<?php

if(isset($_GET['alert'])){
    $alertType = $_GET['alert'];
    // $alertColor = INFO if none assigned
    if($alertType == 'DOC_NO_PERMISSIONS') { $alertColor = 'danger'; $alertMessage = "You have no permissions to view this document."; }
    else if($alertType == 'DOC_NOT_LOAD') { $alertColor = 'danger'; $alertMessage = "The document you are trying to view is not available."; }
    else if($alertType == 'POST_NOT_LOAD') { $alertColor = 'danger'; $alertMessage = "The post you are trying to view is not available."; }
    else if($alertType == 'POST_NO_PERMISSIONS') { $alertColor = 'danger'; $alertMessage = "The post you are trying to view is not published."; }
    else if($alertType == 'SECTION_NOT_LOAD') { $alertColor = 'danger'; $alertMessage = "The section you are trying to view is not available."; }
    else if($alertType == 'SECTION_NO_PERMISSIONS') { $alertColor = 'danger'; $alertMessage = "The section you are trying to view is not published."; }
}

?>


