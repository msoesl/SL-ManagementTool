<?php
require_once ('../views/import/headimportviews.php');
session_start ();
if (isset ( $_SESSION ['user'] )) {
	$userId = $_SESSION ['user']->getAccountId ();
	$problem_title = strip_tags($_POST ['problem_title']);
	$problem_description = strip_tags($_POST ['problem_description']);
	
	$newProject = ORM::for_table ( 'project' )->create ();
	$newProject->problem_title = strip_tags($problem_title);
	
	$stageOne = ORM::for_table ( 'stage_1' )->create ();
	$stageOne->problem_summary = strip_tags($problem_description);
	$stageOne->save ();
	
	$stageTwo = ORM::for_table ( 'stage_2' )->create ();
	$stageTwo->save ();
	
	$stageThree = ORM::for_table ( 'stage_3' )->create ();
	$stageThree->save ();
	
	$newProject->stage_1_id = $stageOne->id;
	$newProject->stage_2_id = $stageTwo->id;
	$newProject->stage_3_id = $stageThree->id;
	$newProject->project_lead_id = $userId;
	$newProject->is_private = 0;
	$newProject->save ();
	
	$permissions = ORM::for_table('project_privileges')->create();
	$permissions->moderate_discussions_permission = 1;
	$permissions->edit_project_content_permission = 1;
	$permissions->control_project_stages_permission = 1;
	$permissions->delete_users_from_project_permission = 1;
	$permissions->collaborator_permission = 1;
	$permissions->save();
	
	$projectHasAccount = ORM::for_table('project_has_account')->create();
	$projectHasAccount->Project_id = $newProject->id;
	$projectHasAccount->Account_id = $userId;
	$projectHasAccount->Account_Title_ID = 1;
	$projectHasAccount->Project_Privileges_id = $permissions->id;
	$projectHasAccount->save();
	
	header( 'Location: ../#MessageView.php?messageType=problem_submission_confirm');
}

?>
