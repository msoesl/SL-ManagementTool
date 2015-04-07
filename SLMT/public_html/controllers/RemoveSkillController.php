<?php
require_once('../views/import/headimportviews.php');
$id = $_GET['id'];
$skillId = $_GET['skillid'];
session_start();
if (isset($_SESSION['user'])) {
	$user = $_SESSION ['user'];
	if ($user->getAccountId() == $id) {
		$accountSkill = ORM::for_table('account_has_skills')
			->select('id')
			->where('Account_id', $id)
			->where('Skills_id', $skillId)
			->find_one();
		$endorsements = ORM::for_table('endorsement')->where('Account_has_Skills_id', $accountSkill->id);
		if ($endorsements) {
			$endorsements->delete_many();
		}
		$accountSkill->delete();
		echo json_encode(array('status'=>'Success'));
	} else {
		echo json_encode(array('status'=>'Failure'));
	}
} else {
		echo json_encode(array('Status'=>'Failure'));
}