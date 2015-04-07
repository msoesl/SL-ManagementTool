<?php
require_once ('../views/import/headimportviews.php');
$id = $_GET ['id'];
$skillName = $_GET ['skillName'];
session_start ();
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
	$skill = ORM::for_table('skills')->where('skill_name', $skillName)->find_one();
	if (!$skill) {
		$skill = ORM::for_table('skills')->create();
		$skill->skill_name = strip_tags($skillName);
		$skill->save();
	}
	$accSkills = ORM::for_table('account_has_skills')->create();
	$accSkills->Account_id = $id;
	$accSkills->Skills_id = $skill->id;
	$accSkills->save();
	echo json_encode(array('status'=>'Success'));
} else {
	echo json_encode(array('status'=>'Failure'));
}