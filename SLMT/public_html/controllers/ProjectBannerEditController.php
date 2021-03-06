<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];
try {
$allowedExts = array (
		"gif",
		"jpeg",
		"jpg",
		"png"
);
if (isset($_POST['id'])) {
	$banner = ORM::for_table('project_banner')->find_one($_POST['id']);
}

$pid = $banner->project_id;

$temp = explode ( ".", $_FILES ["banner-pic"] ["name"] );
$extension = strtolower(end ( $temp ));
$errorMessage = '';
// maximum file size of 5 Mb
if ((($_FILES ["banner-pic"] ["type"] == "image/gif")
		|| ($_FILES ["banner-pic"] ["type"] == "image/jpeg")
		|| ($_FILES ["banner-pic"] ["type"] == "image/jpg")
		|| ($_FILES ["banner-pic"] ["type"] == "image/pjpeg")
		|| ($_FILES ["banner-pic"] ["type"] == "image/x-png")
		|| ($_FILES ["banner-pic"] ["type"] == "image/png"))
		&& ($_FILES ["banner-pic"] ["size"] < 5000000) && in_array ( $extension, $allowedExts )) {
	if ($_FILES ["banner-pic"] ["error"] > 0) {
		$errorMessage = $_FILES ["banner-pic"] ["error"];
	} else {

		if(file_exists("../".$user->getProfilePicUrl())){
			unlink('../'.$banner->src);
		}

		if(!file_exists("../projectfiles/".$pid)){
			mkdir("../projectfiles/".$pid, 0777, true);
		}
		
		move_uploaded_file ( $_FILES ["banner-pic"] ["tmp_name"], "../projectfiles/".$pid."/". $_FILES["banner-pic"] ["name"]);
		$banner->src = "projectfiles/".$pid."/". $_FILES["banner-pic"] ["name"];
		$banner->save();
	}
}

if (isset($_POST['title'])) {
	$title = $_POST['title'];
	$banner->title = $title;
} else {
	$banner->title = '';
}
if (isset($_POST['url'])) {
$url = $_POST['url'];
$banner->url = $url;
} else {
	$banner->url = '';
}
if (isset($_POST['alttext'])) {
$alt = $_POST['alttext'];
$banner->alt_text = $alt;
}else {
$banner->alt_text = '';
}
if (isset($_POST['sort_order'])) {
$sort = $_POST['sort_order'];
$banner->sort_order = $sort;
} else {
	$banner->sort_order = 1000;
}
if (isset($_POST['isenabled'])) {
$enabled = $_POST['isenabled'];
$banner->enabled = $enabled;
} else {
	$banner->enabled = 0;
}
$banner->save();
}catch (Exception $e) {}
header( 'Location: ../#ProjectBannerManagementView.php?id='.$pid);