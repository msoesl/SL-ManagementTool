<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];

$currentPass = $_POST['currentPassword'];
$newPass = $_POST['newPassword'];
$confirmNewPass = $_POST['confirmNewPassword'];

function validate($userP, $currentPassword, $newPassword, $confirmNewPassword){
	$testPass = true;

	try {
		$testAccount = new AccountModel($userP->getUsername(), $currentPassword);
	}catch(LoginException $e){
		$testPass = false;
	}

	return $newPassword == $confirmNewPassword && $testPass;
}


if(validate($user, $currentPass, $newPass, $confirmNewPass)){	
	$userAccount = ORM::for_table('account')
		->where('id', $user->getAccountId())
		->find_one();

	$userAccount->password = hash('sha256',strip_tags($newPass));
	$userAccount->save();
	echo json_encode(array('status'=>'pass'));
}else{
	echo json_encode(array('status'=>'fail'));
}
