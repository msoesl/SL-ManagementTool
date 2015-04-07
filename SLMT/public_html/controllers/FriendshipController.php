<?php
require_once ('../views/import/headimportviews.php');
$user;
session_start ();
if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
}

$action = $_POST['action'];

if($action == 'requestAdd'){
	$friendRequestee = $_POST['uid'];
	$friendRequest = ORM::for_table('friend_request')->create();
	$friendRequest->friend_requestee = $friendRequestee;
	$friendRequest->friend_requestor = $user->getAccountId();
	$friendRequest->new = 1;
	$friendRequest->save();
	echo json_encode(array('status'=>'Success'));
}
else if($action == 'remove'){
	$friendToDelete = $_POST['uid'];
	$friendship = $user->hasFriend($friendToDelete);
	if($friendship){
		$friendship->delete();
	}
	echo json_encode(array('status'=>'Success'));
}
else if($action == 'markNotificationsAsOld'){
	//Mark all notifications as old
	$friendRequests = ORM::for_table('friend_request')->where('friend_requestee', $user->getAccountId())->find_many();
	foreach($friendRequests as $request){
		$request->new = 0;
		$request->save();
	}
	if(isset($_POST['inviteIds'])){
		$inviteIds = $_POST['inviteIds'];
		foreach($inviteIds as $inviteId){
			$invite = ORM::for_table('invite_permission')->where('id', $inviteId)->find_one();
			if($invite){
				$invite->new = 0;
				$invite->save();
			}
		}
	}

	//Return the total number of notifications
	$totalFriendRequests = count($user->getFriendRequests());
	$totalCollaborationInvites = count(ProjectModel::getAllCollaborationInvitesForAccount($user->getAccountId()));
	$totalNotifications = $totalFriendRequests + $totalCollaborationInvites;
	
	echo json_encode(array('status'=>'Success','notifications'=>$totalNotifications));
}
else if($action == 'confirmAdd'){
	//add friend
	$friendship = ORM::for_table('friends')->create();
	$friendship->Account_id1 = $user->getAccountId();
	$friendship->Account_id2 = $_POST['uid'];
	$friendship->save();
	
	//remove friend from friend requests
	$friendRequest = $user->getFriendRequestFromUser($_POST['uid']);
	if($friendRequest != false){
		$friendRequest->delete();
	}
	echo json_encode(array('status'=>'Success'));
}
else if($action == 'ignoreAdd'){
	//remove friend from friend requests
	$friendRequest = $user->getFriendRequestFromUser($_POST['uid']);
	if($friendRequest != false){		
		$friendRequest->delete();
	}
	echo json_encode(array('status'=>'Success'));
}

