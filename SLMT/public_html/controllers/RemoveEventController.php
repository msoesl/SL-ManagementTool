<?php
require_once('../views/import/headimportviews.php');
session_start();
$event = null;
if(isset($_GET['id']) && isset($_SESSION['user'])){
	$user = $_SESSION ['user'];
	$event = ORM::for_table ( "event" )->where('id', $_GET['id'])->find_one();
	$projectId = $event->project_id;
	$project = new ProjectModel($projectId);
	if($project->getProjectOwner()->getAccountId() === $user->getAccountId()){
		$event->delete();
	}
}
?>