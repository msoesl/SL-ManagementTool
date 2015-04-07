<?php
require_once('../views/import/headimportviews.php');
try { 
	$pid = $_GET['pid'];
	$allAccounts = ProjectModel::getAllAccountsForProject ( $pid );
	foreach ($allAccounts as $account) {
		$aid = $account->Account_id;
		$privId = $account->Project_Privileges_id;
		$priv = ORM::for_table('project_privileges')->find_one($privId);
		if (isset($_POST['moderate-permission'.$aid])) {
			$priv->moderate_discussions_permission = '1';
		} else {
			$priv->moderate_discussions_permission = '0';
		}
		if (isset($_POST['edit-permission'.$aid])) {
			$priv->edit_project_content_permission = '1';
		} else {
			$priv->edit_project_content_permission = '0';
		}
		if (isset($_POST['manage-stages-permission'.$aid])) {
			$priv->control_project_stages_permission = '1';
		} else {
			$priv->control_project_stages_permission = '0';
		}
		if (isset($_POST['user-permission'.$aid])) {
			$priv->collaborator_permission = '1';
		} else {
			$priv->collaborator_permission = '0';
		}
		if (isset($_POST['add-delete-permission'.$aid])) {
			$priv->delete_users_from_project_permission = '1';
		} else {
			$priv->delete_users_from_project_permission = '0';
		}
		$priv->save();
	}
	echo json_encode(array('status'=>'Success'));
} catch (Exception $e) {
	$message = $e->getMessage();
	echo json_encode(array('status'=>'Failed', 'message'=>$message));
}