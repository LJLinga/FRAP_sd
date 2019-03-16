<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/16/2019
 * Time: 3:55 PM
 */


require ('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();


if(isset($_POST['loadedReferences'])){

    $versId = $_POST['versId'];
    $crud->getData("SELECT versionId, versionNo, authorId, title, timeCreated, filePath FROM doc_versions WHERE versionId = 117;");

}

?>