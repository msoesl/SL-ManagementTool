<?php
header('Content-type: text/json');
require_once('../views/import/headimportviews.php');
session_start();
//Project ID
$id = $_GET ['id'];

$project = new ProjectModel($id);

$user;
$edit = 0;
$joinable = 0;
if(isset($_SESSION ['user'])){
	$user = $_SESSION ['user'];
	
	if(isset($project) && $project->getProjectOwner()->getAccountId() === $user->getAccountId()){
		$edit = 1;
	}
	$priv_id = ORM::for_table ( "project_has_account" )->select('project_privileges_id')->where('Project_id', $id)->where('Account_id', $user->getAccountId())->find_one();
	if($priv_id){
		$pp_id = ORM::for_table ( "project_privileges" )->where('id', $priv_id->Project_Privileges_id)->find_one();
		if($pp_id && $pp_id->collaborator_permission == 1){
			$joinable = 1;
		}
	}
}

$retVal = array ();
$events = ORM::for_table ( "event" )->where('project_id', $id)->find_many();
//check if logged in user (if any) can edit
foreach ( $events as $event ) {
	$event_account;
	$joined = 0;
	if(isset($user)){
		$event_account = ORM::for_table ( "event_has_account" )->where('event_id', $event->id)->where('account_id', $user->getAccountId())->find_one();
		if($event_account){
			$joined = 1;
		}
	}
	$event = array (
			'id' => $event->id,
			'date' => $event->date_time,
			'type' => $event->type,
			'title' => $event->title,
			'description' => $event->description,
			'edit' => $edit,
			'joinable' => $joinable,
			'joined' => $joined
	);
	array_push ( $retVal, $event );
}
echo json_encode ( $retVal );
?>