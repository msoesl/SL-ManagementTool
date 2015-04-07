<?php
require_once('../views/import/headimportviews.php');


session_start ();
// Get the post data
$postData = $_POST['array'];
// Check if the user is logged in
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
	if(isset($user)){
		// Get the last element in the array which is the ID
		// of the user will be invited
		$inviteeId = array_pop($postData);
		// Message that will hold the result
		$message='';
		foreach ($postData as $projectId){
			$project = new ProjectModel($projectId);
			$result = invetUser($inviteeId,$projectId );
			
		if($result == 'success'){
				$message= $message .'User was invited to ' .$project->getProjectTitle() .'.
				';
			}else{
				$message= $message .'User was NOT invited to ' .$project->getProjectTitle() .'.
				';
			}
		}
		echo json_encode(array('success'=>$message));

	}else{
		echo json_encode(array('failure'=>'You are not allowed to invite.'));
	}
}else{
	echo json_encode(array('failure'=>'You are not allowed to invite.'));
}



// Add the invite to the DB and make it Pending status
function invetUser($user, $projId){
	
	
	$existProjectHasAccount = ORM::for_table('project_has_account')->where('Account_id', $user)->where('Project_id',$projId)->find_one();
	
	if($existProjectHasAccount==FALSE){// means it doesn't exist.
	
	$newInvitePermission = ORM::for_table('invite_permission')->create();
	$newInvitePermission->invite_status = 0;// means invite has not been
	$newInvitePermission->new = 1;
	$newInvitePermission->save();
	
	
	$newProjectPrivileges = ORM::for_table('project_privileges')->create();
	$newProjectPrivileges->moderate_discussions_permission = 0;
	$newProjectPrivileges->edit_project_content_permission = 0;
	$newProjectPrivileges->control_project_stages_permission = 0;
	$newProjectPrivileges->delete_users_from_project_permission = 0;
	$newProjectPrivileges->collaborator_permission = 0;// Means a pending status.
	$newProjectPrivileges->invite_permission_id = $newInvitePermission->id;
	$newProjectPrivileges->save();

	
	$newProjectHasAccount = ORM::for_table('project_has_account')->create();
	$newProjectHasAccount->Project_id = $projId;
	$newProjectHasAccount->Account_id = $user;
	$newProjectHasAccount->Account_Title_ID = 2;// Means a collaborator
	$newProjectHasAccount->Project_Privileges_id = $newProjectPrivileges->id;
	$newProjectHasAccount->save();
		return 'success';
	}else{
		return 'fail';
	}
	
	
	
}
