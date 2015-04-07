<?php

require_once('../views/import/headimportviews.php');

session_start();
// User holds the login user
$user;
// Check if the user is logged in
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}

$isSent = FALSE;

// Check if the user is set
if(isset($user)){
	// Who will receive the Email 
	$to;
	// Who will send the Email
	$from = $user->getEmail();
	// Subject of the Email
	$subject = 'Collaborative Request';
	// The body message of the Email
	$message = ''. $user->getUsername() . ' would like to be a collaborator on your ';
	// Get the project leader Id that the current user is viewing
	$projectLeaderID = $_GET['ownerId'];
	//$_POST['accountId']
	// Get the project ID that the current user is viewing
	$projectID = $_GET['projectId'];
	// Hold the project leader account
	$account;

	// Check if the projectLeaderId is set
	if(isset($projectLeaderID)){
		// Create the leader account
		$account = new SimpleAccountModel($projectLeaderID);
		// Get the leader Email
		$to = $account->getEmailAddress();
	}
	// Check if the project id is set
	if(isset($projectID)){
		// Create the prject
		$project = new ProjectModel($projectID);
		// Complete the message body.
		$message = $message . '' . $project->getProjectTitle() . ' project.';
	}

	// If all information for the Email is set
	if(isset($to) && isset($from) && isset($subject) && isset($message) ){
		// Create an email instance
		$sendEmail =  new EmailModel($to, $subject, $message, $from);
		// Check if Email is created corrctly
		if(isset($sendEmail)){
			// Send the Email.
			//$sendEmail->sendEmail();
			addCollaborationRequest($user, $projectID);
			echo json_encode(1);
			$isSent = TRUE;
		}
	}
	
	
}
if(!$isSent){
		echo json_encode(0);
}
// Add the request to the DB and make it Pending status
function addCollaborationRequest($user, $projId){
	
	$newProjectPrivileges = ORM::for_table('project_privileges')->create();
	$newProjectPrivileges->moderate_discussions_permission = 0;
	$newProjectPrivileges->edit_project_content_permission = 0;
	$newProjectPrivileges->control_project_stages_permission = 0;
	$newProjectPrivileges->delete_users_from_project_permission = 0;
	$newProjectPrivileges->collaborator_permission = 0;// Means a pending status.
	$newProjectPrivileges->save();

	
	$newProjectHasAccount = ORM::for_table('project_has_account')->create();
	$newProjectHasAccount->Project_id = $projId;
	$newProjectHasAccount->Account_id = $user->getAccountId();
	$newProjectHasAccount->Account_Title_ID = 2;// Means a collaborator
	$newProjectHasAccount->Project_Privileges_id = $newProjectPrivileges->id;
	$newProjectHasAccount->save();
	
	
	
}
?>