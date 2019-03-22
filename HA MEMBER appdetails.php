<?php

require_once ("mysql_connect_FA.php");
session_start();
include 'GLOBAL_USER_TYPE_CHECKING.php';






if(isset($_POST['accept'])){
    $queryHA = "UPDATE health_aid set APP_STATUS = 2,PICKUP_STATUS = 3,DATE_APPROVED = date(now()),EMP_ID = {$_SESSION['idnum']}  WHERE RECORD_ID = {$_SESSION['showHAID']}";
    $result = mysqli_query($dbc, $queryHA);
    mysqli_fetch_array($result);

 header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/HA HEALTHAID applications.php");
   
}
else if(isset($_POST['reject'])){
    $queryHA = "UPDATE health_aid set APP_STATUS = 3,EMP_ID = {$_SESSION['idnum']}  WHERE RECORD_ID = {$_SESSION['showHAID']}";
    $result = mysqli_query($dbc, $queryHA);
    mysqli_fetch_array($result);
     
    header("Location: http://".$_SERVER['HTTP_HOST'].  dirname($_SERVER['PHP_SELF'])."/HA HEALTHAID applications.php");
}
 
 $queryHA = "SELECT REASON,AMOUNT FROM health_aid  WHERE RECORD_ID = {$_SESSION['showHAID']}";
        $result = mysqli_query($dbc, $queryHA);
        $rowHA = mysqli_fetch_array($result);

        


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

                    
                </div>

                <div class = "row" style="margin-top: 5px;">

                    <div class="col-lg-8">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b> Application for Health Aid </b>

                            </div>


                            <div class="panel-body">

                                <div class="form-group">

                                    <label for="usr">Amount to Borrow:</label>

                                    <input type="number" class="form-control" id="usr" size="5" style="width:250px;" value = '<?php echo $rowHA['AMOUNT'];?>' disabled>

                                </div>

                                <div class="form-group">

                                    <label>Reason for the Health Aid Application: </label>

                                    <textarea id="noresize" class="form-control" rows="5" cols="125" disabled>
<?php echo $rowHA['REASON'];?>

                                    </textarea>

                                </div>

                            </div>

                        </div>

                    </div>





                    <div class= "col-lg-4">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Upload Receipt Here</b>

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
                    <div class= "col-lg-4">

                        <div class="panel panel-green">

                            <div class="panel-heading">

                                <b>Comments and Receipt</b>

                            </div>

                                <div class="form-group">

                                    <label>Comments</label>

                                    <textarea id="noresize" class="form-control" rows="5" cols="125" >

                                    </textarea>

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
                    
                <div class="row">
                    <div class="col-lg-1">
                        <input type="submit" name="accept" class="btn btn-success" value="Accept Application">
                        <input type="submit" name="reject" class="btn btn-danger" value="Reject Application">
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
