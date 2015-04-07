<?php
require_once ('../views/import/headimportviews.php');


$email = $_POST['email'];
$reason = $_POST['reasoning'];

$reason .= "<br><br><br>You have one chance to defend yourself. To defend yourself, go and sign in.<br> 
				When signed in, you will see a form that you have to fill.<br>If your reasoning is accepted, an email will be sent to your registered email.";

if(isset($email) && isset($reason)){
	
	$email = new EmailModel($email,"Your account has been suspended",$reason,"no-reply@smt.com");
	try {
		$email->sendEmail();
	} catch (Warning $w) {
			
	}
}

header( 'Location: ../#UserBanView.php') ;




