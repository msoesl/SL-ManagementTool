<?php
/*
This class represents the System Privileges.
*/

class SystemPrivilegesModel{

	private $systemPrivilege;
	
	public function __construct($systemPrivilegeId){
		$this->systemPrivilege = ORM::for_table('system_privileges')->find_one($systemPrivilegeId);
	}

	
	public function getAccountManagmentPermission(){
	
		return $this->systemPrivilege->account_managment_permission;
	
	}
	
	
	public function getProjectManagmentPermission(){
	
		return $this->systemPrivilege->project_managment_permission;
	
	}
	
	public function getId(){
	
		return $this->systemPrivilege->id;
	
	}
	
}