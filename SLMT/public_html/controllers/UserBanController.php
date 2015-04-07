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
	$acc->is_banned = 1;
	$acc->save();
	echo json_encode(array('success'=>'User was suspended successfully.'));
} else {
	echo json_encode(array('failure'=>'You do not have permission to suspended users.'));
}
