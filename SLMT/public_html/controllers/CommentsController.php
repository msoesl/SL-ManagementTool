<?php
require_once ('../views/import/headimportviews.php');
$message = $_GET ['message'];
$id = $_GET ['id'];

session_start ();

$user;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$perms = false;
if (isset($user)) {
	$perms = ProjectModel::getAccountPermissionsForProjectWithId($user->getAccountId(), $id);
}
switch ($message) {
	case 'get' :
		$retVal = array ();
		$comments = ORM::for_table ( "solution_comment" )->where ( 'project_id', $id )->find_many ();
		foreach ( $comments as $comment ) {
			$account = new SimpleAccountModel ( $comment->author_id );
			$likedBy = $comment->liked_by;
			$flaggedBy = $comment->flagged_by;
				$allLikes = explode(',', $likedBy);
				$allFlags = explode(',', $flaggedBy);
			$comment = array (
					'Id' => $comment->id,
					'Comment' => $comment->comment,
					'Author' => $account->getUsername (),
					'ParentId' => $comment->parent_comment_id,
					'displayAvatar' => true,
					'UserAvatar' => $account->getProfilePicUrl (),
					'CanDelete' => (((isset ( $user )&& $user->getAccountId () == $comment->author_id) || ($perms && $perms->moderate_discussions_permission > 0)) ? true : false),
					'CanReply' => isset ( $user ),
					'Date' => $comment->time,
					'likes' => $comment->upvotes,
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
			$comment = ORM::for_table ( 'solution_comment' )->where_equal ( 'id', $_POST ['commentId'] )->delete_many ();
			echo $_POST ['commentId'];
		}
		break;
	case 'post' :
		// TODO get logged in user, get parent comment, and verify that they have the permissions to do things
		if (isset($user) && isset ( $_POST ['comment'] )) {
			$value = $_POST ['comment'];
			
			$comment = ORM::for_table ( "solution_comment" )->create ();
			$comment->project_id = $id;
			$comment->comment = $value;
			$comment->author_id = $user->getAccountId ();
			if (isset ( $_POST ['parentId'] )) {
				$comment->parent_comment_id = $_POST ['parentId'];
				
				//Simply get the account, don't need all the extras
				$parentComment = ORM::for_table('solution_comment')->where('id', $_POST['parentId'])->find_one();
				$parentAccount = ORM::for_table('account')->where('id', $parentComment->author_id)->find_one();
				if($parentComment->author_id != $comment->author_id && $parentAccount->recieve_emails > 0){
					try {
						$email = new EmailModel($parentAccount->email_address,
								"New Comment Reply!",
								"A user replied '".$comment->comment."' to your comment '".$parentComment->comment."'.",
								"no-reply@smt.com");
						$email->sendEmail();
					} catch (Warning $w) {
					
					}
				}
			}
			$comment->save ();
			
			// package comment up
			$account = new SimpleAccountModel ( $comment->author_id );
			$retVal = array (
					'Id' => $comment->id,
					'Comment' => $comment->comment,
					'Author' => $account->getUsername (),
					'ParentId' => $comment->parent_comment_id,
					'UserAvatar' => $account->getProfilePicUrl (),
					'CanDelete' => (((isset ( $user ) && $user->getAccountId () == $comment->author_id) || ($perms && $perms->moderate_discussions_permission > 0)) ? true : false),
					'CanReply' => isset ( $user ),
					'Date' => date ( "Y-m-d H:i:s" ),
					'likes'=>0,
					'flags'=>0,
					'canFlag'=>true,
					'canLike'=>true
			);
			echo json_encode ( $retVal );
		} else {
			// TODO
		}
		break;
}