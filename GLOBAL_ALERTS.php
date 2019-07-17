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
    else if($alertType == 'DOC_LOCKED') { $alertColor = 'warning'; $alertMessage = "The document you are trying to view is locked for editing by another user."; }
    else if($alertType == 'SECTION_LOCKED') { $alertColor = 'warning'; $alertMessage = "The section you are trying to view is locked for editing by another user."; }
    else if($alertType == 'DOC_LOCK_FAIL') { $alertColor = 'danger'; $alertMessage = "Unable to check the document out. Another user has locked it first."; }
    else if($alertType == 'DOC_LOCK_SUCCESS'){ $alertColor = 'success'; $alertMessage = 'You have successfully checked the document out!'; }
    else if($alertType == 'SECTION_LOCK_FAIL') { $alertColor = 'danger'; $alertMessage = "Unable to check the document out. Another user has locked it first."; }
    else if($alertType == 'SECTION_LOCK_SUCCESS'){ $alertColor = 'success'; $alertMessage = 'You have successfully checked the document out!'; }
    else if($alertType == 'SECTION_REVISIONS_CLOSED'){ $alertColor = 'danger'; $alertMessage = 'Action failed because revisions are closed. Sorry.'; }
    else if($alertType == 'SECTION_RESTORE_FAIL'){ $alertColor = 'warning'; $alertMessage = 'Another user has already restored this document. '; }
}

?>


