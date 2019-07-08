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
            echo 'Error: cannot execute the command';
            return false;
        } else {
            return true;
        }
    }

    public function executeGetKey($query){
        $result = $this->connection->query($query);
        if ($result == false) {
            echo 'Error: cannot execute the command';
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

    public function getAllUsers(){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', 'e.FIRSTNAME) AS name FROM employee e WHERE e.ACC_STATUS = 2;");
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
            $string = 'APPROVE';
        }else if($num == 4){
            $string = 'REJECT';
        }
        return $string;
    }

    public function permissionString($num){
        if($num == '2'){
            return 'YES';
        }else{
            return 'NO';
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


    // GROUP MANAGEMENT FUNCTIONS
    public function generateGroupName($groupName){
        $rows = $this->getData("SELECT id FROM `groups` WHERE groupName LIKE '$groupName' LIMIT 1;");
        if(empty($rows)){
            return $groupName;
        }else{
            $groupName = substr($groupName, 0, strpos($groupName, "_%R"));
            $groupName.='_%R'.substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 5);
            return $this->generateGroupName($groupName);
        }
    }

    //Will be used in SYS_Groups to add groups.
    public function addGroup($groupDesc){
        $groupName = preg_replace('/\s+/', '_', $groupDesc);
        $groupName = 'GRP_'.strtoupper($groupName);
        $groupName = $this->generateGroupName($groupName);
        return $this->executeGetKey("INSERT INTO process (groupName, groupDesc) VALUES ('$groupName','$groupDesc');");
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

    public function getSpecialGroups(){
        return $this->getData("SELECT g.*, 
                                (SELECT COUNT(ug.userId) FROM user_groups ug WHERE ug.groupId = g.id) AS member_count 
                                FROM groups g 
                                WHERE g.groupName NOT LIKE 'USR%' AND  g.groupName NOT LIKE 'GRP%'
                                ORDER BY g.groupName ASC;");
    }

    public function getGroupMembers($groupId){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name, ug.* 
                                        FROM user_groups ug 
                                        JOIN employee e ON ug.userId = e.EMP_ID
                                        WHERE ug.groupId = '$groupId'
                                        ORDER BY ug.isAdmin DESC;");
    }

    public function getUsersNotInGroup($groupId){
        return $this->getData("SELECT e.EMP_ID, CONCAT(e.LASTNAME,', ',e.FIRSTNAME) AS name
                                        FROM employee e WHERE e.EMP_ID NOT IN (SELECT ug.userId FROM user_groups ug WHERE ug.groupId = '$groupId')
                                        AND e.ACC_STATUS = 2;");
    }

    public function getGroupWorkflows($groupId){
        return $this->getData("SELECT p.*, sg.*, s.* FROM steps s 
                                        JOIN process p on s.processId = p.id 
                                        JOIN step_groups sg on s.id = sg.stepId
                                        WHERE sg.groupId = '$groupId';");
    }

    public function getWorkflowDocTypes($processId){
        return $this->getData("SELECT dt.*
                                        FROM doc_type dt 
                                        JOIN process p on dt.processId = p.id
                                        WHERE p.id = '$processId' AND dt.isActive = 2;");

    }

    public function getDocTypes(){
        return $this->getData("SELECT * FROM doc_types;");
    }

    public function getActiveDocTypes(){
        return $this->getData("SELECT * FROM doc_types WHERE isActive = 2;");
    }

    public function emailNotification($emailTo, $emailFrom, $subject, $message){
        //Will be used for EDMS, CMS, Manual whenever someone moves a document/item to a step
        //Could also be used for literally any email notif purpose
        $headers = array(
            'From' => $emailFrom,
            'Reply-To' => $emailFrom,
            'X-Mailer' => 'PHP/' . phpversion()
        );

        mail($emailTo, $subject, $message, $headers);
    }

    public function addNotification($notification){

    }

    // TO DO: notificaTIONS thread
    // TO DO: Catch link when not logged in, redirect to correct page AFTER login

}

?>