<?php
require_once ('../views/import/headimportviews.php');
$id = $_POST['uid'];

session_start ();

$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

if (isset($user) && !$user->hasReportedAccount($id)) {
	$acc = ORM::for_table('account')->find_one($id);
	$acc->reported = $acc->reported + 1;
	$acc->save();
	$report = ORM::for_table('reported_by')->create();
	$report->reporter_id = $user->getAccountId();
	$report->reported_id = $id;
	$report->save();
	echo json_encode(array('success'=>'User was reported successfully.'));
} else {
	echo json_encode(array('failure'=>'There was an issue reporting the account, please make sure you are logged in or you have not reported this user already.'));
}