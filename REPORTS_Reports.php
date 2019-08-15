<?php
/**
 * Created by PhpStorm.
 * User: Christian Alderite
 * Date: 10/4/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');

$alertType = '';
$alertMessage = '';

$page_title = 'Workspace';

include_once 'GLOBAL_ALERTS.php';
include_once 'GLOBAL_HEADER.php';
include_once 'FRAP_ADMIN_SIDEBAR.php';

$userId = $_SESSION['idnum'];
$dateNotSet = false;

function setGrouping($start, $end, $variableName){
    $groupBy = "";
    $diff=date_diff(date_create($start),date_create($end));
    $diff=$diff->format('%a');
    if($diff<=1 || $start == $end){
        $groupBy = "HOUR(".$variableName.")";
    }else if($diff>1 && $diff<=31) {
        $groupBy = "DATE(".$variableName.")";
    } else if ($diff>31 && $diff<=365){
        $groupBy = "MONTH(".$variableName.")";
    } else if ($diff>365){
        $groupBy = "YEAR(".$variableName.")";
    }
    return $groupBy;
}

function setInterval($start, $end, $variableName){
    return "AND ".$variableName." BETWEEN ('$start') AND ('$end')";
}

if(isset($_POST['btnLoad'])){
    if(isset($_POST['start']) && isset($_POST['end'])){
    }else{
       $dateNotSet = true;
    }
}

?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header"> Exception Reports
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?php if($dateNotSet){ ?>
                    <div class="alert alert-warning">
                        Date not set. Please set dates.
                    </div>
                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label>Timeframe</label>
                                    <select class="form-control timeframe">
                                        <option name="month" value="1" selected>
                                            This Month
                                        </option>
                                        <option name="term" value="2">
                                            This Term
                                        </option>
                                        <option name="year" value="3">
                                            This Year
                                        </option>
                                        <option name="five_years" value="4">
                                            Last Five Years
                                        </option>
                                        <option name="custom" value="5">
                                            Custom Range
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="event_start">Start Date</label>
                                    <div class="input-group date" id="datetimepicker1">
                                        <input id="event_start" name="event_start" type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="event_end">End Date</label>
                                    <div class="input-group date" id="datetimepicker2">
                                        <input id="event_end" name="event_end" type="text" class="form-control">
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <button type="button" class="btn btn-default" name="btnPrint" onclick="printDiv('printable')"> Print </button>
                                <button type="button" class="btn btn-primary" name="timeSubmit" id="timeSubmit"> Load </button>
                            </div>
                        </div>
                    </div>
                </div>
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Report 1</a></li>
                    <li role="presentation"><a href="#editing" aria-controls="editing" role="tab" data-toggle="tab">Report 2</a></li>
                    <li role="presentation"><a href="#access" aria-controls="access" role="tab" data-toggle="tab">Report 3</a></li>
                    <li role="presentation"><a href="#archived" aria-controls="archived" role="tab" data-toggle="tab">Report 4</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="home">
                        <div class="panel panel-secondary">
                            <div class="panel-body">
                                <?php
                                    $haInterval = setInterval($start, $end, "ha.DATE_APPROVED");
                                    $fapInterval = setInterval($start, $end, "l.DATE_APPROVED");
                                    $report1Grouping = setGrouping($start, $end, "m.DATE_APPROVED");
                                    $query = "SELECT 
                                                    (SELECT COUNT(m1.MEMBER_ID) 
                                                        FROM member m1 LEFT JOIN health_aid ha 
                                                        ON m1.MEMBER_ID = ha.MEMBER_ID
                                                        WHERE  m.MEMBER_ID = m1.MEMBER_ID AND m1.HA_STATUS = 2 '$haInterval') AS 'haCount', 
                                                    (SELECT COUNT(m2.MEMBER_ID) 
                                                        FROM member m2 LEFT JOIN loans l
                                                        ON m2.MEMBER_ID = l.MEMBER_ID
                                                        WHERE  m.MEMBER_ID = m2.MEMBER_ID AND l.LOAN_STATUS = 2 '$fapInterval') AS 'fapCount',     
                                                    (SELECT COUNT(m3.MEMBER_ID) 
                                                        FROM member m3 LEFT JOIN lifetime lt
                                                        ON m3.MEMBER_ID = lt.MEMBER_ID
                                                        WHERE  m.MEMBER_ID = m3.MEMBER_ID AND lt.APP_STATUS = 2) AS 'ltCount',         
                                                        dept.DEPT_NAME
                                                FROM member m JOIN ref_department dept ON m.DEPT_ID = dept.DEPT_ID
                                                GROUP BY dept.DEPT_ID, '$report1Grouping'
                                                ";

                                    $rows=$crud->getData($query);

                                ?>
                                <table id="myTable1" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Time</th>
                                        <th>Department</th>
                                        <th>Active Loans</th>
                                        <th>Active Health Aid</th>
                                        <th>Active Lifetime</th>
                                    </tr>
                                    </thead>
                                    <tbody                                    <tr>
                                        <td>TIME</td>
                                        <td>DEPT</td>
                                        <td>0</td>
                                        <td>0</td>
                                        <td>0</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="editing">
                        <div class="panel panel-secondary">
                            <div class="panel-body">
                                <table id="myTable1" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Version</th>
                                        <th>Submitted by</th>
                                        <th>Submitted on</th>
                                        <th>Status</th>
                                        <th>Last modified on</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="access">
                        <div class="panel panel-secondary">
                            <div class="panel-body">
                                <table id="myTable1" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Version</th>
                                        <th>Submitted by</th>
                                        <th>Submitted on</th>
                                        <th>Status</th>
                                        <th>Last modified on</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="archived">
                        <div class="panel panel-secondary">
                            <div class="panel-body">
                                <table id="myTable1" class="table table-striped table-responsive table-condensed table-sm" cellspacing="0" width="100%">
                                    <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Version</th>
                                        <th>Submitted by</th>
                                        <th>Submitted on</th>
                                        <th>Status</th>
                                        <th>Last modified on</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<?php include 'GLOBAL_FOOTER.php';?>
<script>
    $(document).ready(function() {

        $('#navSideItemWorkspace').addClass('active');

        $('[data-toggle="tooltip"]').tooltip();

        var startDatePicker = $('#datetimepicker1').datetimepicker( {
            locale: moment().local('ph'),
            maxDate: moment().subtract(1,'days'),
            format: 'YYYY-MM-DD HH:00:00'
        });

        var endDatePicker = $('#datetimepicker2').datetimepicker( {
            locale: moment().local('ph'),
            defaultDate: moment(),
            maxDate: moment(),
            format: 'YYYY-MM-D HH:00:00'
        });

        startDatePicker.data("DateTimePicker").disable();
        startDatePicker.data("DateTimePicker").date(moment().subtract(1,'months'));
        endDatePicker.data("DateTimePicker").disable();

        $("select.timeframe").change(function(){
            let option = $(this).children("option:selected").val();
            $('.loading_chart').empty();
            if(option<5){
                startDatePicker.data("DateTimePicker").disable();
                endDatePicker.data("DateTimePicker").disable();
                if(option == 1){
                    startDatePicker.data("DateTimePicker").date(moment().subtract(1,'months'));
                }else if(option == 2){
                    startDatePicker.data("DateTimePicker").date(moment().subtract(3,'months'));
                }else if(option == 3){
                    startDatePicker.data("DateTimePicker").date(moment().subtract(1,'years'));
                }else if(option == 4){
                    startDatePicker.data("DateTimePicker").date(moment().subtract(5,'years'));
                }
            }else if (option == 5){
                startDatePicker.data("DateTimePicker").enable();
                endDatePicker.data("DateTimePicker").enable();
            }
        });

    } );

    $('[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        $.fn.dataTable.tables( {visible: true, api: true} ).columns.adjust();
    } );

    let table = $('#myTable1').DataTable( {
        bSort: true,
        scrollX: true,
        destroy: true,
        pageResize: true,
        pageLength: 10,
        aaSorting: []
    });

    $('#searchField').keyup(function(){
        table.search($('#searchField').val()).draw();
    });
</script>
<?php include_once 'GLOBAL_FOOTER.php';?>


