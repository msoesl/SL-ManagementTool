<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];

$banner = ORM::for_table('banner')->find_one($_GET['bid']);
$banner->delete();
header( 'Location: ../#BannerManagementView.php');