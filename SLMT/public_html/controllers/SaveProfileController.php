<?php
	require_once('../views/import/headimportviews.php');
	session_start();
	$user = $_SESSION ['user'];
	
	
	$profile = $user->refreshProfile();
	
	$message=false;
	$message=TRUE;
	if (isset($_POST['firstname'])) {
		$profile->firstname = strip_tags($_POST['firstname']);
		$message=TRUE;
	}
	
	if (isset($_POST['lastname'])) {
		$profile->lastname = strip_tags($_POST['lastname']);
		$message=TRUE;
	}
	if (isset($_POST['city'])) {
		$profile->city = strip_tags($_POST['city']);
		$message=TRUE;
	}
	
	if (isset($_POST['state'])) {
		$profile->state = strip_tags($_POST['state']);
		$message=TRUE;
	}
	
	if (isset($_POST['about_me'])) {
		$profile->about_me = strip_tags($_POST['about_me']);
		$message=TRUE;
	}
	
	if (isset($_POST['email'])){
		$email = strip_tags($_POST['email']);
		
		$account = ORM::for_table('account')
			->find_one($user->getAccountId());
			
		$account->email_address = $email;
		$account->save();
		$message=TRUE;
	}
	
	if (isset($_POST['phone_number'])) {
		$profile->contact_number = strip_tags($_POST['phone_number']);
		$message=TRUE;
	}
	
	if (isset($_POST['gender'])) {
		$profile->gender = strip_tags($_POST['gender']);
		$message=TRUE;
	}
	
	if (isset($_POST['age'])) {
		$profile->age = strip_tags($_POST['age']);
		$message=TRUE;
	}
	
	if($message==TRUE){
		$profile->save();
		header( 'Location: ../#PersonalProfileView.php?id='.$user->getAccountId().'&status=0');// success
		
	}else{
		header( 'Location: ../#PersonalProfileView.php?id='.$user->getAccountId().'&status=1');
	}