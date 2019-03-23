<?php

require_once __DIR__ . '/vendor/autoload.php';

include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();
require_once('mysql_connect_FA.php');
session_start();
include('GLOBAL_USER_TYPE_CHECKING.php');
include('GLOBAL_CMS_ADMIN_CHECKING.php');
$userId = $_SESSION['idnum'];

/**
 * Created by PhpStorm.
 * User: nicol
 * Date: 10/10/2018
 * Time: 3:48 PM
 */

if(isset($_POST['btnSubmit'])){
    $title = $crud->escape_string($_POST['post_title']);
    $body = $crud->escape_string($_POST['post_content']);
    $status = 3;
    $startTime =  $_POST['event_start'];
    $endTime =  $_POST['event_end'];

    $delimitedInput = preg_replace('/\s+/', '', $_POST['post_emails']);
    $email_array = explode (",", $delimitedInput);
    $data = [];
    foreach((array) $email_array as $key=>$value) {
        $data[] = array('email'=>$value);
    }

    $startTime = preg_replace('/\s+/', 'T', $startTime);
    $endTime = preg_replace('/\s+/', 'T', $endTime);

    if($status == 3) {

        include 'CMS_API_AddToCalendar.php';

        $client = getClient();
        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $title,
            'location' => 'Manila',
            'description' => $body,
            'start' => array(
                'dateTime' => $startTime,
                //'dateTime' => '2019-02-21T17:00:00',
                'timeZone' => 'Asia/Manila',
            ),
            'end' => array(
                //'dateTime' => '2019-02-21T17:15:00',
                'dateTime' => $endTime,
                'timeZone' => 'Asia/Manila',
            ),
            //'recurrence' => array(
            //    'RRULE:FREQ=DAILY;COUNT=2'
            //),
            'attendees' => $data,
            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 120 * 60),
                    array('method' => 'email', 'minutes' => 72 * 60),
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'email', 'minutes' => 180),
                    array('method' => 'popup', 'minutes' => 180),
                    array('method' => 'email', 'minutes' => 60),
                    array('method' => 'popup', 'minutes' => 60),
                    array('method' => 'email', 'minutes' => 30),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            )
        ));

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        $eventId = $event->getId();
        $eventLink = $event->htmlLink;

        $id = $crud->executeGetKey("INSERT INTO events (title, description, posterId, startTime, endTime, GOOGLE_EVENTID, GOOGLE_EVENTLINK) values ('$title', '$body','$userId','$startTime','$endTime','$eventId','$eventLink')");
    }
}

$page_title = 'Santinig - Add Event';
include 'GLOBAL_HEADER.php';
include 'CMS_SIDEBAR.php';
?>
    <style>
        @media screen and (min-width: 1200px) {
            #publishColumn{
                position: fixed;
                right:1rem;
            }
        }
        @media screen and (max-width: 1199px) {
            #publishColumn{
                position: relative;
            }
        }
    </style>
    <script>
        $(document).ready( function(){



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

            $('#toAddUsers').dataTable(function(){
                // Load to Add Users here
            });

            $('#addedUsers').dataTable(function(){
                // Load to added users
            });
        });
    </script>

    <div id="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="page-header">
                        Add New Event
                    </h3>

                </div>
            </div>
            <!--Insert success page-->
            <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                <div class="row">
                    <div class="column col-lg-7">
                        <!-- Text input-->

                        <div class="form-group">
                            <label for="post_title">Title</label>
                            <input id="post_title" name="post_title" type="text" placeholder="Provide title..." class="form-control input-md"  required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="event_start">Start Date</label>
                                    <div class='input-group date' id='datetimepicker1'>
                                        <input id="event_start" name="event_start" type='text' class="form-control" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="event_end">End Date</label>
                                    <div class='input-group date' id='datetimepicker2'>
                                        <input id="event_end" name="event_end" type='text' class="form-control" />
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Textarea -->
                        <div class="form-group">
                            <label for="post_content">Description </label>
                            <textarea class="form-control" name="post_content" rows="5" id="post_content">
                            </textarea>
                            <?php echo $details; ?><br>
                            <?php echo $calendarDetails; ?>
                        </div>



                    </div>
                    <div id="publishColumn" class="column col-lg-4" style="margin-bottom: 1rem;">

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-header">
                                <label for="post_emails">Invite through email</label>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <input id="post_emails" name="post_emails" type="text" placeholder="Provide emails" class="form-control input-md" data-toggle="modal" data-target="#myModal" required>
                                </div>
                            </div>
                        </div>
                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <div class="form-group">
                                    <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                                    <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog" data-backdrop="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->
                    <h5 class="modal-title">Invite People</h5>
                </div>
                <div class="modal-body">

                    <form id="form" name="form" method="POST" action="<?php $_SERVER["PHP_SELF"]?>">
                        <table class="table table-bordered" align="center" id="toAddUsers">
                            <thead>
                            <tr>
                                <th>
                                    User
                                </th>
                                <th>
                                    Action
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>
                                    Alderite, Christian <br>
                                    <span id="user_email">nicolealderite@gmail.com</span>
                                </td>
                                <td>
                                    <button class="btn btn-primary"> Add </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <input type="submit" class="btn btn-primary" value="Add">
                </div>
            </div>

        </div>
    </div>
<?php include 'GLOBAL_FOOTER.php' ?>