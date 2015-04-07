<?php
require_once('../views/import/headimportviews.php');
$username = $_POST['user'];
$password = $_POST['pass'];
session_start();
try {
	$_SESSION['user'] = new AccountModel($username,$password);

	if(!$_SESSION['user']->isActivated()){
                echo json_encode(array('status'=>'Locked', 'id'=>$_SESSION['user']->getAccountId()));

                //send out email
                $emailAddress = $_SESSION['user']->getEmail();
                $emailMessage = 'Here\'s your activation code: '.$_SESSION['user']->lockedCode().' <br><br> Copy this and paste it <a href="www.msoeslmt.com/#AccountUnlockView.php?accountid='.$_SESSION['user']->getAccountId().'">here</a> to get started!';
                $email = new EmailModel(
                        $emailAddress, 
                        'SMT Notification: Thanks for signing up!  Here\'s your activation code!', 
                        $emailMessage, 
                        'no-reply@slmt.com');
                $email->sendEmail();

                session_destroy();
	}else{
		if (!$_SESSION['user']->isBanned()) {
			echo json_encode(array('status'=>'Success', 'username'=>$_SESSION['user']->getUsername()));
		} else {

			session_destroy();
			if($_SESSION['user']->isDefended()>0){
				
				echo json_encode(array('status'=>'Banned'));

			}else{
					$id =$_SESSION['user']->getAccountId();
					if(isset($id)){
						echo json_encode(array('status'=>$id));
					}else{
						echo json_encode(array('status'=>'Failed'));
					}
					
				
			}


		}
	}

}catch (Exception $le) {
	echo json_encode(array('status'=>'Failed'));
} 


