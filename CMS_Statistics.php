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
include 'CMS_SIDEBAR.php';

$userId = $_SESSION['idnum'];

?>
<div id="content-wrapper">

    <div class="container-fluid">

        <div class="row">
            <div class="col-lg-12">
                <h3 class="page-header">
                    Santinig Statistics
                </h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="form-group">
                            <label>Timeframe</label>
                            <select class="form-control timeframe">
                                <option name="today" value="1" selected>
                                    Today
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
                                    Custom
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 1rem;">
            <div class="col-lg-6">
                <div class="card" style="margin-top: 1rem;">
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
            </div>
            <div class="col-lg-6">
                <div class="card" style="margin-top: 1rem;">
                    <div class="card-header">
                        <b>Comments</b>
                        <span class="loading_chart"></span>
                    </div>
                    <div class="card-body">
                        <div id="myfirstchart" style="height: 250px;"></div>
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

<script>
    $(document).ready(function() {

        var views_chart = Morris.Area({
            element: 'views',
            behaveLikeLine: true,
            parseTime : false,
            data: [{ xmin: '0', count: 0}],
            xkey: 'xmin',
            ykeys: ['count'],
            labels: ['Views'],
            xLabelAngle: 45,
            pointFillColors: ['#707f9b'],
            pointStrokeColors: ['#ffaaab'],
            lineColors: ['#f26c4f', '#00a651', '#00bff3'],
            redraw: true,
        });

        var previews_chart = Morris.Area({
            element: 'previews',
            behaveLikeLine: true,
            parseTime : false,
            data: [{ xmin: '0', count: 0}],
            xkey: 'xmin',
            ykeys: ['count'],
            labels: ['Previews'],
            xLabelAngle: 45,
            pointFillColors: ['#707f9b'],
            pointStrokeColors: ['#ffaaab'],
            lineColors: ['#f26c4f', '#00a651', '#00bff3'],
            redraw: true,
        });

        let myInt = setInterval(function(){
            loadData('1', 'views', views_chart);
            loadData('1', 'previews', previews_chart);
        },5000);

        $("select.timeframe").change(function(){
            let option = $(this).children("option:selected").val();
            $('.loading_chart').empty();
            if(option<6){
                $('.loading_chart').append('<div class="fa fa-spinner fa-spin"></div>');
                clearInterval(myInt);

                myInt = setInterval(function(){
                    loadData(option, 'views', views_chart);
                    loadData(option, 'previews', previews_chart);
                },5000);
            }else{

            }

        });
    });

    function loadData(option, mode, chart){
        //$('#loading').append('<div class="fa fa-spinner fa-spin"></div><span> Loading, please wait...</span>');
        $.ajax({
            url:'CMS_AJAX_FetchStatistics.php',
            method:'POST',
            data:{option:option, mode:mode},
            dataType:"json",
            success:function(data)
            {
                chart.setData(data);
                $('.loading_chart').empty();
            }
        }).fail(function(){
            console.log('error, option and mode: '+option+mode);
        });
    }
</script>
