<?php
require_once('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$likedCommentId = $_POST['id'];
$liked = ORM::for_table('thread_comment')->find_one($likedCommentId);


$likedBy = $liked->liked_by;
$liked->likes = $liked->likes + 1;
$likedBy .= $user->getAccountId() . ',';
$liked->liked_by = $likedBy;
$liked->save();