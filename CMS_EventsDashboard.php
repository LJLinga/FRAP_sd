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
<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Events
                    <button name="btnAddEvent" id="btnAddEvent" data-toggle="modal" data-target="#myModal" class="btn btn-primary">Add Event</button>
                </h3>
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped" id="tblAllEvents" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th align="left" width="200px"><b>Event</b></th>
                                <th align="left" width="200px"><b>Start</b></th>
                                <th align="left" width="200px"><b>End</b></th>
                                <th align="left" width="200px"><b>Created by</b></th>
                                <th align="left" width="200px"><b>Created on</b></th>
                                <th align="left" width="200px"><b>Attached to Post</b></th>
                                <th align="left" width="200px"><b>Action</b></th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th align="left" width="200px"><b>Event</b></th>
                                <th align="left" width="200px"><b>Start</b></th>
                                <th align="left" width="200px"><b>End</b></th>
                                <th align="left" width="200px"><b>Created by</b></th>
                                <th align="left" width="200px"><b>Created on</b></th>
                                <th align="left" width="200px"><b>Attached to Post</b></th>
                                <th align="left" width="200px"><b>Action</b></th>
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
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content" id="myModalContent">
                <form method="POST" id="addEventForm">
                    <input type="hidden" name="userId" value="<?php echo $userId; ?>">
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
                            <label for="event_desc">Invite Members</label>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-success" id="btnInviteAll" onclick="addAllEmails()">Invite All</button>
                                <button type="button" class="btn btn-sm btn-warning" id="btnRemoveAll" onclick="removeAllEmails()" disabled>Remove All</button>
                            </div>
                            <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="addEmails">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="card" style="min-height: 10rem; max-height: 10rem; overflow: auto;" id="toAddEmails">
                                <?php
                                $rows = $crud->getData("SELECT CONCAT(e.LASTNAME,', ',e.LASTNAME) as name, e.EMAIL, e.MEMBER_ID FROM MEMBER e;");
                                foreach((array)$rows as $key=>$row){
                                    echo '<div class="btn btn-default btn-sm btn_add_email" id="email'.$row['MEMBER_ID'].'" onclick="addEmail(this,&quot;'.$row['EMAIL'].'&quot;,&quot;'.$row['name'].'&quot;)" style="text-align: left;"><b>'.$row['name'].' ('.$row['EMAIL'].')</b></div>';
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
                </form>
            </div>
    </div>
</div>
<script>
    $("#myModal").on("show.bs.modal", function() {
        $('#eventTitle').val('');
        $('#eventDescription').val('');
        $('#err').html('');
        removeAllEmails();
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
    });

    let table = $('#tblAllEvents').DataTable( {
        bSort: true,
        destroy: true,
        pageLength: 10,
        aaSorting: [],
        "ajax": {
            "url":"CMS_AJAX_FetchEvents.php",
            "type":"POST",
            "dataSrc": '',
            "data":{requestType: 'ALL'}
        },
        columns: [
            { data: "event" },
            { data: "start" },
            { data: "end"},
            { data: "added_by"},
            { data: "added_on"},
            { data: "permalink"},
            { data: "action"}
        ]
    });

    $("#addEventForm").on('submit', function(e){
        e.preventDefault();
        //$('#myModal').modal({backdrop: 'static', keyboard: false});
        $('#btnSubmit').attr('disabled',true);
        $('#err').html('<div class="alert alert-info">Adding event into Google Calendar. Please wait.</div>');
        $.ajax({
            type: "POST",
            url: "CMS_AJAX_AddEvent.php",
            cache: false,
            processData: false,
            contentType: false,
            data: new FormData(this),
            success: function(response){
                if(response === 'success'){
                    $('#myModal').modal('toggle');
                }else{
                    $('#err').html('<div class="alert alert-warning"><strong> Adding of event unsuccessful: </strong>'+response+'</div>');
                }
                $('#btnSubmit').attr('disabled',false);
                table.ajax.reload();
            },
            error: function(){
                $('#myModal').modal('toggle');
                table.ajax.reload();
                $('#btnSubmit').attr('disabled',false);
            }
        });
        return false;
    });
    function addAllEmails(){
        $('#btnInviteAll').attr('disabled',true);
        $('#btnRemoveAll').attr('disabled',false);
        $('.btn_remove_email').click();
        $('.btn_add_email').click();
    }
    function removeAllEmails(){
        $('#btnInviteAll').attr('disabled',false);
        $('#btnRemoveAll').attr('disabled',true);
        $('.btn_remove_email').click();
    }

    function addEmail(element, email, name){
        $(element).hide()
        let element_id = $(element).attr('id');
        $('#addEmails').append('<div class="btn btn-success btn-sm btn_remove_email" onclick="removeEmail(this,&quot;'+element_id+'&quot;,&quot;'+email+'&quot;,&quot;'+name+'&quot;)" style="text-align: left;"><b>'+name+' ('+email+')</b>' +
            '<input type="hidden" name="toAddEmails[]" value="'+email+'"></div>');
    }
    function removeEmail(element,id, email, name){
        $(element).remove();
        $('#btnInviteAll').attr('disabled',false);
        $('#'+id).show();
    }
</script>
<?php include 'GLOBAL_FOOTER.php'?>
