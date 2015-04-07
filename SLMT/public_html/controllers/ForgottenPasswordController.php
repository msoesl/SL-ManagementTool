<?php
require_once ('../views/import/headimportviews.php');
error_reporting(E_ERROR | E_PARSE);

if(isset($_POST["formStep"])){
	if($_POST["formStep"] == "requestSecurityQuestion"){
		if(isset($_POST["username"])){
			$username = $_POST['username'];
			$account = AccountModel::getORMAccountByUsername($username);
			if($account != false){
				//header( 'Location: ../#ForgottenPasswordView.php?id='.$account->id);
				$array = array('id' => $account->id, 'question' => $account->security_question);
				echo json_encode($array);
			}
			else{
				echo json_encode(array('id' => 0));
			}
		}
	}
	else if($_POST["formStep"] == "requestNewPassword"){
		if(isset($_POST["userId"]) && isset($_POST["securityQuestionResponse"])){
			$response = $_POST["securityQuestionResponse"];
			$account = ORM::for_table('account')
							->where('id', $_POST["userId"])
							->find_one();
			$actualResponse = $account->security_answer;
			if($response == $actualResponse){
				$password = generateRandomPassword();
				$account->password = hash('sha256',strip_tags($password));
				$account->save();
				$recipientEmailAddress = $account->email_address;
				//EMAIL IS NOT FUNCTIONING AT THE MOMENT
				$email = new EmailModel($recipientEmailAddress,
						"New Password",
						"Your password has been changed to ".$password,
						"no-reply@smt.com");
				try {
				$email->sendEmail();
				} catch (Warning $w) {
					
				}
				echo json_encode(array('email'=>$recipientEmailAddress));
			}
			else{
				echo json_encode(array('email' => 'error'));				
			}
		}
	}
}

function generateRandomPassword(){
	$characters = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$password = '';
	for($i = 0; $i < 10; $i++){
		$password .= $characters[rand(0, strlen($characters)-1)];
	}
	return $password;
}