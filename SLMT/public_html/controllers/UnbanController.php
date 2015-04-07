<?php
require_once ('../views/import/headimportviews.php');
$id = $_POST['uid'];

session_start ();

$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$sysPrivs = $user->getSystemPrivileges();
if ($sysPrivs->ban_users_permission) {
	$acc = ORM::for_table('account')->find_one($id);
	$acc->is_banned = 0;
	$acc->save();
	$reason = 'We apologize for the suspension. You account has been activated. <br> Sincerely <br> SMT';
	$email = new EmailModel($acc->email_address,"Account Acctivation",$reason,"no-reply@smt.com");
	try {
		$email->sendEmail();// send the email
	} catch (Warning $w) {

	}
	echo json_encode(array('success'=>'User account was activated successfully after suspension.'));
} else {
	echo json_encode(array('failure'=>'You do not have permission to activate a suspended account.'));
}