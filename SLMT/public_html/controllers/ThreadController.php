<?php
require_once ('../views/import/headimportviews.php');
$message = $_GET ['message'];
$id = $_GET ['id'];
$pid = $_GET['project_id'];
session_start ();

$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$perms = false;
if (isset($user)) {
	$perms = ProjectModel::getAccountPermissionsForProjectWithId($user->getAccountId(), $pid);
}
switch ($message) {
	case 'get' :
		$retVal = array ();
		$comments = ORM::for_table ( "thread_comment" )->where ( 'thread_id', $id )->find_many ();
		
		foreach ( $comments as $comment ) {
			$account = new SimpleAccountModel ( $comment->author_id );
			$likedBy = $comment->liked_by;
			$flaggedBy = $comment->flagged_by;
			$allLikes = explode(',', $likedBy);
			$allFlags = explode(',', $flaggedBy);
			
			$comment = array (
					'Id' => $comment->id,
					'Comment' => htmlspecialchars($comment->comment),
					'Author' => htmlspecialchars($account->getUsername ()),
					'ParentId' => $comment->parent_comment_id,
					'displayAvatar' => true,
					'UserAvatar' => $account->getProfilePicUrl (),
					'CanDelete' => (((isset ( $user ) && $user->getAccountId () == $comment->author_id) || ($perms && $perms->moderate_discussions_permission > 0)) ? true : false),
					'CanReply' => isset ( $user ),
					'Date' => $comment->time,
					'likes' => $comment->likes,
					'flags' => $comment->inappropriate,
					'canFlag' => (!isset($allFlags)||(isset($allFlags) && isset($user) && !in_array($user->getAccountId(), $allFlags)))?true:false,
					'canLike' => (!isset($allFlags)||(isset($allLikes) && isset($user) && !in_array($user->getAccountId(), $allLikes)))?true:false
			);
			array_push ( $retVal, $comment );
		}
		
		echo json_encode ( $retVal );
		break;
	case 'delete' :
		if (isset ( $_POST ['commentId'] )) {
			$comment = ORM::for_table ( 'thread_comment' )->where_equal ( 'id', $_POST ['commentId'] )->delete_many ();
			echo $_POST ['commentId'];
		}
		break;
	case 'post' :
		// TODO get logged in user, get parent comment, and verify that they have the permissions to do things
		if (isset($user) && isset ( $_POST ['comment'] )) {
			$value = $_POST ['comment'];
			
			$comment = ORM::for_table ( "thread_comment" )->create ();
			$comment->thread_id = $id;
			$comment->comment = strip_tags($value);
			$comment->author_id = $user->getAccountId ();
			$comment->time = date ( "Y-m-d H:i:s" );
			if (isset ( $_POST ['parentId'] )) {
				$comment->parent_comment_id = $_POST ['parentId'];
			}
			$comment->save();
			
			// package comment up
			$account = new SimpleAccountModel ( $comment->author_id );
			$retVal = array (
					'Id' => $comment->id,
					'Comment' => strip_tags($comment->comment),
					'Author' => strip_tags($account->getUsername()),
					'ParentId' => $comment->parent_comment_id,
					'UserAvatar' => strip_tags($account->getProfilePicUrl()),
					'CanDelete' => (((isset ( $user ) && $user->getAccountId () == $comment->author_id) || ($perms && $perms->moderate_discussions_permission > 0))? true : false),
					'CanReply' => isset ( $user ),
					'Date' => date ( "Y-m-d H:i:s" ),
					'likes'=>0,
					'flags'=>0,
					'canFlag'=>true,
					'canLike'=>true
			);
			echo json_encode ( $retVal );

			//send out emails to all who subscribe to this thread
			//get the subscribers' ids
			$subscribers = ORM::for_table("account_subscribes_to_thread")->where('thread_id', $id)->find_many();
			//get the title of the thread for the email
			$threadTitle = ORM::for_table("thread")->select("thread.title")->where('id', $id)->find_one()->title;
			//if we have any subscribers, for each subscriber
			if($subscribers){
				foreach($subscribers as $subscriber){
					//get the subscriber's email address
					$emailAddress = ORM::for_table('account')->select('account.email_address')->where('id', $subscriber->account_id)->find_one()->email_address;
					$userName = $user->getUsername();

					//if they had an email and they're not the one who posted this comment, send an email to 'em!
					if($emailAddress && $subscriber->account_id != $user->getAccountId()){
						$emailMessage = "A thread you're subscribed to recieved a comment. <br><br>";
						$emailMessage .= "The thread title is ".htmlspecialchars($threadTitle).".<br>";
						$emailMessage .= "The new comment is \"".htmlspecialchars($comment->comment)."\"<br>";
						$emailMessage .= "Posted By ".htmlspecialchars($userName)."";

						$email = new EmailModel(
							$emailAddress, 
							'SMT Notification: A Thread you\'ve subscribed to recieved a comment.', 
							$emailMessage, 
							'no-reply@smt.com');
						$email->sendEmail();
					}
				}
			}
		}

		break;
	case 'sticky':
		$thread = ORM::for_table('thread')->where('id', $id)->find_one();
		if($thread->stickied == 1){
			$thread->stickied = 0;
			echo json_encode(0);
		}else{
			$thread->stickied = 1;
			echo json_encode(1);
		}
		$thread->save();
		
		break;
	case 'disable':
		$thread = ORM::for_table('thread')->where('id', $id)->find_one();
		if($thread->disabled == 1){
			$thread->disabled = 0;
			$thread->save();
		}else{
			$thread->disabled = 1;
			$thread->save();
		}

		echo json_encode($thread->disabled);
		
		break;
	case 'subscribe':
		echo json_encode(ForumModel::subscribeUserToThread($user->getAccountId(), $id));	
		break;
	case 'isSubscribed':
		echo json_encode(ForumModel::isUserSubscribedToThread($user->getAccountId(), $id));
		break;
}