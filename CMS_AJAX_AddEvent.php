<?php
/**
 * Created by PhpStorm.
 * User: Christian
 * Date: 3/23/2019
 * Time: 11:28 PM
 */

require_once __DIR__ . '/vendor/autoload.php';
include_once('GLOBAL_CLASS_CRUD.php');
$crud = new GLOBAL_CLASS_CRUD();

    $title = $crud->escape_string($_POST['event_title']);
    $body = $crud->escape_string($_POST['event_content']);
    $status = 3;
    $startTime =  $_POST['event_start'];
    $endTime =  $_POST['event_end'];

    $userId =  $_POST['userId'];

    $email_array = $_POST['toAddEmails'];
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
                    array('method' => 'email', 'minutes' => 24 * 60),
                    array('method' => 'email', 'minutes' => 180),
                    array('method' => 'popup', 'minutes' => 60),
                    array('method' => 'popup', 'minutes' => 10),
                ),
            )
        ));

        $calendarId = 'primary';
        $event = $service->events->insert($calendarId, $event);
        $eventId = $event->getId();
        $eventLink = $event->htmlLink;

        $id = $crud->executeGetKey("INSERT INTO events (title, description, posterId, startTime, endTime, GOOGLE_EVENTID, GOOGLE_EVENTLINK) values ('$title', '$body','$userId','$startTime','$endTime','$eventId','$eventLink')");

        echo $id;
    }
