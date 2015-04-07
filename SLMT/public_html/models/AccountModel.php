<?php

/**
 * Account database accessor class. This class refers only to the currently logged in account.
 * SimpleAccount model will be used to only get simple data about each account, not including the login information.
 * This class handles account privileges and login for the current user.
 * @author Emil.Stewart
 */
class AccountModel {
	
	/**
	 * The account ORM object
	 * @var Account
	 */
	private $account;
	
	/**
	 * The profile associated with this account
	 * @var Profile
	 */
	private $profile;

	
	/**
	 * ORM Collection of skills this account has
	 * @var Skills
	 */
	private $skills;
	
	/**
	 * System level privileges this account has
	 * @var
	 */
	private $systemPrivileges;
	
	/**
	 * ORM Collection of friends of this account
	 * @var 
	 */
	private $friends;
	
	/**
	 * ORM Collection of friend requests of this account
	 * @var
	 */
	private $friendRequests;

	/**
	 * Constructor that either creates a new account or throws an exception if login was incorrect.
	 * @param  $username
	 * @param  $password
	 */
	public function __construct($username, $password) {
		//attempt to authenticate with the system
		if ($this->authenticate($username,$password)) {
			//profile assigned
			$this->profile = ORM::for_table('profile')
				->find_one($this->account->Profile_id); //$profile->getProfileId();
			$this->skills = ORM::for_table('account_has_skills')
				->select('account_has_skills.*')
				->select('s.skill_name')
				->inner_join('skills', 'skills_id = s.id','s')
				->where ('Account_id',$this->account->id)
				->find_many();
			$this->systemPrivileges = ORM::for_table('system_privileges')->find_one($this->account->System_Privileges_id);
			
			$friends = SimpleAccountModel::getFriendsForAccount($this->account->id);
			
			
			$this->friends = SimpleAccountModel::getFriendsForAccount($this->account->id);
			
			$this->friendRequests = SimpleAccountModel::getFriendRequestsForAccount($this->account->id);
				
		} else {
			throw new LoginException("Login Failed");
		}
	}

	public static function getAccountById($id) {
		$account = ORM::for_table('account')->where('id', $id)->find_one();

		if($account){
			//profile assigned
			$account->profile = ORM::for_table('profile')
				->find_one($account->Profile_id); //$profile->getProfileId();
			$account->skills = ORM::for_table('account_has_skills')
				->select('Account_has_Skills.*')
				->select('s.skill_name')
				->inner_join('Skills', 'Skills_id = s.id','s')
				->where ('Account_id',$account->id)
				->find_many();
			$account->systemPrivileges = ORM::for_table('system_privileges')->find_one($account->System_Privileges_id);
			
			
			$account->friends = SimpleAccountModel::getFriendsForAccount($account->id);
			
			$account->friendRequests = SimpleAccountModel::getFriendRequestsForAccount($account->id);
		}

		return $account;
	}
	
	public static function getORMAccountByUsername($username) {
		return ORM::for_table('account')->where('username', $username)->find_one();
	}	
	/**
	 * Refreshes the connection to the ORM object for profile.
	 * @return Profile
	 */
	public function refreshProfile() {
		$this->account = ORM::for_table('account')
		->where('username', $this->account->username)
		->find_one();
		$this->profile = ORM::for_table('profile')
			->find_one($this->account->Profile_id); //$profile->getProfileId();
		return $this->profile;
	}
	
	/**
	 * Returns whether or not the account is banned.
	 * @return boolean
	 */
	public function isBanned(){
		return $this->account->is_banned>0;
	}

	public function isActivated(){
		if($this->account->locked_code === '-1'){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 * 
	 * Returns whether or not the user has defened the suspension.
	 * 0 is not defeneded, 1 is already defended the suspension.
	 */
	public function isDefended(){
		return $this->account->defend_suspension>0;
	}
	
	public function isPrivate(){
		return $this->profile->is_private > 0;
	}
	
	public function canRecieveEmails(){
		return $this->account->recieve_emails > 0;
	}
	
	/**
	 * Authenticates a user, ensuring that the user is valid.
	 * @param $username
	 * @param $password
	 * @return boolean
	 */
	private function authenticate ($username, $password) {
	
		$this->account = ORM::for_table('account')
			->where('username', $username)
			->where('password', hash('sha256',$password))
			->find_one();
	
		if ($this->account) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Returns the profile with user information.
	 * @return Profile
	 */
	public function getProfile() {
		return $this->profile;
	}
	
	/**
	 * Return's the user's account id.
	 */
	public function getAccountId() {
		return $this->account->id;
	}
	
	/**
	 * Returns the profile with user information.
	 * @return Profile
	 */
	public function getUsername(){
		return $this->account->username;
	}
	
	/**
	 * Returns the account email address
	 */
	public function getEmail() {
		return $this->account->email_address;
	}
	
	public function getProfilePicUrl() {
		return $this->profile->profile_pic_url;
	}

	
	/**
	 * Returns an object if the user has reported the given account id,
	 * returns false if the report never took place.
	 * @param unknown $aid
	 */
	public function hasReportedAccount($aid) {
		return ORM::for_table('reported_by')
			->where('reporter_id',$this->getAccountId())
			->where('reported_id', $aid)
			->find_one();
	}
	

	/**
	 * Returns the user's skills.
	 * @return Project
	 */
	public function getSkills() {
		return $this->skills;
	}
	
	/**
	 * Returns the user's system level privileges.
	 * @return Project
	 */
	public function getSystemPrivileges() {
		return $this->systemPrivileges;
	}
	
	/**
	 * Returns the user's friends.
	 * @return Project
	 */
	public function getFriends() {
		return $this->friends;
	}
	
	/**
	 * Returns whether or not a user is friends with this account
	 * @return friendship status
	 */
	public function hasFriend($id){
		$query1 = ORM::for_table('friends')
		 			->where('Account_id1', $this->account->id)
		 			->where("Account_id2", $id)
					->find_one();
		$query2 = ORM::for_table('friends')
		 			->where('Account_id1', $id)
		 			->where("Account_id2", $this->account->id)
					->find_one();
		return $query1 == true ? $query1 : $query2;
	}
	
	/**
	 * Returns if the user has a friend request out for this friend
	 * @return friendship status
	 */
	public function hasPendingRequestForUser($id){
		return ORM::for_table('friend_request')
		->where('friend_requestor',$this->account->id)
		->where("friend_requestee", $id)
		->find_one();
	}	
	
	/**
	 * Returns how many friend requests the user has
	 * @return friendship status
	 */
	public function getFriendRequests(){
		return ORM::for_table('friend_request')
		->where("friend_requestee", $this->account->id)
		->find_many();
	}
	
	/**
	 * Returns how many NEW friend requests the user has
	 * @return friendship status
	 */
	public function getNewFriendRequests(){
		return ORM::for_table('friend_request')
		->where("friend_requestee", $this->account->id)
		->where("new", 1)->find_many();
	}
	
	/**
	 * Returns the specific friend request
	 * @return friendship status
	 */
	public function getFriendRequestFromUser($requestorId){
		return ORM::for_table('friend_request')
		->where("friend_requestee", $this->account->id)
		->where("friend_requestor", $requestorId)
		->find_one();
	}
	
	/**
	 * Returns the user's security question.
	 * @return Project
	 */
	public function getSecurityQuestion() {
		return $this->security_question;
	}
	
	/**
	 * Returns any collaboration invites. A 'collaboration invite' is an invite, sent from an owner of a project, to be a collaborator on that owner's project
	 * @return collaborator invites
	 */
	public function getCollaborationInvites() {
		
	}
	
}

class LoginException extends Exception {
	
}
