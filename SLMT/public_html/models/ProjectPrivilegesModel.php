<?php
/*
 This class represents the Project Privileges.
 
 */

class ProjectPrivilegesModel{

	/**
	 * Project Privilege object
	 */
	private $projectPrivilege;

	/**
	 * 
	 * Project Privilege constructor
	 * @param int $projectPrivilegeId
	 */
	public function __construct($projectPrivilegeId){
		// Initialize this object
		$this->projectPrivilege = ORM::for_table('project_privileges')->find_one($projectPrivilegeId);
		
	}

	/**
	 * 
	 * Get Moderate Discussions Permission
	 */
	public function getModerateDiscussionsPermission(){

		return $this->projectPrivilege->moderate_discussions_permission;

	}

	/**
	 * 
	 * Get Edit Project Content Permission
	 */
	public function getEditProjectContentPermission(){

		return $this->projectPrivilege->control_project_stages_permission;

	}

	/**
	 * 
	 * Get Delete Users From Project Permission
	 */
	public function getDeleteUsersFromProjectPermission(){

		return $this->projectPrivilege->delete_users_from_project_permission;

	}




	/**
	 * 
	 * Get Collaborator Permission
	 */
	public function getCollaboratorPermission(){

		return $this->projectPrivilege->collaborator_permission;

	}
	
	/**
	 * 
	 * Get Collaborator Permission
	 */
	public function getControlProjectStagesPermission(){

		return $this->projectPrivilege->control_project_stages_permission;

	}
	/**
	 * 
	 * Get the object id
	 */
	public function getId(){

		return $this->projectPrivilege->id;

	}

}