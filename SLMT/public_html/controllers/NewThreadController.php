<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];
$project = null;
if(isset($_GET['project_id'])){
	$project = new ProjectModel ($_GET['project_id'] );
}

//TODO: Ensure they are collaborator
if(isset($project) && isset($user)){

	if(isset($_POST['new_thread']) && isset($_POST['new_comment'])){
		ForumModel::createThread($project->getProjectId(), $_POST['new_thread'], $_POST['new_comment'], $user->getAccountId());

		//send out emails to all who subscribe to this forum
		//get the subscribers' ids
		$subscribers = ORM::for_table("account_subscribes_to_forum")->where('forum_id', $project->getProjectId())->find_many();
		//get the title of the thread for the email
		$projectTitle = $project->getProjectTitle();
		//if we have any subscribers, for each subscriber
		if($subscribers){
			foreach($subscribers as $subscriber){
				//get the subscriber's email address
				$emailAddress = ORM::for_table('account')->select('account.email_address')->where('id', $subscriber->account_id)->find_one()->email_address;
				$userName = $user->getUsername();

				//if they had an email and they're not the one who posted this comment, send an email to 'em!
				if($emailAddress && $subscriber->account_id != $user->getAccountId()){
					$emailMessage = "A new thread was posted in a forum you subscribe to. <br><br>";
					$emailMessage .= "The forum's project title is ".$projectTitle.".<br>";
					$emailMessage .= "The new thread's title is \"".$_POST['new_thread']."\"<br>";
					$emailMessage .= "with the comment \"".$_POST['new_comment']."\"<br>";
					$emailMessage .= "Posted By ".$userName."";

					$email = new EmailModel(
						$emailAddress, 
						'SMT Notification: A New Thread was posted in a forum you subscribe to.', 
						$emailMessage, 
						'no-reply@smt.com');
					$email->sendEmail();
				}
			}
		}
	}
}

?>