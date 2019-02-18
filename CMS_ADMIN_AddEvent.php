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
    $title = $_POST['post_title'];
    $body = $crud->escape_string($_POST['post_content']);
    $status = $_POST['submitStatus'];
    $startTime =  $_POST['event_start'];
    $endTime =  $_POST['event_end'];

    $delimitedInput = preg_replace('/\s+/', '', $_POST['post_emails']);
    $email_array = explode (",", $delimitedInput);

    $startTime = preg_replace('/\s+/', 'T', $startTime);
    $endTime = preg_replace('/\s+/', 'T', $endTime);

    if($status == 3) {

        include 'addToCalendar.php';

        $client = getClient();
        $service = new Google_Service_Calendar($client);

        $event = new Google_Service_Calendar_Event(array(
            'summary' => $title,
            'location' => 'Manila',
            'description' => $body,
            'start' => array(
                'dateTime' => $startTime,
                //'dateTime' => '2019-02-14T17:00:00',
                'timeZone' => 'Asia/Manila',
            ),
            'end' => array(
                //'dateTime' => '2019-02-14T17:00:00',
                'dateTime' => $endTime,
                'timeZone' => 'Asia/Manila',
            ),
            //'recurrence' => array(
            //    'RRULE:FREQ=DAILY;COUNT=2'
            //),
            'attendees' => array(
                array('email' => 'nicolealderite@gmail.com'),
                array('email' => 'sbrin@example.com'),
            ),
            'reminders' => array(
                'useDefault' => FALSE,
                'overrides' => array(
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            )
        ));

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        $eventId = $event->getId();
        $eventLink = $event->htmlLink;

    }

    $id = $crud->executeGetKey("INSERT INTO events (title, description, posterId, statusId, startTime, endTime, GOOGLE_EVENTID, GOOGLE_EVENTLINK) values ('$title', '$body','$userId','$statusId','$startTime','$endTime','$eventId','$eventLink')");
    if(!empty ($id)) {
        header("Location: http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "/CMS_ADMIN_EditEvent.php?postId=" . $id);
    }else{
        echo '<script language="javascript">';
        echo 'alert('.$eventLink.')';
        echo '</script>';
    }
}

$page_title = 'Santinig - Add Event';
include 'GLOBAL_HEADER.php';
include 'CMS_ADMIN_SIDEBAR.php';
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
            $('textarea').froalaEditor({
                // Disables video upload
                videoUpload: false,
                //
                imageUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_IMAGE_Upload.php',
                // Set the file upload URL.
                fileUploadURL: 'CMS_SERVER_INCLUDES/CMS_SERVER_FILE_Upload.php',

                width: 750
            });

            $('#datetimepicker1').datetimepicker( {
                minDate: moment(),
                locale: moment().local('ph'),
                defaultDate: moment().add(5,'minutes'),
                format: 'YYYY-MM-DD hh:mm:ss'
            });

            $('#datetimepicker2').datetimepicker( {
                minDate: moment().add(15, 'minutes'),
                locale: moment().local('ph'),
                defaultDate: moment().add(20, 'minutes'),
                format: 'YYYY-MM-DD hh:mm:ss'
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
                            <label for="post_content">Description</label>
                            <textarea name="post_content" id="post_content"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="post_emails">Invite through email (separate by comma) </label>
                            <input id="post_emails" name="post_emails" type="text" placeholder="Provide emails" class="form-control input-md"  required>
                        </div>

                    </div>
                    <div id="publishColumn" class="column col-lg-4" style="margin-bottom: 1rem;">

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="reference">References</label>
                                    <div id="reference">
                                        <button type="button" onclick="alertBox();" id="btnReference" name="btnReference" class="btn btn-sm">Add Reference</button><p></p>
                                        <input id="ref_1" name="ref_1" type="text" placeholder="No document referenced yet..." class="form-control input-sm" disabled required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card" style="margin-bottom: 1rem;">
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="submitStatus">Submit Action</label>
                                    <select class="form-control" id="submitStatus" name="submitStatus">
                                        <option value="1">Save as Draft</option>
                                        <?php if($cmsRole!=3 && $status!=3){ echo "<option value=\"2\">Submit for Approval</option>";}?>
                                        <?php if($cmsRole==3){ echo "<option value=\"3\">Approve</option>";}?>
                                        <?php if($cmsRole==3 && $status==4){ echo "<option value=\"5\">Finish Event</option>";}?>
                                        <?php if($cmsRole==3 && $status==3){ echo "<option value=\"6\">Cancel Event</option>";}?>
                                    </select>
                                </div>
                                <input type="hidden" id="post_id" name="post_id" value="<?php if(isset($postId)){ echo $postId;};?>">
                                <button type="submit" class="btn btn-primary" name="btnSubmit" id="btnSubmit">Submit</button>

                            </div>
                        </div>

                        <!-- Button -->

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /#page-wrapper -->
<?php include 'GLOBAL_FOOTER.php' ?>