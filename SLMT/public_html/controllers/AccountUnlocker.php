<?php
require_once('../views/import/headimportviews.php');

$accountId = $_GET['id'];
$code = $_POST['code'];
$account = ORM::for_table('Account')->find_one($accountId);

if($code === $account->locked_code){
	$account->locked_code = -1;
	$account->save();

	header('Location: ../#AccountUnlockView.php?accountid='.$accountId);
}else{
	header('Location: ../#AccountUnlockView.php?accountid='.$accountId.'&error=1') ;
}
