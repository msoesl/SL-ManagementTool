<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];

if(isset($_GET['bid'])){
	$banner = ORM::for_table('project_banner')->find_one($_GET['bid']);
	$pid = $banner->project_id;
	$banner->delete();
}

header( 'Location: ../#ProjectBannerManagementView.php?id='.$pid);