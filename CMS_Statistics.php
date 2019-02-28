<?php
/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');

$page_title = 'Santinig - Posts Dashboard';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR_Admin.php';

$userId = $_SESSION['idnum'];

?>
<div class="content-wrapper" >
    <div class="container-fluid" id="printable">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Santinig Statistics
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <div class="col-lg-8">
                            <div class="form-group">
                                <label>Timeframe <span id="indicator">(Static)</span></label>
                                <select class="form-control timeframe">
                                    <option name="today" value="1" selected>
                                        Last 24 Hours
                                    </option>
                                    <option name="week" value="2">
                                        Last Week
                                    </option>
                                    <option name="month" value="3">
                                        Last Month
                                    </option>
                                    <option name="year" value="4">
                                        Last Year
                                    </option>
                                    <option name="5years" value="5">
                                        Last 5 Years
                                    </option>
                                    <option name="custom" value="6">
                                        Custom (Static Data)
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="printButton">Action</label>
                                <div class="form-group">
                                    <button type="button" id="btnRealtime" class="btn btn-success" name="btnRealtime"> Make Realtime </button>
                                    <button type="button" class="btn btn-default" name="btnPrint" onclick="printDiv('printable')"> Print </button>
                                </div>
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
                        <div class="col-lg-4">
                            <label for="timeSubmit">Action</label>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" name="timeSubmit" id="timeSubmit"> Load </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-body">
                        <div class="form-group">
                            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                <tr>

                                    <th align="left" width="200px"><b>Title</b></th>
                                    <th align="left" width="100px"><b>Previews</b></th>
                                    <th align="left" width="100px"><b>Views</b></th>
                                    <th align="right" width="100px"><b>Comments</b></th>
                                    <th></th>

                                </tr>
                                </thead>
                                <tfoot>
                                <tr>

                                    <th align="left" width="200px"><b>Title</b></th>
                                    <th align="left" width="100px"><b>Previews</b></th>
                                    <th align="left" width="100px"><b>Views</b></th>
                                    <th align="right" width="100px"><b>Comments</b></th>
                                    <th></th>

                                </tr>
                                </tfoot>
                                <tbody id="tbody">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="card" >
                    <div class="card-header">
                        <b>Collective Post Views</b>
                        <span class="loading_chart"></span>
                    </div>
                    <div class="card-body">
                        <div id="views" style="height: 250px;"></div>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Collective Post Previews</b>
                        <span class="loading_chart"></span>
                    </div>
                    <div class="card-body">
                        <div id="previews" style="height: 250px;"></div>
                    </div>
                </div>
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Comments</b>
                        <span class="loading_chart"></span>
                    </div>
                    <div class="card-body">
                        <div id="comments" style="height: 250px;"></div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row" style="margin-top: 1rem;">

            <div class="col-lg-4">

            </div>
            <div class="col-lg-4">

            </div>
        </div>

    </div>
    <!-- /.container-fluid -->


</div>
<!-- /.content-wrapper -->

</div>
<!-- /#wrapper -->

<script>
    $(document).ready(function() {

        $('#btnRealtime').on('click', function(){
            let option = $("select.timeframe").children("option:selected").val();
            if($(this).hasClass("btn-success")){
                $(this).removeClass("btn-success").addClass("btn-warning");
                $(this).html('Make Static');
                $('#indicator').html('(Realtime)');

                table = loadTable(table, option);
                myInt = setInterval(function(){
                    loadData(option, 'views', views_chart);
                    loadData(option, 'previews', previews_chart);
                    table.ajax.reload();
                },5000);
            }else{
                $(this).removeClass("btn-warning").addClass("btn-success");
                $(this).html('Make Realtime');
                $('#indicator').html('(Static)');
                clearInterval(myInt);
                //table = loadTable(table, option);
                //loadData(option, 'views', views_chart);
                //loadData(option, 'previews', previews_chart);
            }
        });

        var myInt;

        var table = $('#dataTable').DataTable();

        var views_chart = Morris.Area({
            element: 'views',
            behaveLikeLine: true,
            parseTime : true,
            data: [{ xmin: '0', count: 0, unique_count:0}],
            xkey: 'xmin',
            ykeys: ['count','unique_count'],
            labels: ['Views', 'Unique Views'],
            xLabelAngle: 45,
            pointFillColors: ['#707f9b'],
            pointStrokeColors: ['#ffaaab'],
            lineColors: ['#f26c4f', '#00a651'],
            redraw: true,
        });

        var previews_chart = Morris.Area({
            element: 'previews',
            behaveLikeLine: true,
            parseTime : true,
            data: [{ xmin: '0', count: 0, unique_count:0}],
            xkey: 'xmin',
            ykeys: ['count','unique_count'],
            labels: ['Previews', 'Unique Previews'],
            xLabelAngle: 45,
            pointFillColors: ['#707f9b'],
            pointStrokeColors: ['#ffaaab'],
            lineColors: ['#f26c4f', '#00a651'],
            redraw: true,
        });

        loadData('1', 'views', views_chart);
        loadData('1', 'previews', previews_chart);
        table = loadTable(table, '1');

        $('#datetimepicker1').datetimepicker( {
            locale: moment().local('ph'),
            maxDate: moment().subtract(1,'days'),
            format: 'YYYY-MM-DD HH:00:00'
        });

        $('#datetimepicker2').datetimepicker( {
            locale: moment().local('ph'),
            defaultDate: moment(),
            maxDate: moment(),
            format: 'YYYY-MM-D HH:00:00'
        });

        $('#timeSubmit').on('click', function(){
            loadData('6', 'views', views_chart, $('#event_start').val(), $('#event_end').val());
            loadData('6', 'previews', previews_chart, $('#event_start').val(), $('#event_end').val());
            table = loadTable(table,'6', $('#event_start').val(), $('#event_end').val());
        });

        $('#datetimepicker1').data("DateTimePicker").disable();
        $('#datetimepicker2').data("DateTimePicker").disable();
        $('#timeSubmit').attr('disabled','disabled');

        $('.loading_chart').append('<div class="fa fa-spinner fa-spin"></div>');

        $("select.timeframe").change(function(){
            clearInterval(myInt);
            let option = $(this).children("option:selected").val();
            $('.loading_chart').empty();
            if(option<6){
                $('#datetimepicker1').data("DateTimePicker").disable();
                $('#datetimepicker2').data("DateTimePicker").disable();
                $('#timeSubmit').attr('disabled','disabled');
                $('#btnRealtime').removeAttr('disabled');

                $('.loading_chart').append('<div class="fa fa-spinner fa-spin"></div>');

                table = loadTable(table, option);

                if($('#btnRealtime').html() === 'Make Static'){
                    myInt = setInterval(function(){
                        table.ajax.reload();
                        loadData(option, 'views', views_chart);
                        loadData(option, 'previews', previews_chart);
                    },5000);
                }else{
                    loadData(option, 'views', views_chart);
                    loadData(option, 'previews', previews_chart);
                }
            }else if (option == 6){
                $('#datetimepicker1').data("DateTimePicker").enable();
                $('#datetimepicker2').data("DateTimePicker").enable();
                $('#timeSubmit').removeAttr('disabled');
                $('#btnRealtime').attr('disabled','disabled');
            }

        });
    });

    function loadData(option, mode, chart, start, end){
        //$('#loading').append('<div class="fa fa-spinner fa-spin"></div><span> Loading, please wait...</span>');
        $.ajax({
            url:'CMS_AJAX_FetchViewStats.php',
            method:'POST',
            data:{option:option, mode:mode, start:start, end:end},
            dataType:"json",
            success:function(data)
            {
                chart.setData(data);
                $('.loading_chart').empty();
            }
        }).fail(function(){
            console.log('error, option and mode: '+option+mode+start+end);
        });
    }

    function loadTable(table, option, start, end){
        table.destroy();
        table = $('#dataTable').DataTable( {
            "ajax": {
                "url":"CMS_AJAX_FetchPostStats.php",
                "type":"POST",
                "data": {'option':option, 'start':start, 'end':end},
                "dataSrc": ''
            }, columns: [
                { data: "title" },
                { data: "views" },
                { data: "previews" },
                { data: "comments" },
                { data: "action" }
                /*and so on, keep adding data elements here for all your columns.*/
            ]
        } );
        return table;
    }

    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
</script>
