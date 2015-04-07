<?php
require_once('../views/import/headimportviews.php');
session_start();
$event = null;
if(isset($_GET['id']) && isset($_SESSION['user'])){
	$user = $_SESSION ['user'];
	$event = ORM::for_table ( "event_has_account" )->where('event_id', $_GET['id'])->where('account_id', $user->getAccountId())->find_one();
	$event->delete();
}
?>