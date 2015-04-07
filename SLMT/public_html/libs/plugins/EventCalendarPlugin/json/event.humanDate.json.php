<?php
header('Content-type: text/json');
echo '[';
$separator = "";
$days = 16;
 //   echo '  { "date": "2013-03-19 17:30:00", "type": "meeting", "title": "Test Last Year", "description": "Lorem Ipsum dolor set", "url": "" },';
  //  echo '  { "date": "2013-03-23 17:30:00", "type": "meeting", "title": "Test Next Year", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" },';

$i = 1;
    echo $separator;
    $initTime = date("Y")."-".date("m")."-".date("d")." ".date("H").":00:00";
    //$initTime = date("Y-m-d H:i:00");
    echo '  { "date": "2013-04-01 17:30:00", "type": "meeting", "title": "Test Last Year", "description": "Lorem Ipsum dolor set", "url": "" },';
    echo '  { "date": "2013-04-02 17:30:00", "type": "meeting", "title": "Test Last Year", "description": "Lorem Ipsum dolor set", "url": "" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 1 days')); echo '", "type": "meeting", "title": "Project '; echo $i; echo ' meeting", "description": "Lorem Ipsum dolor set", "url": "" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 1 days + 4 hours')); echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event2.com/" },';

    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 1 days 8 hours')); echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 2 days 3 hours')); echo '", "type": "meeting", "title": "Project '; echo $i; echo ' meeting", "description": "Lorem Ipsum dolor set", "url": "http://www.event5.com/" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 4 days 3 hours')); echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event6.com/" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 7 days 1 hours')); echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event7.com/" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 20 days 10 hours')); echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event9.com/" },';
    echo '  { "date": "'; echo date("Y-m-d H:i:00",strtotime($initTime. ' + 22 days 3 hours')); echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event10.com/" },';
    $separator = ",";

echo ']';
?>