<?php
/*
session_start();
try{
$user = $_SESSION['user'];
} catch (Exception $e){
	
}
*/
require_once('../libs/idiorm/idiorm.php');
require_once('../models/Config.php');
require_once('../models/AccountModel.php');
require_once('../models/SimpleAccountModel.php');
require_once('../models/ProjectModel.php');
require_once('../models/Stage1Model.php');
require_once('../models/SkillsModel.php');
require_once('../models/SystemPrivilegesModel.php');
require_once('../models/PictureModel.php');
require_once('../models/EmailModel.php');
require_once('../models/ForumModel.php');
require_once('../models/ProjectPrivilegesModel.php');