<?php 

require_once ('../views/import/headimportviews.php');
$user;
session_start ();
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}

$action = $_POST['action'];

if($action == 'acceptCollaborationInvite'){
	$projectPrivilegesId = $_POST['ppid'];
	$projectPrivileges = ORM::for_table('project_privileges')->where('id', $projectPrivilegesId)->find_one();
	$invitationInfo = ORM::for_table('invite_permission')->where('id', $projectPrivileges->invite_permission_id)->find_one();
	$projectPrivileges->collaborator_permission = 1;
	$invitationInfo->invite_status = 1;
	$projectPrivileges->save();
	$invitationInfo->save();
	
	echo json_encode(array('status'=>'AcceptSuccess'));
}
else if($action == 'ignoreCollaborationInvite'){
	$projectPrivilegesId = $_POST['ppid'];
	$projectId = $_POST['projectId'];
	$inviteeId = $_POST['inviteeId'];
	
	//The user no longer has an association with the project
	$projectHasAccount = ORM::for_table('project_has_account')
							->where('Project_id', $projectId)
							->where('Account_id', $inviteeId)
							->find_one();
	$projectHasAccount->delete();
	
	//Since the user is no longer associated with the project, the respective row in Project_Privileges can be deleted
	$projectPrivileges = ORM::for_table('project_privileges')->where('id', $projectPrivilegesId)->find_one();
	$projectPrivileges->delete();
	
	//...Samesies with the invitation
	$invitationInfo = ORM::for_table('invite_permission')->where('id', $projectPrivileges->invite_permission_id)->find_one();
	$invitationInfo->delete();
	
	echo json_encode(array('status'=>'IgnoreSuccess'));
}
?>