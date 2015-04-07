<?php
require_once ('../views/import/headimportviews.php');

$action = $_POST['faq-action'];

if($action == 'new-faq'){
	$newFaq = ORM::for_table('frequently_asked_questions')->create();
	$newFaq->question = strip_tags($_POST['faq-question']);
 	$newFaq->answer = strip_tags($_POST['faq-answer']);
	$newFaq->save();
	echo "NICE!";
	header( 'Location: ../#HelpPageManagementView.php');
}
else if($action == "modify-faq"){
	$faq = ORM::for_table('frequently_asked_questions')->where('id', $_POST['id'])->find_one();
	$faq->question = strip_tags($_POST['faq-question']);
 	$faq->answer = strip_tags($_POST['faq-answer']);
	$faq->save();
	header( 'Location: ../#HelpPageManagementView.php?id='.$_POST['id']);
}
else if($action == "delete-faq"){
	$faq = ORM::for_table('frequently_asked_questions')->find_one($_POST['id']);
	$faq->delete();
	header( 'Location: ../#HelpPageManagementView.php?id='.$_POST['id']);
}
else if($action == "new-question"){
	//Get the admins email
	$adminEmail = ORM::for_table('account')
		->inner_join('system_privileges', 'sp.id = account.id', 'sp')
		->where('sp.system_admin', 1)
		->find_one()
		->email_address;
	$email = new EmailModel($adminEmail,"A user has asked a question",strip_tags($_POST['user-question']),strip_tags($_POST['user-email']));
	try {
		$email->sendEmail();
	} catch (Warning $w) {
			
	}
	header( 'Location: ../#MessageView.php?messageType=confirm_question_submission');
}
?>