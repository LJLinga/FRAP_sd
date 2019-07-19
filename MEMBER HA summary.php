<?php
require_once ("mysql_connect_FA.php");
include('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';


//checks the status of the application that you have sent
$checkForHealthAidApplicationQuery = "SELECT * FROM health_aid where MEMBER_ID = {$_SESSION['idnum']} ORDER BY RECORD_ID DESC LIMIT 1";
$checkForHealthAidApplicationResult = mysqli_query($dbc,$checkForHealthAidApplicationQuery);
$checkForHealthAidApplication = mysqli_fetch_array($checkForHealthAidApplicationResult);

if(empty($checkForHealthAidApplication)){

        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER HA application.php");

}



$page_title = 'Loans - Health Aid Application Sent';
include 'GLOBAL_HEADER.php';
include 'FRAP_USER_SIDEBAR.php';
?>

    <div id="page-wrapper">

        <div class="container-fluid">

                <div class="row"> <!-- Title & Breadcrumb -->

                    <div class="col-lg-12">

                        <h1 class="page-header"> Health Aid Application Summary</h1>
                        <ol class="breadcrumb">
                            <li>
                                Application
                            </li>
                            <li>
                                In-Process Summary
                            </li>
                            <li class="active">
                                Final Summary
                            </li>
                        </ol>
                    </div>

                    <div class = "col-lg-8">
                        <div class ="alert alert-info">
                            Below are your details that will be reviewed by the Committee and President.
                        </div>
                    </div>

                </div>

                <div class = "row" style="margin-top: 5px;">

                    <div class="col-lg-8">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b> Health Aid Application Details </b>

                            </div>


                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="usr">Amount to Borrow:</label>

                                    <input type="number" name="amount" placeholder="<?php echo $checkForHealthAidApplication['AMOUNT_TO_BORROW'] ?>" class="form-control" id="usr" size="5" style="width:250px;" disabled>

                                </div>

                                <div class="form-group">

                                    <label>Reason for the Health Aid Application: </label>

                                    <textarea  placeholder="<?php echo $checkForHealthAidApplication['MESSAGE'] ?>"  id="noresize" name="message" class="form-control" rows="5" cols="125" disabled></textarea>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class= "col-lg-4">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>View Uploaded Files and their Status</b>

                            </div>

                                <div class="panel-body">

                                    <?php
                                    //gets the document ids and their
                                    $query = "SELECT  d.documentId,d.title, ds.statusName
                                         from ref_document_healthaid rdh
                                         join documents d 
                                         ON rdh.DOC_ID = d.documentId
                                         join doc_status ds
                                         on d.statusId = ds.id
                                         WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
                                         AND rdh.DOC_REF_TYPE = 1";
                                    $rows = $crud->getData($query);


                                    foreach((array) $rows as $key => $row){   ?>

                                        <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>">

                                            <button type="button" class="btn btn-info"><?php echo $row['title'] ?></button></a>

                                        <?php echo $row['statusName'] ?>


                                    <?php }?>

                                </div>

                        </div>

                    </div>

                </div>

            <?php if($checkForHealthAidApplication['APP_STATUS'] != 1){?>

                <div class="col-lg-12">
                <?php if($checkForHealthAidApplication['APP_STATUS'] == 2){?>

                    <h3 class="page-header"> Response from Administration - Accepted </h3>

                <?php }else if($checkForHealthAidApplication['APP_STATUS'] == 3){ ?>

                    <h3 class="page-header"> Response from Administration - Rejected</h3>

                <?php }?>

                </div>

                <div class = "col-lg-12">


                </div>



                <div class="row">

                    <div class="col-lg-8">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b> Response Details </b>

                            </div>


                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="usr">Amount to Receive: </label>

                                    <input type="number" placeholder = "<?php echo $checkForHealthAidApplication['AMOUNT_GIVEN'] ?>" name="amount"  class="form-control" id="usr" size="5" style="width:250px;" disabled>

                                </div>

                                <div class="form-group">

                                    <label> Justification for the Amount Given: </label>

                                    <textarea   id="noresize" name="message" class="form-control" rows="5" cols="125" disabled><?php echo $checkForHealthAidApplication['RESPONSE'] ?></textarea>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class= "col-lg-4">

                        <div class="panel panel-default">

                            <div class="panel-heading">

                                <b>Pickup Status</b>

                            </div>

                            <div class="panel-body">
                                <div class ="row">
                                    <div class= "col-lg-12">
                                            <?php

                                            $query = "SELECT ps.STATUS
                                            from health_aid ha
                                            join pickup_status ps
                                            on ha.PICKED_UP_STATUS = ps.STATUS_ID
                                            WHERE ha.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}";

                                            $result = mysqli_query($dbc,$query);
                                            $rows = mysqli_fetch_array($result);


                                            echo $rows['STATUS'];


                                          ?>
                                    </div>
                                </div>
                            </div>
                        </div>

<!--                            --><?php //if($rows['APP_STATUS'] = 1){?>
<!--                                <div class="panel panel-default" style="margin-top: 1rem;">-->
<!---->
<!--                                    <div class="panel-heading">-->
<!---->
<!--                                        <b>Receipt for Health Aid</b>-->
<!---->
<!--                                    </div>-->
<!---->
<!--                                    <div class="panel-body">-->
<!---->
<!--                                        <div class ="row">-->
<!---->
<!--                                            <div class= "col-lg-12">-->
<!---->
<!--                                                --><?php
//                                                //gets the document ids and their
//                                                $query = "SELECT d.documentId, dv.title, ds.statusName
//                                                 from ref_document_healthaid rdh
//                                                 join documents d
//                                                 ON rdh.DOC_ID = d.documentId
//                                                 join doc_versions dv
//                                                 on d.documentId = dv.documentId
//                                                 join doc_status ds
//                                                 on d.statusId = ds.id
//                                                 WHERE rdh.RECORD_ID = {$checkForHealthAidApplication['RECORD_ID']}
//                                                 AND rdh.DOC_REF_TYPE = 2";
//                                                 $rows = $crud->getData($query);
//
//
//                                                foreach((array) $rows as $key => $row){   ?>
<!---->
<!--                                                    <div class="row">-->
<!--                                                        <div class="col-lg-2"></div>-->
<!--                                                        <div class="col-lg-8" align="center">-->
<!--                                                            <a href ="EDMS_ViewDocument.php?docId=--><?php //echo $row['documentId'];?><!--">-->
<!--                                                                <button type="button" class="btn btn-info">--><?php //echo $row['title'] ?><!--</button></a>-->
<!--                                                            <br>-->
<!--                                                            <br>-->
<!--                                                        </div>-->
<!--                                                        <div class="col-lg-2"></div>-->
<!---->
<!--                                                    </div>-->
<!---->
<!---->
<!---->
<!--                                                --><?php //}?>
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            --><?php //}?>



                    </div>


                </div>
            <?php } ?>


        </div>

    </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>