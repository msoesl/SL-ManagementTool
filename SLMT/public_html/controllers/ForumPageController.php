<?php 
require_once('../views/import/headimportviews.php');

$projectId = false;
if(isset($_GET['project_id'])){
	$projectId = $_GET['project_id'];
}

$offset = false;
if(isset($_GET['offset'])) {
	$offset = $_GET['offset'];
}

$command = false;
if(isset($_GET['command'])){
	$command = $_GET['command'];
}

//projectId and offset might be 0
if($projectId !== false && $offset !== false && $command){
	if($command === 'get'){
		$threads = ForumModel::generateJsonFriendlyArray(ForumModel::getAllThreads($projectId, $offset));
		echo json_encode($threads);
	}
}
