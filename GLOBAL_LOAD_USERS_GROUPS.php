<?php

    function loadUserGroups($crud){
        $query = "SELECT ug.groupId, g.groupName, g.groupDesc FROM user_groups ug 
                                        JOIN groups g ON ug.groupId = g.id;";
        $rows = $crud->getData($query);
        $groups = [];
        $ctr = 0;
        if (!empty($rows)) {
            foreach ((array)$rows AS $key => $row) {
                $groups[$ctr]['id'] = $row['groupId'];
                $groups[$ctr]['name'] = $row['groupName'];
                $groups[$ctr]['desc'] = $row['groupDesc'];
                $ctr++;
            }
        }
        return $groups;
    }

?>