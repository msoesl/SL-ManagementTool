<?php
header('Content-type: text/json');
echo '[';
$separator = "";
$days = 16;
	echo '	{ "date": "1314579600000", "type": "meeting", "title": "Test Last Year", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" },';
	echo '	{ "date": "1377738000000", "type": "meeting", "title": "Test Next Year", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" },';
for ($i = 1 ; $i < $days; $i= 1 + $i * 2) {
	echo $separator;
	$initTime = (intval(microtime(true))*1000) + (86400000 * ($i-($days/2)));
	echo '	{ "date": "'; echo $initTime; echo '", "type": "meeting", "title": "Project '; echo $i; echo ' meeting", "description": "Lorem Ipsum dolor set", "url": "http://www.event1.com/" },';
	echo '	{ "date": "'; echo $initTime+3600000; echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event2.com/" },';

	echo '	{ "date": "'; echo $initTime-7200000; echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event3.com/" },';
	echo '	{ "date": "'; echo $initTime+1800000; echo '", "type": "meeting", "title": "Project '; echo $i; echo ' meeting", "description": "Lorem Ipsum dolor set", "url": "http://www.event5.com/" },';
	echo '	{ "date": "'; echo $initTime+3600000+2592000000; echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event6.com/" },';
	echo '	{ "date": "'; echo $initTime-7200000+2592000000; echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event7.com/" },';
	echo '	{ "date": "'; echo $initTime+10800000+2592000000; echo '", "type": "test", "title": "A very very long name for a f*cking project '; echo $i; echo ' events", "description": "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.", "url": "http://www.event8.com/" },';
	echo '	{ "date": "'; echo $initTime+3600000-2592000000; echo '", "type": "demo", "title": "Project '; echo $i; echo ' demo", "description": "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.", "url": "http://www.event9.com/" },';
	echo '	{ "date": "'; echo $initTime-7200000-2592000000; echo '", "type": "meeting", "title": "Test Project '; echo $i; echo ' Brainstorming", "description": "Lorem Ipsum dolor set", "url": "http://www.event10.com/" },';
	echo '	{ "date": "'; echo $initTime+10800000-2592000000; echo '", "type": "test", "title": "A very very long name for a f*cking project '; echo $i; echo ' events", "description": "Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam.", "url": "http://www.event11.com/" }';
	$separator = ",";
}
echo ']';
?>