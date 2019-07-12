<?php

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 7:53 PM
 */
include_once 'GLOBAL_CLASS_Database.php';
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

    public function isUserInGroup($userId, $groupId){
        $query = "SELECT g.id FROM groups g 
                    JOIN user_groups ug ON g.id = ug.groupId
                    WHERE ug.userId='$userId' AND ug.groupId = '$groupId' 
                    LIMIT 1;";
        $rows = getData($query);
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
        $rows = getData($query);
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

    public function permissionString($num){
        if($num == '2'){
            return 'YES';
        }else{
            return 'NO';
        }
    }

    public function availabilityString($num){
        if($num == '2'){
            return 'LOCKED';
        }else if($num == '1'){
            return 'UNLOCKED';
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
                $groupName = strtok($groupName, '_');
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
        $this->execute("UPDATE steps SET gread= '1', gwrite='1', groute='1', gcomment='1', groupId = NULL WHERE id = '$stepId';");
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
        return $this->getData("SELECT p.*, sg.*, s.*, p.id FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        JOIN step_groups sg on s.id = sg.stepId
                                        WHERE sg.groupId = '$groupId'
                                        ORDER BY p.processForId, p.processName, s.stepNo ASC;");
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

    public function getWorkflowGroups($processId){
        return $this->getData("SELECT g.id, g.groupName, g.groupDesc, pg.read, pg.write, pg.comment, pg.route 
                                FROM groups g JOIN process_groups pg on g.id = pg.groupId
                                                            WHERE pg.processId = '$processId'");
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
        return $this->getData("SELECT d.documentId, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS authorName, 
                d.filePath, d.title, d.versionNo, d.timeCreated, d.lastUpdated,
                stat.statusName, s.stepNo, s.stepName, t.type,
                pr.processName, 
                (SELECT CONCAT(e.FIRSTNAME,', ',e.LASTNAME) FROM employee e2 WHERE e2.EMP_ID = d.firstAuthorId) AS firstAuthorName 
                FROM facultyassocnew.documents d 
                JOIN employee e ON e.EMP_ID = d.authorId
                JOIN doc_status stat ON stat.id = d.statusId 
                JOIN doc_type t ON t.id = d.typeId
                JOIN steps s ON s.id = d.stepId
                JOIN process pr ON pr.id = s.processId
                WHERE t.isActive = 2 AND (d.firstAuthorId = '$userId' OR d.authorId = '$userId')
                ORDER BY d.lastUpdated DESC;");
    }

    public function getStepGroupMemberPermissions($stepId, $userId){
        return $this->getData("SELECT s.* FROM user_groups ug 
                                        JOIN groups g ON ug.groupId = g.id
                                        JOIN steps s ON g.id = s.groupId
                                        WHERE s.id = '$stepId' AND ug.userId = '$userId'
                                        LIMIT 1;");
    }

    // ONE GROUP PER STEP ONLY, CHANGE OF DESIGN
    public function getStepUserPermissions($stepId, $creatorId, $userId)
    {
        if ($userId == $creatorId) {
            return $this->getStep($stepId);
        } else {
            $rows = $this->getStepGroupMemberPermissions($stepId, $userId);
            if(empty($rows)){
                return $this->getStepProcessGroupMemberPermissions($stepId, $userId);
            }else{
                return $rows;
            }
        }
    }

    public function getStepProcessGroupMemberPermissions($stepId, $userId){
        return $this->getData("SELECT pg.* FROM process_groups pg
                                        JOIN process p ON pg.processId = p.id
                                        JOIN steps s ON p.id = s.processId
                                        JOIN groups g ON pg.groupId = g.id
                                        JOIN user_groups ug ON g.id = ug.groupId
                                        WHERE s.id = '$stepId'
                                        AND ug.userId = '$userId';");
    }

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
        return $this->getData("SELECT pr.id FROM process pr 
                                        JOIN steps s ON pr.id = s.processId
                                        JOIN step_groups sg ON s.id = sg.stepId
                                        JOIN groups g ON sg.groupId = g.id 
                                        JOIN user_groups ug on g.id = ug.groupId
                                        WHERE ug.userId = '$userId'
                                        AND pr.id = '$processId'
                                        LIMIT 1; ");
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

    public function displayUnseenUserNotifications($userId){
        return $this->getData("SELECT * FROM notifications WHERE receiverId = '$userId' AND seen = '1';");
    }

    // TO DO: notificaTIONS thread
    // TO DO: Catch link when not logged in, redirect to correct page AFTER login

}

?>