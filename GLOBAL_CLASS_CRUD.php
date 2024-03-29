<?php

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 7:53 PM
 */


$alertMessage = '';
$alertType = '';
$alertColor = 'info';
include_once 'GLOBAL_CLASS_Database.php';
require_once __DIR__ . '/vendor/autoload.php';

class GLOBAL_CLASS_CRUD extends GLOBAL_CLASS_Database {

    public function __construct(){
        parent::__construct();
    }

    public function getData($query){
        $result = $this->connection->query($query);
        if ($result == false) {
            return false;
        }

        $rows = array();
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        return $rows;
    }

    public function execute($query){
        $result = $this->connection->query($query);
        if ($result == false) {
            return false;
        } else {
            return true;
        }
    }

    public function executeGetKey($query){
        $result = $this->connection->query($query);
        if ($result == false) {
            return false;
        }else{
            return mysqli_insert_id($this->connection);
        }
    }

    public function delete($id, $table){

        $query = "DELETE FROM $table WHERE id = $id";
        $result = $this->connection->query($query);

        if ($result == false) {
            echo 'Error: cannot delete id ' . $id . ' from table ' . $table;
            return false;
        } else {
            return true;
        }
    }

    public function getActiveUsers(){
        return $this->getData("SELECT e.*, CONCAT(e.LASTNAME,', 'e.FIRSTNAME) AS name FROM employee e WHERE e.ACC_STATUS = 2;");
    }

    public function getInactiveUsers(){
        return $this->getData("SELECT e.*, CONCAT(e.LASTNAME,', 'e.FIRSTNAME) AS name FROM employee e WHERE e.ACC_STATUS = 1;");
    }

    public function getUsers(){
        return $this->getData("SELECT e.*, CONCAT(e.LASTNAME,', 'e.FIRSTNAME) AS name FROM employee e;");
    }

    //USER <-> GROUPS FUNCTIONS
    //Added functions for ease of use, put here because its going to be used over and over.
    public function getUserGroups($userId){
        $query = "SELECT * FROM groups g JOIN user_groups ug 
                    ON g.id = ug.groupId
                    WHERE ug.userId='$userId' ORDER BY g.groupName;";
        return $this->getData($query);
    }

    public function getUserGroupsWithCount($userId){
        $query = "SELECT g.*, (SELECT COUNT(ug.userId) FROM user_groups ug WHERE ug.groupId = g.id) AS member_count  FROM groups g JOIN user_groups ug 
                    ON g.id = ug.groupId
                    WHERE ug.userId='$userId' ORDER BY g.groupName;";
        return $this->getData($query);
    }

    public function isUserInGroup($userId, $groupId){
        $query = "SELECT g.id FROM groups g 
                    JOIN user_groups ug ON g.id = ug.groupId
                    WHERE ug.userId='$userId' AND ug.groupId = '$groupId' 
                    LIMIT 1;";
        $rows = $this->getData($query);
        if(!empty($rows)){
            return true;
        }else {
            return false;
        }
    }

    public function isUserInGroupName($userId, $groupName){
        $query = "SELECT g.id FROM groups g 
                    JOIN user_groups ug ON g.id = ug.groupId
                    WHERE ug.userId='$userId' AND g.groupName = '$groupName' 
                    LIMIT 1;";
        $rows = $this->getData($query);
        if(!empty($rows)){
            return true;
        }else {
            return false;
        }
    }


    //Will be used in SYS_Groups to force add users to groups.
    //Will be used in INVITATION screen after user accepts JOIN group invite.
    public function addUserToGroup($groupId, $userId){
        $query = "INSERT INTO user_groups(userId, groupId) VALUES ('$userId','$groupId');";
        return $this->execute($query);
    }

    //Will be used in SYS_Groups to force remove people.
    //Will be used in GRP_Management to allow Group Admins to remove people.
    public function removeUserFromGroup($groupId, $userId){
        $query = "DELETE FROM user_groups WHERE userId = '$userId' AND groupId = '$groupId';";
        return $this->execute($query);
    }

    // STRING FORMATTING FUNCTIONS
    public function escape_string($value){
        return $this->connection->real_escape_string($value);
    }

    public function esc($value){
        return $this->connection->real_escape_string($value);
    }

    public function canApproveString($num){
        $string = 'Error';
        if($num == 1){
            $string ='NO';
        }else if($num == 2){
            $string = 'REJECT ONLY';
        }else if($num == 3){
            $string = 'APPROVE ONLY';
        }else if($num == 4){
            $string = 'APPROVE AND REJECT';
        }
        return $string;
    }

    public function stepTypeString($num){
        $string = 'Error';
        if($num == 1){
            $string = 'START (First step)';
        }else if($num == 2){
            $string = 'NORMAL (In-between steps)';
        }else if($num == 3){
            $string = 'COMPLETE (Last step after approve/reject)';
        }else if($num == 4){
            $string = 'RESTART (Routes only to start)';
        }
        return $string;
    }

    public function assignStatusString($num){
        $string = 'Error';
        if($num == 5){
            $string ='NO ASSIGNMENT';
        }else if($num == 1){
            $string = 'DRAFT';
        }else if($num == 2){
            $string = 'PENDING';
        }else if($num == 3){
            $string = 'APPROVED';
        }else if($num == 4){
            $string = 'REJECTED';
        }
        return $string;
    }

    public function lifecycleString($num){
        if($num == '2'){
            return 'ARCHIVED';
        }else{
            return 'RESTORED';
        }
    }
    //Later
    public function redirectToPreviousWithAlert ($alertType){
        $previousHTTP = $_SERVER['HTTP_REFERER'];
        $previousHTTP = explode($previousHTTP, '&');
        $previousHTTP = $previousHTTP[0];
        if (strpos($previousHTTP, '?') == true) {
            return $previousHTTP.'&alert='.$alertType;
        }else if(strpos($previousHTTP, '?') != true){
            return $previousHTTP.'?alert='.$alertType;
        }
    }

    // 2 == Process for Manual Revisions
    public function getManualRevisionsFirstStep(){
        return $this->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id 
                                  WHERE pr.processForId = 2 AND s.stepTypeId = 1 LIMIT 1;");
    }

    // 3 == Process for Post Publication
    public function getPostPublicationFirstStep(){
        return $this->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id 
                                  WHERE pr.processForId = 3 AND s.stepTypeId = 1 LIMIT 1;");
    }

    public function getWorkflowFirstStep($processId){
        return $this->getData("SELECT s.id FROM steps s JOIN process pr ON s.processId = pr.id 
                                     WHERE pr.id = '$processId' AND s.stepTypeId = 1 LIMIT 1;");
    }

    public function permissionString($num){
        if($num == '2'){
            return 'YES';
        }else{
            return 'NO';
        }
    }

    public function availabilityString($num){
        if($num == '2'){
            return 'CHECKED OUT';
        }else if($num == '1'){
            return 'CHECKED IN';
        }
    }

    public function groupRoleString($num){
        if($num == 2){
            return 'ADMIN';
        }else{
            return 'MEMBER';
        }
    }

    public function processForString($num){
        if($num == '1'){
            return 'DOCUMENTS';
        }else if($num == '2'){
            return 'MANUAL SECTIONS';
        }else if($num == '3') {
            return 'POSTS';
        }
    }

    public function activeString($num){
        if($num == '2'){
            return 'ACTIVE';
        }else{
            return 'INACTIVE';
        }
    }

    public function editableString($num){
        if($num == '1'){
            return 'NOT EDITABLE';
        }else if($num == '2'){
            return 'SUPERADMIN';
        }else if($num == '3') {
            return 'ADMIN, SUPERADMIN';
        }else if($num == '4'){
            return 'GROUP ADMIN, ADMIN, SUPERADMIN';
        }
    }

    public function deactivatableString($num){
        if($num == '1'){
            return 'NOT EDITABLE';
        }else if($num == '2'){
            return 'SUPERADMIN';
        }else if($num == '3') {
            return 'ADMIN, SUPERADMIN';
        }else if($num == '4'){
            return 'GROUP ADMIN, ADMIN, SUPERADMIN';
        }
    }

    public function removableString($num){
        if($num == '1'){
            return 'NOT REMOVABLE';
        }else if($num == '2'){
            return 'SUPERADMIN';
        }else if($num == '3') {
            return 'ADMIN, SUPERADMIN';
        }else if($num == '4'){
            return 'GROUP ADMIN, ADMIN, SUPERADMIN';
        }
    }

    //Will be used in SYS_Groups to add groups.
    public function addGroup($groupDesc){
        $groupName = preg_replace('/\s+/', '_', $groupDesc);
        $groupName = 'GRP_'.strtoupper($groupName);
        $bool = false;
        $insertKey = false;
        do {
            $rows = $this->getData("SELECT id FROM groups WHERE groupName LIKE '$groupName' LIMIT 1;");
            if(empty($rows)){
                $bool = true;
                $insertKey = $this->executeGetKey("INSERT INTO groups (groupName, groupDesc) VALUES ('$groupName','$groupDesc');");
            }else{
                $groupName = strtok($groupName, '_%');
                $groupName.='_%'.substr(str_shuffle("CHRISTAN"), 0,5);
            }
        } while ($bool == false);
        return $insertKey;
    }

    //This will NOT change their initial name
    public function setGroupDisplayName($groupId, $groupDesc){
        return $this->execute("UPDATE groups SET groupDesc = '$groupDesc' WHERE id = '$groupId';");
    }

    public function setGroupAdmin($groupId, $userId){
        return $this->execute("UPDATE user_groups SET isAdmin = 2 WHERE userId = '$userId' AND groupId = '$groupId';");
    }

    public function removeGroupAdmin($groupId, $userId){
        return $this->execute("UPDATE user_groups SET isAdmin = 1 WHERE userId = '$userId' AND groupId = '$groupId';");
    }

    public function getGroupFromName($groupName){
        return $this->getData("SELECT g.* FROM groups g WHERE g.groupName LIKE '$groupName' LIMIT 1;");
    }

    public function getGroup($groupId){
        return $this->getData("SELECT g.* FROM groups g WHERE g.id = '$groupId' LIMIT 1;");
    }

    public function getGroups(){
        return $this->getData("SELECT g.*, 
                                (SELECT COUNT(ug.userId) FROM user_groups ug WHERE ug.groupId = g.id) AS member_count 
                                FROM groups g 
                                ORDER BY g.groupName ASC;");
    }

    public function deleteGroup($groupId){
        $rows = $this->getGroupMembers($groupId);
        if(!empty($rows)){
           foreach((array) $rows AS $key => $row){
               $this->removeUserFromGroup($groupId,$row['EMP_ID']);
           }
        }
        $rows = $this->getData("SELECT id FROM steps WHERE groupId = '$groupId';");
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $this->removeStepGroup($row['id']);
            }
        }
        $this->execute("DELETE FROM groups WHERE id = '$groupId'");
    }

    public function removeStep($stepId){
        $this->execute("DELETE FROM steps WHERE id = '$stepId';");
    }

    public function removeStepGroup($stepId){
        $this->execute("UPDATE steps SET gwrite='1', groute='1', gcycle ='1', groupId = NULL WHERE id = '$stepId';");
    }

    public function removeProcessGroup($processId){
        $this->execute("UPDATE process SET `write`='1', route='1', cycle ='1', groupId = NULL WHERE id = '$processId';");
    }

    public function removeStepRoute($routeId){
        $this->execute("DELETE FROM step_routes WHERE id = '$routeId';");
    }

    public function isUserGroupAdmin($userId, $groupId){
        $rows = $this->getData("SELECT ug.userId FROM user_groups ug
                                        WHERE ug.userId = '$userId' 
                                        AND ug.groupId = '$groupId'
                                        AND ug.isAdmin = '2' LIMIT 1;");
        if(!empty($rows)){
            return true;
        }else {
            return false;
        }
    }

    public function auditColoriser($contentType, $actionType){
       //Couldve thought of this sooner, when auditing just directly say the action, not make it look for it.
    }

    public function getNonAdminGroups(){
        return $this->getData("SELECT g.*, 
                                (SELECT COUNT(ug.userId) FROM user_groups ug WHERE ug.groupId = g.id) AS member_count 
                                FROM groups g 
                                WHERE g.groupName NOT LIKE 'USR%' AND  g.groupName NOT LIKE 'ADM%'
                                ORDER BY g.groupName ASC;");
    }

    public function getAdminGroups(){
        return $this->getData("SELECT g.*, 
                                (SELECT COUNT(ug.userId) FROM user_groups ug WHERE ug.groupId = g.id) AS member_count 
                                FROM groups g 
                                WHERE g.groupName NOT LIKE 'USR%' AND  g.groupName NOT LIKE 'GRP%'
                                ORDER BY g.groupName ASC;");
    }

    public function getGroupMembers($groupId){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name, ug.isAdmin
                                        FROM user_groups ug 
                                        JOIN employee e ON ug.userId = e.EMP_ID
                                        WHERE ug.groupId = '$groupId'
                                        ORDER BY ug.isAdmin DESC, name ASC;");
    }

    public function friendlyDate($date){
        return date("F j, Y g:i:s A ", strtotime($date));
    }

    public function deactivateGroup($groupId){
        return $this->execute("UPDATE groups SET isActive = '1' WHERE id = '$groupId'");
    }

    public function activateGroup($groupId){
        return $this->execute("UPDATE groups SET isActive = '2' WHERE id = '$groupId'");
    }

    public function getUsersNotInGroup($groupId){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name
                                        FROM employee e WHERE e.EMP_ID NOT IN (SELECT ug.userId FROM user_groups ug WHERE ug.groupId = '$groupId')
                                        AND e.ACC_STATUS = 2;");
    }

    public function getGroupWorkflows2($groupId){
        return $this->getData("SELECT p.* FROM process p
                                        JOIN process_groups pg on p.id = pg.processId
                                        WHERE pg.groupId = '$groupId'
                                        ORDER BY p.processName ASC;");
    }

    public function getGroupWorkflows($groupId){
        return $this->getData("SELECT p.*, s.*, s.gwrite AS `write`, s.groute AS `route`, s.gcycle AS `cycle`, p.id AS processId, s.id AS stepId
                                        FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        WHERE s.groupId = '$groupId'
                                        ORDER BY p.processForId, p.processName, s.stepNo ASC;");
    }

    public function getGroupWorkflowsSpectate($groupId){
        return $this->getData("SELECT p.*, pg.*, p.id FROM process p JOIN process_groups pg on p.id = pg.processId 
                                        WHERE pg.groupId = '$groupId'
                                        ORDER BY p.processForId, p.processName ASC;");
    }

    public function getGroupDocWorkflows($groupId){
        return $this->getData("SELECT p.*, s.*, s.gwrite AS `write`, s.groute AS `route`, s.gcycle AS `cycle`, p.id FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        WHERE s.groupId = '$groupId' AND p.processForId = 1
                                        ORDER BY p.processForId, p.processName, s.stepNo ASC;");
    }

    public function getGroupDocWorkflowsSpectate($groupId){
        return $this->getData("SELECT p.*, pg.*, p.id FROM process p JOIN process_groups pg on p.id = pg.processId 
                                        WHERE pg.groupId = '$groupId' AND p.processForId = 1
                                        ORDER BY p.processName ASC;");
    }

    public function coloriseStatus($num){
        $string = $this->assignStatusString($num);
        $color = '';
        if($num == '1') { $color = "info"; }
        else if($num == '2') { $color = "primary"; }
        else if($num == '3') { $color = "success"; }
        else if($num == '4') { $color = "danger"; }
        return '<span class="label label-'.$color.'">'.$string.'</span>';
    }

    public function coloriseStep(){
        return '<span class="label label-primary">MOVED</span>';
    }

    public function coloriseCycle($num){
        $string = $this->lifecycleString($num);
        $color = '';
        if($num == '1') { $color = "success"; }
        else if($num == '2') { $color = "warning"; }
        return '<span class="label label-'.$color.'">'.$string.'</span>';
    }

    public function isRevisionsOpen(){
        $query = "SELECT r.id, r.revisionsOpened FROM revisions r WHERE r.statusId = 2 ORDER BY r.id DESC LIMIT 1;";
        $rows = $this->getData($query);
        if(!empty($rows)){
            $revisions = 'open';
            foreach ((array) $rows as $key => $row){
                $revisionsOpened = $row['revisionsOpened'];
                $revisionsId = $row['id'];
            }
        }
    }

    public function coloriseUpdated($string){
        return '<span class="label label-success">'.$string.'</span>';
    }

    public function coloriseAvailability($num){
        $string = $this->availabilityString($num);
        $color = '';
        if($num == '1') { $color = "default"; }
        else if($num == '2') { $color = "default"; }
        return '<span class="label label-'.$color.'">'.$string.'</span>';
    }

    public function getUserDocTypes($userId){
        return $this->getData("SELECT dt.* FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        JOIN doc_type dt ON p.id = dt.processId
                                        JOIN step_groups sg on s.id = sg.stepId
                                        JOIN groups g ON sg.groupId = g.id
                                        JOIN user_groups ug on g.id = ug.groupId
                                        WHERE ug.userId = '$userId' GROUP BY dt.id
                                        ORDER BY dt.type ASC;");
    }

    public function getDistinctGroupWorkflows($groupId){
        return $this->getData("SELECT DISTINCT p.* FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        JOIN step_groups sg on s.id = sg.stepId
                                        WHERE sg.groupId = '$groupId';");
    }

    public function getWorkflowSteps($processId){
        return $this->getData("SELECT s.* FROM steps s JOIN process p ON s.processId = p.id WHERE p.id = '$processId';");
    }

    public function getStepRoutes($stepId){
        return $this->getData("SELECT r.id AS routeId, r.orderNo, r.routeName, r.nextStepId, r.assignStatus, s.id AS stepId, s.stepName, s.stepNo, s.stepTypeId
                                        FROM step_routes r JOIN steps s ON r.nextStepId = s.id
                                        WHERE r.currentStepId = '$stepId' ORDER BY r.orderNo;");
    }

    public function getStep($stepId){
        return $this->getData("SELECT s.* FROM steps s WHERE s.id = '$stepId' LIMIT 1;");

    }

    public function getWorkflowDocTypes($processId){
        return $this->getData("SELECT dt.* FROM doc_type dt JOIN process p on dt.processId = p.id WHERE p.id = '$processId';");
    }

    public function getWorkflows(){
        return $this->getData("SELECT pr.* FROM process pr ORDER BY pr.processForId, pr.processName ASC;");
    }

    public function getDocumentWorkflows(){
        return $this->getData("SELECT pr.* FROM process pr WHERE pr.processForId = 1 ORDER BY pr.id ASC;");
    }

    public function getSpecialWorkflows(){
        return $this->getData("SELECT pr.* FROM process pr WHERE pr.processForId != 1 ORDER BY pr.processName ASC;");
    }

    public function getDocTypes(){
        return $this->getData("SELECT dt.*, p.processName, dt.id AS typeId, p.id AS processId FROM doc_type dt JOIN process p ON dt.processId = p.id;");
    }

    public function getActiveDocTypes(){
        return $this->getData("SELECT * FROM doc_type WHERE isActive = 2;");
    }

    //TO DO: handle documents with deactivated types, they should be viewable but cant be interacted with.
    public function getPersonalDocuments($userId){
        return $this->getData("SELECT d.documentId, d.statusId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                s.stepNo, s.stepName, t.type,
                pr.processName, 
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM facultyassocnew.documents d 
                JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND (d.firstAuthorId = '$userId' OR d.authorId = '$userId')
                ORDER BY d.lastUpdated DESC;");
    }

    public function getPersonalDocumentsEditing($userId){
        return $this->getData("SELECT d.documentId, d.statusId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                s.stepNo, s.stepName, t.type,
                pr.processName, 
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM facultyassocnew.documents d 
                JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND (d.firstAuthorId = '$userId')
                AND d.availabilityById = '$userId' AND d.availabilityId = '2'
                ORDER BY d.lastUpdated DESC;");
    }

    public function getStepGroupMemberPermissions($stepId, $userId){
        return $this->getData("SELECT s.gcycle AS `cycle`, s.gwrite AS `write`, s.groute AS `route` FROM user_groups ug 
                                        JOIN groups g ON ug.groupId = g.id
                                        JOIN steps s ON g.id = s.groupId
                                        WHERE s.id = '$stepId' AND ug.userId = '$userId'
                                        LIMIT 1;");
    }

    public function error404(){
        http_response_code(404);
        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/resource_not_found.html");
        die();
    }

    public function getStepGroupDetails($stepId){
        return $this->getData("SELECT g.id AS groupId, g.groupName, g.groupDesc
                                    FROM steps s JOIN groups g ON s.groupId = g.id
                                    WHERE s.id = ' $stepId'");
    }

    public function getNeedsMyAttentionDocumentsCount($userId){
        return $this->getData("SELECT COUNT(d.documentId) FROM documents d
                JOIN doc_type t ON t.id = d.typeId
                WHERE t.isActive = 2 AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE ug.userId = '$userId' AND (s.groute = 2 OR s.gwrite = 2))
                AND d.firstAuthorId != '$userId'
                AND d.statusId = '2'
                ORDER BY d.lastUpdated DESC;");
    }

    public function getEditingByMeDocumentsCount($userId){
        return $this->getData("SELECT COUNT(d.documentId) FROM documents d
                JOIN doc_type t ON t.id = d.typeId
                WHERE t.isActive = 2 AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE ug.userId = '$userId' AND (s.groute = 2 OR s.gwrite = 2))
                AND d.firstAuthorId != '$userId'
                AND d.statusId = '2'
                ORDER BY d.lastUpdated DESC;");
    }

    // ONE GROUP PER STEP ONLY, CHANGE OF DESIGN
    public function getStepUserPermissions($stepId, $creatorId, $userId)
    {
        //Before: creator permissions before group permissions;
        //After: group permissions first before creator;
        $rows = $this->getStepGroupMemberPermissions($stepId, $userId);
        if(!empty($rows)){
            return $rows;
        }else if($userId === $creatorId){
            return $this->getStep($stepId);
        }
        $rows = $this->getStep($stepId);
        if(!empty($rows)){
            $groupId = $rows[0]['groupId'];
            $bool = $this->isUserInGroup($userId, $groupId);
            if($bool){

            }
        }
        $this->isUserInGroup();
    }

    public function getPublishableSections($manualId){

    }

    public function isUserInWorkflow($userId, $stepId){
        $rows = $this->getData("SELECT pr.id FROM process pr 
                                        JOIN steps s ON pr.id = s.processId
                                        WHERE s.id = '$stepId' AND pr.id IN (SELECT p.id FROM process p 
                                                        JOIN steps s on p.id = s.processId
                                                        JOIN groups g ON s.groupId = g.id
                                                        JOIN user_groups ug on g.id = ug.groupId
                                                        WHERE ug.userId = '$userId')
                                        LIMIT 1;");
        if(!empty($rows)){
            return true;
        }else{
            return false;
        }
    }

    public function getManualReferencableDocuments($sectionId){
        $query = "SELECT v.*, dt.type FROM doc_versions v 
                    JOIN doc_type dt ON dt.id = v.typeId
                    WHERE v.versionId IN 
                    (SELECT MAX(v2.versionId) FROM doc_versions v2 
                        WHERE (v2.audit_action_type = 'STATUSED'OR v2.audit_action_type = 'STATUSED/MOVED') 
                        AND v2.statusId = 3 
                        AND (v2.typeId = 4 OR v2.typeId = 5 OR v2.typeId = 6)
                        GROUP BY v2.documentId 
                        ORDER BY v2.statusedOn DESC) 
                    AND v.versionId NOT IN
					(SELECT ref.versionId FROM section_ref_versions ref WHERE ref.sectionId = '$sectionId')
                    ORDER BY v.statusedOn DESC;";
        return $this->getData($query);
    }

    public function getPostReferencableDocuments($postId){
        $query = "SELECT v.*, dt.type FROM doc_versions v 
                    JOIN doc_type dt ON dt.id = v.typeId
                    WHERE v.versionId IN 
                    (SELECT MAX(v2.versionId) FROM doc_versions v2 
                        WHERE (v2.audit_action_type = 'STATUSED'OR v2.audit_action_type = 'STATUSED/MOVED') 
                        AND v2.statusId = 3 
                        AND (v2.typeId = 8)
                        GROUP BY v2.documentId 
                        ORDER BY v2.statusedOn DESC) 
                    AND v.versionId NOT IN
					(SELECT ref.versionId FROM post_ref_versions ref WHERE ref.sectionId = '$postId')
                    ORDER BY v.statusedOn DESC;";
        return $this->getData($query);
    }

//    public function getStepProcessGroupMemberPermissions($stepId, $userId){
//        return $this->getData("SELECT pg.* FROM process_groups pg
//                                        JOIN process p ON pg.processId = p.id
//                                        JOIN steps s ON p.id = s.processId
//                                        JOIN groups g ON pg.groupId = g.id
//                                        JOIN user_groups ug ON g.id = ug.groupId
//                                        WHERE s.id = '$stepId'
//                                        AND ug.userId = '$userId';");
//    }

    public function getProcessIdOfDocType($docTypeId){
        return $this->getData("SELECT pr.id FROM process pr JOIN doc_type dt on pr.id = dt.processId
                                  WHERE dt.id = '$docTypeId' LIMIT 1;");
    }

    public function getFirstStepIdOfProcess($processId){
        return $this->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id 
                                  WHERE p.id = '$processId' AND s.stepTypeId = 1 LIMIT 1;");
    }

    public function getFirstStepIdOfDocType($docTypeId){
        return $this->getData("SELECT s.id FROM steps s 
                                  JOIN process pr ON s.processId = pr.id 
                                  JOIN doc_type dt on pr.id = dt.processId
                                  WHERE dt.id = '$docTypeId' AND s.stepTypeId = 1 LIMIT 1;");
    }

    public function doesUserHaveWorkflow($userId, $processId){
        $rows = $this->getData("SELECT pr.id FROM process pr 
                                        JOIN steps s ON s.processId = pr.id
                                        JOIN groups g ON s.groupId = g.id 
                                        JOIN user_groups ug on g.id = ug.groupId
                                        WHERE ug.userId = '$userId'
                                        AND pr.id = '$processId'
                                        LIMIT 1; ");
        if(!empty($rows)){
            return true;
        }else{
            return false;
        }
    }


    public function sendNotifications(){

    }

    public function sendEmailNotifications(){

    }

    public function getUserEmail($userId){
        $email = '';
        $rows = $this->getData("SELECT EMAIL FROM member WHERE MEMBER_ID = '$userId' LIMIT 1;");
        if(!empty($rows)){
            foreach((array) $rows AS $key => $row){
                $email = $row['EMAIL'];
            }
        }
        return $email;
    }

    public function sendEmail($receiverId, $senderId, $subject, $message){
        //Will be used for EDMS, CMS, Manual whenever someone moves a document/item to a step
        //Could also be used for literally any email notif purpose

        $emailFrom = $this->getUserEmail($senderId);
        $emailTo = $this->getUserEmail($receiverId);

        $headers = array(
            'From' => $emailFrom,
            'Reply-To' => $emailFrom,
            'X-Mailer' => 'PHP/' . phpversion()
        );

        mail($emailTo, $subject, $message, $headers);
    }

    public function getGroupInvites($groupId){
        return $this->getData("SELECT gi.*, CONCAT(e.LASTNAME,',', e.FIRSTNAME) as name FROM group_invitations gi
                                        JOIN employee e ON e.EMP_ID = gi.invitedId
                                        WHERE gi.groupId = '$groupId'");
    }

    public function getGroupNoninvitedUsers($groupId){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name
                                        FROM employee e 
                                        WHERE e.EMP_ID NOT IN (SELECT gi.invitedId FROM group_invitations gi WHERE gi.groupId = '$groupId')
                                        AND e.EMP_ID NOT IN (SELECT ug.userId FROM user_groups ug WHERE ug.groupId = '$groupId')
                                        AND e.ACC_STATUS = 2;");
    }

    public function getUserName($userId){
        $rows = $this->getData("SELECT CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name FROM employee e WHERE e.EMP_ID = '$userId' LIMIT 1;");
        $name = '';
        foreach($rows AS $key => $row){
            $name = $row['name'];
        }
        return $name;
    }

    public function addNotification($senderId, $receiverId, $message, $hyperlink, $type){
        return $this->execute("INSERT INTO notifications (senderId, receiverId, message, hyperlink, notification_type) VALUES ('$senderId','$receiverId','$message', '$hyperlink', '$type');");
    }

    public function displayUserNotifications($userId){
        return $this->getData("SELECT * FROM notifications WHERE receiverId = '$userId'");
    }

    public function displayUserNotificationsByType($userId, $typeId){
        return $this->getData("SELECT n1.* FROM notifications n1 WHERE n1.notification_type = '$typeId' AND n1.receiverId = '$userId' AND n1.seen = '1' 
                        AND n1.timestamp = (SELECT MAX(n2.timestamp) FROM notifications n2 WHERE n2.artifactId = n1.artifactId ) 
                        GROUP BY n1.artifactId ORDER BY n1.timestamp DESC LIMIT 10;");
    }

    public function displayPendingDocumentsCount($userId){
        return $this->getData("SELECT COUNT(d.documentId) AS count
                FROM documents d 
                JOIN doc_type t ON t.id = d.typeId
                WHERE t.isActive = 2 
                AND d.lifecycleStateId = 1
                AND d.statusId = 2
                AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE (ug.userId = '$userId' AND (s.groute = 2 OR s.gwrite = 2))
                                                    OR (s.route = 2 OR s.`write` = 2 AND d.firstAuthorId = '$userId'));");
    }

    public function displayInProcessDocumentsCount($userId){
        return $this->getData("SELECT COUNT(d.documentId) AS 'count'
                FROM documents d 
                JOIN doc_type t ON t.id = d.typeId
                WHERE t.isActive = 2 
                AND d.lifecycleStateId = 1
                AND d.stepId IN (SELECT s.id FROM user_groups ug
                                                    JOIN groups g ON ug.groupId = g.id
                                                    JOIN steps s ON g.id = s.groupId
                                                    WHERE (ug.userId = '$userId' AND (s.gwrite = 2 OR s.gcycle = 2 OR s.groute = 2)))
                AND d.availabilityId = '2' AND d.availabilityById = '$userId'
                ORDER BY d.lastUpdated DESC;");
    }

    public function displayUnseenUserNotifications($userId){
        return $this->getData("SELECT * FROM notifications WHERE receiverId = '$userId' AND seen = '1';");
    }

    // TO DO: notificaTIONS thread
    // TO DO: Catch link when not logged in, redirect to correct page AFTER login

    public function generate_permalink($string){
        if($string !== mb_convert_encoding( mb_convert_encoding($string, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32') )
            $string = mb_convert_encoding($string, 'UTF-8', mb_detect_encoding($string));
        $string = htmlentities($string, ENT_NOQUOTES, 'UTF-8');
        $string = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $string);
        $string = html_entity_decode($string, ENT_NOQUOTES, 'UTF-8');
        $string = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $string);
        $string = strtolower( trim($string, '-') );
        return $string;
    }


    public function getCalendarClient(){
        $client = new Google_Client();
        $client->setApplicationName('LapDoc Event Scheduler');
        $client->setScopes(Google_Service_Calendar::CALENDAR);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

// If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
// Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
// Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

// Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

// Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
// Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    public function getCalendarService(){
        $client = $this->getCalendarClient();
        return new Google_Service_Calendar($client);
    }

    public function insertCalendarEvent($userId, $title, $description, $startTime, $endTime, $email_array, $freq, $freqCount){
        $data = [];
        if(!empty($email_array)){
            foreach((array) $email_array as $key=>$value) {
                $data[] = array('email'=>$value);
            }
        }

        try{
            $service = $this->getCalendarService();

            $event = new Google_Service_Calendar_Event(array(
                'summary' => $title,
                'location' => 'Manila',
                'description' => $description,
                'start' => array(
                    'dateTime' => $startTime,
                    'timeZone' => 'Asia/Manila',
                ),
                'end' => array(
                    'dateTime' => $endTime,
                    'timeZone' => 'Asia/Manila',
                ),
                'recurrence' => array(
                    'RRULE:FREQ='.$freq.';COUNT='.$freqCount
                ),
                'attendees' => $data,
                'reminders' => array(
                    'useDefault' => TRUE
                )
            ));

            $calendarId = 'primary';
            $event = $service->events->insert($calendarId, $event);
            $eventId = $event->getId();
            $eventLink = $event->htmlLink;


        }catch(Exception $e){
            return 'Google Calendar service did not respond.';
        }

        if($eventLink !== ''){
            $id = $this->executeGetKey("INSERT INTO events (title, description, posterId, startTime, endTime, GOOGLE_EVENTID, GOOGLE_EVENTLINK) values ('$title', '$description','$userId','$startTime','$endTime','$eventId','$eventLink')");

            if(isset($id)){
                if(!empty($email_array)){
                    foreach((array) $email_array as $key=>$value) {
                        $this->insertCalendarEventEmail($id, $value);
                    }
                }
                return $id;
            }else{
                return 'Cannot insert event into database.';
            }
        }else{
            return 'Event link not found.';
        }

    }

    public function insertTentativeEvent(){

    }
    public function updateCalendarEvent(){

    }

    public function deleteCalendarEvent($eventId){
        $bool = $this->execute("DELETE FROM event_emails WHERE eventId = '$eventId'");
        if($bool){
            $rows = $this->getData("SELECT GOOGLE_EVENTID from events WHERE id = '$eventId' LIMIT 1;");
            if(!empty($rows)){
                foreach((array) $rows AS $key => $row){
                    $googleEventId = $row['GOOGLE_EVENTID'];
                }

            }else{
                return false;
            }
        }
    }

    public function insertCalendarEventEmail($eventId, $email){
        $this->execute("INSERT INTO event_emails (eventId, email) VALUES ('$eventId','$email')");
    }

    public function removeCalendarEventEmail($eventId, $email){
        $this->execute("DELETE FROM event_emails WHERE eventId = '$eventId' AND email = '$email';");
    }
}

?>