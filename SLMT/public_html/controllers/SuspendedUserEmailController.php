<?php
require_once ('../views/import/headimportviews.php');


$userid = $_POST['userid'];
$reason = $_POST['reasoning'];
$message = '2';

if(!isset($reason) || strlen($reason)<1){// check if the user has typed a message, if not don't send a message
	
	$message = '1';
	// return the reuslt with the user id, so it can be used for another submission
	header( 'Location: ../#SuspendedUserEmailView.php?message='.$message.'&userid='.$userid) ;
	
}else if (isset($userid) && $userid!= -1){// if the user typed a message, 

	$account = AccountModel::getAccountById($userid);

	if(isset($account)){// is it set?
		$email = new EmailModel("smtadm1@gmail.com","Suspension Defens",$reason,$account->email_address);
		try {
			$email->sendEmail();// send the email
			// set defened to one, so the user will not be able to send another email again.
			$userAccount =  ORM::for_table('account')->find_one($userid);
			$userAccount->defend_suspension = 1;
			$userAccount->save();
			$message = '3';
			header( 'Location: ../#SuspendedUserEmailView.php?message='.$message) ;
		} catch (Warning $w) {
				
			$message = 'Email was not sent. Try later please.';
			$message = '2';
				// return the reuslt with the user id, so it can be used for another submission
			header( 'Location: ../#SuspendedUserEmailView.php?message='.$message.'&userid='.$userid) ;
		}
		
	}else{
		$message = '2';
		// return the reuslt with the user id, so it can be used for another submission
		header( 'Location: ../#SuspendedUserEmailView.php?message='.$message.'&userid='.$userid) ;
	}
}else{
	$message = '2';
	// return the reuslt.
	header( 'Location: ../#SuspendedUserEmailView.php?message='.$message) ;
}