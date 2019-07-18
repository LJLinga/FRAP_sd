<?php

    require_once ("mysql_connect_FA.php");
    session_start();
    include 'GLOBAL_USER_TYPE_CHECKING.php';
    include('GLOBAL_CLASS_CRUD.php');
    $crud = new GLOBAL_CLASS_CRUD();




    //check first if the guy is a part time.


    //then check if the guy has currently a pending loan - which brings the dude to the summary page.


    //then check if the guy has paid 50% of the Loan.... shit. we actually have a screen that keeps track of TWO Loans at the fucking same time jesus fucking christ


    //Dont forget about me pleaseeeeee. REMEMBER. TO. HAVE. TO. CHECK. IF. THE. PERSON. HAS. A. DRAFT.

    //after user confirms all the documents, when the submit button is pressed, DO IT. Update the Loans Table, get the
    //Application using the User ID. After that, update the App_Status to Pending. remember ah, APP status ah.
    $query = "SELECT * FROM LOANS where MEMBER_ID = {$_SESSION['idnum']}  ORDER BY LOAN_ID DESC LIMIT 1 ";
    $result = mysqli_query($dbc,$query);
    $ans = mysqli_fetch_assoc($result);

    $query = "SELECT l1.APP_STATUS,l2.STATUS as 'Status' FROM LOANS l1 JOIN LOAN_STATUS l2 ON l1.LOAN_STATUS = l2.STATUS_ID where l1.MEMBER_ID = {$_SESSION['idnum']} 
                      ORDER BY loan_id DESC LIMIT 1";
    $result = mysqli_query($dbc,$query);
    $ans1 = mysqli_fetch_assoc($result);


    if(!empty($row)){

        if($row['LOAN_STATUS'] == 5){

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP application.php");

        }


        if($row['LOAN_STATUS'] == 1){ //checks if you have a pending loan

            header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

        } else if($row['LOAN_STATUS'] == 2 ) { //checks if you have a loan that is ongoing.

            if ($row['PAYMENT_TERMS'] > $row['PAYMENTS_MADE']){ //checks if the loan is 50%

                header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

            }

        }

    }


    if(isset($_POST['submitApp'])){


        $crud->execute("UPDATE loans SET LOAN_STATUS = 1 WHERE LOAN_ID ={$ans['LOAN_ID']}");


        $crud->execute("INSERT INTO TXN_REFERENCE (MEMBER_ID, TXN_TYPE, TXN_DESC, AMOUNT, LOAN_REF, SERVICE_ID) 
                          VALUES({$_SESSION['idnum']}, 1, 'FALP Application Sent!', {$ans['AMOUNT']}, {$ans['LOAN_ID']}, 4)");

        //update the document types to be Pending.
        $queryForDocs = "SELECT rdl.DOC_ID from ref_document_loans rdl
                              JOIN loans l 
                              ON rdl.LOAN_ID = l.LOAN_ID
                              WHERE rdl.LOAN_ID = {$ans['LOAN_ID']}";
        $resultDocIDs = mysqli_query($dbc, $queryForDocs);


        foreach($resultDocIDs as $resultDocs){

            $crud->execute("UPDATE documents d SET d.statusedById = {$_SESSION['idnum']}, d.stepId = 10, d.statusId = 2 WHERE d.documentId = {$resultDocs['DOC_ID']}");

        }



        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP summary.php");

    }else if(isset($_POST['deleteApp'])){ //delete was pressed

        $crud->execute("UPDATE loans SET LOAN_STATUS = 5 WHERE LOAN_ID ={$ans['LOAN_ID']}");

        $queryForDocs = "SELECT rdl.DOC_ID from ref_document_loans rdl
                              JOIN loans l 
                              ON rdl.LOAN_ID = l.LOAN_ID
                              WHERE rdl.LOAN_ID = {$ans['LOAN_ID']}";
        $resultDocIDs = mysqli_query($dbc, $queryForDocs);


        foreach($resultDocIDs as $resultDocs){

            $crud->execute("UPDATE documents d SET d.statusedById = {$_SESSION['idnum']}, d.stepId = 1 WHERE d.documentId = {$resultDocs['DOC_ID']}");

        }


        header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/MEMBER FALP application.php");

        //delete all the documents or archive it

        //first archve the shit out of the documents

        //then delete the referrences


    }



    //update APP_STATUS from Draft to Passed.


    $page_title = 'Loans - FALP Summary';
    include 'GLOBAL_HEADER.php';
    include 'FRAP_USER_SIDEBAR.php';


?>
<style>
    #noresize {
        resize: none;
    }

</style>

<script type="text/javascript">


    $(document).ready(function() {
        $("input[id^='upload_file']").each(function() {
            var id = parseInt(this.id.replace("upload_file", ""));
            $("#upload_file" + id).change(function() {
                if ($("#upload_file" + id).val() != "") {
                    $("#moreImageUploadLink").show();
                }
            });
        });
    });

    $(document).ready(function() {
        var upload_number = 2;
        $('#attachMore').click(function() {
            //add more file
            var moreUploadTag = '';
            moreUploadTag += '<div class="element">';
            moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="upload_file[]" required/>';
            moreUploadTag += ' <a href="javascript:del_file(' + upload_number + ')" style="cursor:pointer;" onclick="return confirm("Are you really want to delete ?")"><i class="fa fa-trash"></i>  Delete </a></div>';
            $('<dl id="delete_file' + upload_number + '">' + moreUploadTag + '</dl>').fadeIn('slow').appendTo('#moreImageUpload');
            upload_number++;
        });
    });

    function del_file(eleId) {
        var ele = document.getElementById("delete_file" + eleId);
        ele.parentNode.removeChild(ele);
    }

</script>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">

                    <div class="col-lg-12">

                        <h1 class="page-header">FALP Loan Application Review</h1>
                        <ol class="breadcrumb">
                            <li class="active">
                                Application
                            </li>
                            <li>
                                In-Process Summary
                            </li>
                            <li>
                                Final Summary
                            </li>
                        </ol>
                    </div>

                </div>

                <div class="row">

                        <div class="col-lg-6">
                            <div class="col-lg-3">



                            </div>

                            <div class="col-lg-9">

                                <div class="panel panel-default">

                                    <div class="panel-heading">

                                        <b>Current FALP Loan Plan</b>

                                    </div>

                                    <div class="panel-body">

                                    <table class="table table-bordered" style="width: 100%;">

                                    <thread>

                                        <tr>

                                        <td align="center"><b>Description</b></td>
                                        <td align="center"><b>Amount</b></td>

                                        </tr>

                                    </thread>

                                        <tbody>

                                        <tr>

                                            <td>Amount to Borrow</td>
                                            <td>₱ <?php echo $ans['AMOUNT'];?></td>

                                        </tr>

                                        <tr>

                                            <td>Amount Payable</td>
                                            <td>₱ <?php echo $ans['PAYABLE'];?></td>

                                        </tr>

                                        <tr>

                                            <td>Payment Terms</td>
                                            <td><?php echo $ans['PAYMENT_TERMS'];?> months</td>

                                        </tr>

                                        <tr>

                                            <td>Monthly Deduction</td>
                                            <td>₱ <?php echo sprintf("%.2f",(float)$ans['PER_PAYMENT']*2);?></td>

                                        </tr>

                                        <tr>

                                            <td>Number of Payments</td>
                                            <td><?php echo $ans['PAYMENT_TERMS']*2;?> payments</td>

                                        </tr>

                                        <tr>

                                            <td>Per Payment Deduction</td>
                                            <td>₱ <?php echo $ans['PER_PAYMENT'] ;?></td>

                                        </tr>

                                    </table>

                                    </div>

                                </div>

                            </div>




                        </div>

<!--                        Containsthe Statuses + Receipt-->
                        <div class="col-lg-6">
                            <div class="row">


                                <div class="col-lg-9">

                                    <div class="panel panel-default">

                                        <div class="panel-heading">

                                            <b>Your Uploaded Files </b>

                                        </div>

                                        <div class="panel-body">

                                            <table id="table" name="table" class="table table-bordered table-striped">

                                                <thead>

                                                <tr>

                                                    <td align="center"><b>Document Name</b></td>
                                                    <td align="center"><b>Status</b></td>
                                                    <td align="center"><b>View Doc</b></td>

                                                </tr>

                                                </thead>

                                                <tbody>

                                            <?php
                                            //gets the document ids and their
                                            $query = "SELECT d.documentId, d.title, ds.statusName, rdlrq.REQ_TYPE
                                             from ref_document_loans rdl
                                             join documents d 
                                             ON rdl.DOC_ID = d.documentId
                                             join doc_status ds
                                             on d.statusId = ds.id
                                             join ref_document_loans_req_type rdlrq
                                             on rdl.DOC_REQ_TYPE = rdlrq.ID
                                             WHERE rdl.LOAN_ID = {$ans['LOAN_ID']}
                                             AND rdl.DOC_REF_TYPE = 1";
                                                $rows = $crud->getData($query);


                                            foreach((array) $rows as $key => $row){   ?>
                                                <tr>
                                                    <td align='center'> <?php echo $row['REQ_TYPE'] ?></td>
                                                    <td align='center'> <?php echo $row['statusName'] ?></td>
                                                    <td align='center'> <a href ="EDMS_ViewDocument.php?docId=<?php echo $row['documentId'];?>">

                                                            <button type="button" class="btn btn-info" ><i class="fa fa-file" aria-hidden="true"></i> </button></a></td>
                                                </tr>
                                            <?php }?>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>

                                </div>
                                <div class="col-lg-3">



                                </div>

                            </div>
<!--                            here is where the uploaded files coming from the Admin will be. dont forgetto spaghetto.-->
                        </div>

                    </div>

                    <div class="row">

                        <div class="col-lg-12">

                            <div align="center">
<!--                            put an if here that will stop them from accessing the Payment activity UNTIL the shit is accepted.-->

                                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" onsubmit="return confirm('Notice: Once you do this action, you cannot undo it.')">

                                    <input type="submit" name="deleteApp" class="btn btn-danger" value="Delete Application" style="margin:10px;">

                                    <input type="submit" name="submitApp" class="btn btn-success" value="Submit Application" style="margin:10px;">





                                </form>



                            </div>

                        </div>



                    </div>



                </div>

                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->






    <?php include 'GLOBAL_FOOTER.php' ?>
