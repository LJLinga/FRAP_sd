<?php

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';











$page_title = 'Loans - Health Aid Application';
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
            moreUploadTag += '<input type="file" id="upload_file' + upload_number + '" name="upload_file[]"/>';
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

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data" >

                <div class="row"> <!-- Title & Breadcrumb -->

                    <div class="col-lg-12">

                        <h1 class="page-header"><i class="fa fa-plus fa-border"></i> Health Aid Application Form</h1>

                    </div>

                    <div class = "col-lg-12">

                        <label>Please fill out the necessary fields below in order for the Committee to process your request. Upload the Receipts as proof of your situation.</label>

                    </div>
                </div>

                <div class = "row" style="margin-top: 5px;">

                    <div class="col-lg-8">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b> Apply for Health Aid</b>

                            </div>


                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="usr">Amount to Borrow:</label>

                                    <input type="number" class="form-control" id="usr" size="5" style="width:250px;" required>

                                </div>

                                <div class="form-group">

                                    <label>Reason for the Health Aid Application: </label>

                                    <textarea id="noresize" class="form-control" rows="5" cols="125" required>


                                    </textarea>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class= "col-lg-4">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Upload Files Here</b>

                            </div>


                            <div class="panel-body">

                                <div class="element">
                                    <input type="file" name="upload_file[]" id="upload_file1"/>
                                </div>

                                <div id="moreImageUpload">
                                    <br>
                                </div>

                                <div class="clear">

                                </div>

                                <div id="moreImageUploadLink" style="display:none;margin-left: 10px;">
                                    <i class="fa fa-plus"></i>   <a href="javascript:void(0);" id="attachMore">Attach another file</a>
                                </div>


                            </div>

                        </div>

                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-1">
                        <input type="submit" name="applyHA" class="btn btn-success" value="Submit Application">
                    </div>
                </div>

            </form>

            </div>

        </div>


    </div>
    <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

<?php include 'GLOBAL_FOOTER.php' ?>
