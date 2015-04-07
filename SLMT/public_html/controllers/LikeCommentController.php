<?php
require_once('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$likedCommentId = $_POST['id'];
$liked = ORM::for_table('solution_comment')->find_one($likedCommentId);
$liked->upvotes = $liked->upvotes + 1;
$likedBy = $liked->liked_by;
$likedBy .= $user->getAccountId() . ',';
$liked->liked_by = $likedBy;
$liked->save();