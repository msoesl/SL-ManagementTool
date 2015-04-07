<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];

$actionType = null;

if(isset($_POST)){
	$actionType = $_POST['actionType'];
}

$hasError = false;



if(isset($actionType)){
	
	
	if($actionType == 'removeCollaborator'){// Remove a user collaborators list
		// of a specific project
		if(isset($_POST['projectId']) && isset($_POST['userId']) ){
			$collaboratorToBeRemoved = $_POST['userId'];
			$projectToBeRemoveFrom = $_POST['projectId'];
			remvoeCollaborator($collaboratorToBeRemoved,$projectToBeRemoveFrom);
		}



	}else if($actionType == 'acceptRequest'){// another action

		$userId = $_POST['userId'];
		$projectId = $_POST['projectId'];

		if(isset($userId) && isset($projectId)){// Only change the collaboration permission to 1

			$record =  ORM::for_table('project_has_account')->where('Project_id', $projectId)
			->where('Account_id', $userId)->find_one();
				
			if($record != false && isset($record)){// make sure that query is not returning a false
				echo ' the id is ' . $record->Project_Privileges_id;
				$newPrivilege = ORM::for_table('project_privileges')->find_one($record->Project_Privileges_id);

				$newPrivilege->collaborator_permission = 1;
				$newPrivilege->save();
				$hasError = false;

			}


		}

	}else if($actionType == 'rejectRequest'){
		$userId = $_POST['userId'];
		$projectId = $_POST['projectId'];

		// removing a request is the same as removing a collaborator.
		remvoeCollaborator($userId,$projectId );
	


	}else if($actionType == 'unactviate'){
		$userId = $_POST['userId'];
		$projectId = $_POST['projectId'];
		// unactivate a request is the same as removing a collaborator.
		remvoeCollaborator($userId,$projectId );
				
	} else if($actionType == 'flipRecieveEmails') {
		$userId = $_POST['userId'];
		
		if(isset($userId)){
			$userAccount =  ORM::for_table('account')->where('id', $userId)->find_one();
		
			if($userAccount){
				if($userAccount->recieve_emails > 0){
					$userAccount->recieve_emails = 0;
				} else {
					$userAccount->recieve_emails = 1;
				}
				$userAccount->save();
			}
		}
	} else if($actionType == 'flipIsPrivate'){
		$userId = $_POST['userId'];
		
		if(isset($userId)){
			$userAccount =  ORM::for_table('profile')
				->where('a.id', $userId)
				->inner_join('Account','Profile.id = a.Profile_id','a')
				->find_one();
		
			if($userAccount){
				if($userAccount->is_private > 0){
					$userAccount->set('is_private',0);
				} else {
					$userAccount->set('is_private',1);
				}
				$userAccount->save();
			}
		}
	}
}

// Rmove a collaborator from the user's project
function remvoeCollaborator($collaborator, $projectId){

	if(isset($collaborator)&& isset($projectId)	&& !empty($collaborator) && !empty($projectId)){


		$record =  ORM::for_table('project_has_account')->where('Project_id', $projectId)
		->where('Account_id', $collaborator)->find_one();

		

		if($record != false){
			// Get the privileges id to be deleted
			$privilegeId =	$record->Project_Privileges_id;
			// Delete it
			$record->delete();
			// Remove the project privileges since it is no more needed
			$privilege = ORM::for_table('project_privileges')->where('id', $privilegeId)->find_one()->delete();
			$hasError = false;
			
		}
	}
}

$user->refreshProfile();
if($hasError){
	// reload the page if it fails
	header( 'Location: ../#AccountSettingsView.php?status=1');// if fail
}
else{
	// reload the page if it fails
	header( 'Location: ../#AccountSettingsView.php?status=0');// success
}

?>