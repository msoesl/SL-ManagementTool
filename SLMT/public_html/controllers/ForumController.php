<?php
require_once ('../views/import/headimportviews.php');
$message = $_GET ['message'];
$pid = $_GET['project_id'];
$project = new ProjectModel($pid);
session_start ();

$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$perms = false;
if (isset($user)) {
	$perms = ProjectModel::getAccountPermissionsForProjectWithId($user->getAccountId(), $pid);
}
switch ($message) {
	case 'subscribe':
		echo json_encode(ForumModel::subscribeUserToForum($project->getProjectId(), $user->getAccountId()));	
		break;
}