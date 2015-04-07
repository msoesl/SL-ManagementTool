<?php
require_once('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$unlikedId = $_POST['id'];
$unliked = ORM::for_table('solution_comment')->find_one($unlikedId);
$unliked->upvodes = $unliked->upvotes - 1;
$likedBy = $unliked->liked_by;
str_replace($user->getAccountId().',','',$likedBy);
$unliked->liked_by = $likedBy;
$unliked->save();