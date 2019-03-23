<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

require_once __DIR__ . '/vendor/autoload.php';

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$userId = $_SESSION['idnum'];

$page_title = 'Santinig - Events Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>

<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-8">
                <h3 class="page-header">
                    Events
                    <button name="btnAddEvent" id="btnAddEvent" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Event</button>
                </h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th align="left" width="300px"><b>Event</b></th>
                                <th align="left" width="200px"><b>Start</b></th>
                                <th align="left" width="200px"><b>End</b></th>
                                <th align="left" width="100px"><b>GCal</b></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th align="left" width="300px"><b>Event</b></th>
                                <th align="left" width="200px"><b>Start</b></th>
                                <th align="left" width="200px"><b>End</b></th>
                                <th align="left" width="100px"><b>GCal</b></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <form method="POST" id="addEventForm">
            <input type="hidden" name="userId" value="<?php echo $userId; ?>">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <b>Add Event</b>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="eventTitle">Name</label>
                        <input type="text" name="event_title" id="eventTitle" class="form-control" placeholder="Name your event..." required>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="eventStart">Start Date</label>
                                <div class="input-group date" id="datetimepicker1">
                                    <input id="event_start" name="event_start" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="eventEnd">End Date</label>
                                <div class="input-group date" id="datetimepicker2">
                                    <input id="event_end" name="event_end" type="text" class="form-control">
                                    <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="event_desc">Description</label>
                        <textarea name="event_content" id="eventDescription" class="form-control" rows="5" placeholder="Describe your event..." required></textarea>
                    </div>
                    <div class="form-group">
                        <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="addEmails">
                        </div>
                        <span id="formAppend"></span>
                    </div>
                    <div class="form-group">
                        <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="toAddEmails">
                            <?php
                                $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.LASTNAME) as name, e.EMAIL FROM MEMBER e;");
                                foreach((array)$rows as $key=>$row){
                                    echo '<div class="btn btn-default btn-sm" onclick="addEmail(this,&quot;'.$row['EMAIL'].'&quot;)" style="text-align: left;"><b>'.$row['name'].' ('.$row['EMAIL'].')</b></div>';
                                }
                            ?>
                        </div>
                    </div>
                    <span id="err"></span>
                </div>
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <input type="submit" name="btnSubmit" id="btnSubmit" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('table.table').DataTable( {
        bSort: false,
        destroy: true,
        pageLength: 5,
        "ajax": {
            "url":"CMS_AJAX_FetchEvents.php",
            "type":"POST",
            "dataSrc": ''
        },
        columns: [
            { data: "event" },
            { data: "start" },
            { data: "end"},
            { data: "action"}
        ]
    });
    $('#datetimepicker1').datetimepicker( {
        minDate: moment(),
        locale: moment().local('ph'),
        defaultDate: moment().add(5,'minutes'),
        format: 'YYYY-MM-DD HH:mm:ss'
    });
    $('#datetimepicker2').datetimepicker( {
        minDate: moment().add(15, 'minutes'),
        locale: moment().local('ph'),
        defaultDate: moment().add(20, 'minutes'),
        format: 'YYYY-MM-DD HH:mm:ss'
    });

    $("#addEventForm").on('submit', function(e){
        e.preventDefault();
        $('#myModal').modal({backdrop: 'static', keyboard: false});
        $.ajax({
            type: "POST",
            url: "CMS_AJAX_AddEvent.php",
            cache: false,
            processData: false,
            contentType: false,
            data: new FormData(this),
            success: function(response){
                $('#myModal').find('form')[0].reset();
                $('#myModal').modal('toggle');
            },
            error: function(){
            }
        });
        return false;
    });
    function addEmail(element, email){
        $(element).removeClass("btn-default");
        $(element).addClass("btn-success");
        $(element).removeAttr('onclick');
        $(element).on('click', function(){ removeEmail(element, email); });
        $(element).appendTo($('#addEmails'));
        $('#formAppend').append('<div id="'+email+'"><input type="hidden" name="toAddEmails[]" value="'+email+'"></div>');
    }
    function removeEmail(element, email){
        $(element).removeClass("btn-success");
        $(element).addClass("btn-default");
        $(element).off();
        $(element).on('click', function(){ addEmail(element, email); });
        $(element).appendTo($('#toAddEmails'));
        $('#'+email).remove();
    }
</script>
<?php include 'GLOBAL_FOOTER.php'?>
