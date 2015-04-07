<?php
require_once('../views/import/headimportviews.php');

session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
$inappropriateCommentId = $_POST['id'];
$inappropriate = ORM::for_table('solution_comment')->find_one($inappropriateCommentId);
$inappropriate->inappropriate = $inappropriate->inappropriate + 1;
$flaggedBy = $inappropriate->flagged_by;
$flaggedBy .= $user->getAccountId() . ',';
$inappropriate->flagged_by = $flaggedBy;
$inappropriate->save();

$proj = ORM::for_table('project')->find_one($inappropriate->project_id);
$leadAccount = new SimpleAccountModel($proj->project_lead_id);
$emailMessage = 'Hello there, A comment on your project, ' . $proj->problem_title .' has been flagged as being inappropriate.';
$emailMessage .= 'The following message "'.$inappropriate->comment .'" has been flagged as being inappropriate ' . $inappropriate->inappropriate . ' times.';
$emailMessage .= 'Please take corrective action as soon as possible if neccessary. Thank you.';
$email = new EmailModel(
		$leadAccount->getEmailAddress(), 
		'SMT Notification: A comment was flagged as inappropriate in one of your projects.', 
		$emailMessage, 
		'no-reply@smt.com');
var_dump($email);
$email->sendEmail();