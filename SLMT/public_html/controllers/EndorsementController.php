<?php
require_once ('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}



if (isset($user)) {
	$skill = $_POST['skill'];
	$type = $_POST['type'];
	if ($type == 'remove') {
		$endorsement = ORM::for_table('endorsement')->where('Account_has_Skills_id', $skill)->where('Account_id', $user->getAccountId())->find_one();
		$endorsement->delete();
	} else if ($type == 'add'){
		$new = ORM::for_table('endorsement')->create();
		$new->Account_has_Skills_id = $skill;
		$new->Account_id = $user->getAccountId();
		$new->save();
	}
}