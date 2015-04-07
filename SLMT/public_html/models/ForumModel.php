<?php 

/**
 * The Forum Model isn't really a model, but boats a variety of static functions useful
 * for manipulating a project's forum.
 * @author Ryan.Graef
 */
class ForumModel {
	
	/**
	 * returns threads for this project and sets
	 * internal threads variable.  Also sorts the threads so
	 * stickied threads are first, and the rest of the threads are in order
	 * of newest -> oldest
	 * @param $projectId {number} - exactly what it says
	 * @param $rangeStart {number} - the number of threads to offset by
	 * @return threads, an ORM representation of the threads object array.
	 */
	public static function getAllThreads($projectId, $rangeStart){
		$threads = ORM::for_table('thread')
		->select('tc.time', 'mostRecentCommentTime')
		->select('tc_account.username', 'newestCommentUsername')
		->select('thread_account.username', 'username')
		->select('thread.*')
		->inner_join('thread_comment', 'tc.thread_id = thread.id' ,'tc')
		->inner_join('account', 'tc.author_id = tc_account.id', 'tc_account')
		->inner_join('account', 'thread.author_id = thread_account.id', 'thread_account')
		->where('thread.project_id', $projectId)
		->order_by_desc('thread.stickied')
		->order_by_desc('tc.id')
		->limit(25)->offset($rangeStart)->find_many();

        return $threads;
	}

	/**
	 * Creates a thread for this project
	 * @param title {String}: title of the thread
	 * @param content {string}: content of the first post
	 * @param $projectId {number}: id of the project forum to add thread to
	 * @param $author_id {number}: id of the author of this thread
	 * of the thread.
	 * @return null if invalid params, else the new thread.
	 */
	public static function createThread($projectId, $title, $content, $authorId){
		if(!isset($projectId) || !isset($title) || !isset($content) || !isset($authorId)){
			return null;
		}

		//create the new thread
		$newThread = ORM::for_table('thread')->create();
		$newThread->author_id = $authorId;
		$newThread->disabled = 0;
		$newThread->stickied = 0;
		$newThread->project_id = $projectId;
		$newThread->inappropriate = 0;
		$newThread->time = date('Y-m-d H:i:s');
		$newThread->title = strip_tags($title);
		$newThread->save();

		$newComment = ORM::for_table('thread_comment')->create();
		$newComment->author_id = $authorId;
		$newComment->comment = strip_tags($content, "<b><em>");
		$newComment->inappropriate = 0;
		$newComment->thread_id = $newThread->id;
		$newComment->time = date('Y-m-d H:i:s');
		$newComment->upvotes = 0;
		$newComment->save();

		return $newThread;
	}

	/**
	 * Get the number of threads for the projectId passed in.
	 * @param $projectId {number} what you think it is
	 * @return {number} what you think it is
	 */
	public static function getNumThreads($projectId){
		return ORM::for_table('thread')->where('project_id', $projectId)->count('id');
	}

	/**
	 * Check to see if the id of the passed in user is subscribed to the
	 * forum under this project.
	 * @param $userId {number} the id of the account to check
	 * @return true if this user is subscribed to this forum, false
	 * if this user is not subscribed, or doesn't exist.
	 */
	public static function isUserSubscribedToForum($projectId, $userId){
		$isSubscribed = ORM::for_table('account_subscribes_to_forum')->where('account_id', $userId)->where('forum_id', $projectId)->find_one();
	
		if($isSubscribed != false){
			return true;
		}else{
			return false;
		}
	}

	/**
	 * Check to see if the id of the passed in user is subscribed to the thread
	 * represented by the passed in thread id.
	 * @param $userId {number} the id of the account to check
	 * @param $threadId {number} the id of the thread to check
	 * @return true of this user is subscribed to this thread, false
	 * if the user is not subscribed, or if either the user or thread doesn't exist.
	 */
	public static function isUserSubscribedToThread($userId, $threadId){
		$isSubscribed = ORM::for_table('account_subscribes_to_thread')->where('account_id', $userId)->where('thread_id', $threadId)->find_one();

		if($isSubscribed === false){
			return false;
		}else{
			return true;
		}
	}

	/**
	 * Subscribe the specified user to the specified thread.  If they are already
	 * subscribed, unsubscribe them.
	 * @param $userId {number} the id of the account to subscribe
	 * @param $threadId {number} the id of the thread to subscribe to
	 * @return 1 if user is subscribed as a result of this, 0 if they are unsubscribed.
	 */
	public static function subscribeUserToThread($userId, $threadId){
		if(!ForumModel::isUserSubscribedToThread($userId, $threadId)){
			$newSub = ORM::for_table('account_subscribes_to_thread')->create();
			$newSub->thread_id = $threadId;
			$newSub->account_id = $userId;
			$newSub->save(); 
			return 1;
		}else{
			ORM::for_table('account_subscribes_to_thread')->where('account_id', $userId)->where('thread_id', $threadId)->find_one()->delete();
			return 0;
		}
	}

	/**
	 *	Subscribe the specified user to this forum.  If they are already subscribed,
	 * unsubscribe them.
	 * @param $userId {number} the id of the account to subscribe
	 * @param $projectId {number} the id of the project to be subscribed to
	 * @return 1 if user has been subscribed or 0 if they have been unsubscribed
	 */
	public static function subscribeUserToForum($projectId, $userId){
		if(!ForumModel::isUserSubscribedToForum($projectId, $userId)){
			$newSub = ORM::for_table('account_subscribes_to_forum')->create();
			$newSub->forum_id = $projectId;
			$newSub->account_id = $userId;
			$newSub->save();
			return 1; 
		}else{
			$isSubscribed = ORM::for_table('account_subscribes_to_forum')->where('forum_id', $projectId)->find_one();
			$isSubscribed->delete();
			return 0;
		}
	}

	public static function generateJsonFriendlyArray($threads){
		$retVal = array();

		foreach($threads as $thread){
			$threadJson = array(
				'id' => $thread->id,
				'newestCommentUsername' => $thread->newestCommentUsername,
				'mostRecentCommentTime' => $thread->mostRecentCommentTime,
				'author_id' => $thread->authorId,
				'disabled' => $thread->disabled,
				'inappropriate' => $thread->inappropriate,
				'project_id' => $thread->project_id,
				'stickied' => $thread->stickied,
				'time' => $thread->time,
				'title' => $thread->title,
				'username' => $thread->username
			);
			array_push($retVal, $threadJson);
		}

		return $retVal;
	}
}