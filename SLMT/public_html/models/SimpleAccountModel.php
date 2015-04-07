<?php
/**
 * Simple account model
 * @author Emil.Stewart
*/
class SimpleAccountModel {

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
	 * Constructor that either creates a new account or throws an exception if login was incorrect.
	 * @param  $username
	 * @param  $password
	 */
	public function __construct($aid) {
			$this->account = ORM::for_table('account')->find_one($aid);
			//profile assigned
			$this->profile = ORM::for_table('profile')
			->find_one($this->account->Profile_id); //$profile->getProfileId();
			//TODO: Replace skills with a SkillsModel so that Endorsements support can be put in place
			$this->skills = ORM::for_table('account_has_skills')
			->inner_join('skills', 'Skills_id = s.id','s')
			->find_many();
			$this->systemPrivileges = ORM::for_table('system_privileges')->find_one($this->account->System_Privileges_id);
				
			//TODO: Change friends to only return a simple friends model
			$this->friends = ORM::for_table('friends')
			->where('Account_id1',$this->account->id)
			->inner_join('account','Account_id2 = a.id','a')
			->find_many();
				
	}

	public function getAccountId(){
		return $this->account->id;
	}

	/**
	 * Returns the profile with user information.
	 * @return Profile
	 */
	public function getProfile() {
		return $this->profile;
	}

	/**
	 * Returns the profile with user information.
	 * @return Profile
	 */
	public function getUsername() {
		return $this->account->username;
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
	 * Get's the profile picture url of the given simple account.
	 */
	public function getProfilePicUrl() {
		return $this->profile->profile_pic_url;
	}
	
	/**
	 * Return's the account's email address.
	 */
	public function getEmailAddress() {
		return $this->account->email_address;
	}
	
	/**
	 * Returns the account's first name
	 */
	public function getFirstName(){
		return $this->profile->firstname;
	}
	
	/**
	 * Returns the account's last name
	 */
	public function getLastName(){
		return $this->profile->lastname;
	}
	
	/**
	 * Returns the account's city
	 */
	public function getCity(){
		return $this->profile->city;
	}
	
	/**
	 * Returns the account's state
	 */
	public function getState(){
		return $this->profile->state;
	}
	
	/**
	 * Gets all of the friends associated with a given account.
	 */
	public static function getFriendsForAccount($aid) {
		$friends = ORM::for_table('friends')->where('Account_id1',$aid)->find_many();
		$friends2 = ORM::for_table('friends')->where('Account_id2',$aid)->find_many();
		$friendsForAccount = array();
		foreach ($friends as $friend) {
			array_push($friendsForAccount, new SimpleAccountModel($friend->Account_id2));
		}
		foreach ($friends2 as $friend) {
			array_push($friendsForAccount, new SimpleAccountModel($friend->Account_id1));
		}
		return $friendsForAccount;
	}
	
	/**
	 * Gets all of the friend requests associated with a given account.
	 */
	public static function getFriendRequestsForAccount($aid) {
		$friends = ORM::for_table('friend_request')->where('friend_requestee',$aid)->find_many();
		$friendRequestsForAccount = array();
		foreach ($friends as $friend) {
			array_push($friendRequestsForAccount, new SimpleAccountModel($friend->friend_requestor));
		}
		return $friendRequestsForAccount;
	}	
}
