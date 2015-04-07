<?php
//imports

/**
 * The ProjectModel is a class that allows for access of any and all project related data.
 * @author Emil.Stewart
 */
class ProjectModel {
	
////////////Actual functions available from Individual Project Models//////////////////////////////////////
	private $project;
	private $accounts;
	private $desiredSkillsForProject;
	private $stage1;
	private $stage3;
	private $profilePic;
	private $collaborators;

	/**
	 * Constructor that creates a new Project Model
	 * @param  $proj - the id of the project
	 */
	public function __construct($proj) {
		if (gettype($proj) == "object") {
			$this->project = $proj;
		} else {
			$this->project = ORM::for_table('project')->find_one($proj);
		}
		if($this->project){
			//TODO: SimpleAccount model for accounts
			$this->desiredSkillsForProject = ORM::for_table('project_has_skills')
				->inner_join('skills','skills_id = s.id','s')
				->inner_join('project','Project_id = p.id','p')
				->find_many();
			$this->stage1 = new Stage1Model($this->project->stage_1_id);
			$this->stage2 = ORM::for_table('stage_2')->find_one($this->project->stage_2_id);
			$this->stage3 = ORM::for_table('stage_3')->find_one($this->project->stage_3_id);

			$allPictures = $this->getAllPictures();
			foreach($allPictures as $pic){
			 	$this->profilePic = $pic;
			 	break;
			}
		}
	}
	
	public function getStage3() {
		return $this->stage3;
	}

	public function getProfilePicture() {
		return $this->profilePic;
	}

	public function isValid(){
		if($this->project){
			return true;
		}else{
			return false;
		}
	}

	//todo: configure to use current stage
	public function setProjectDescription($projectDescription){
		$this->stage1->setSummary($projectDescription);
	}

	public function getAllPictures() {
		$banners = ORM::for_table('project_banner')
		->where('project_id', $this->getProjectId())
		->where('enabled',1)
		->order_by_asc('sort_order')->find_many();
		return $banners;
	}

	public function getProjectTitle() {
		return $this->project->problem_title;
	}

	public function setProjectTitle($newTitle){
		$this->project->problem_title = strip_tags($newTitle);
		$this->project->save();
	}

	/**
	 *
	 * Gets the account permissions for the project with the given pid
	 * @param account id $aid
	 * @param project id $pid
	 * @return Ambigous <boolean, ORM, ORM>
	 */
	public static function getAccountPermissionsForProjectWithId($aid, $pid) {
		return ORM::for_table('project_has_account')
			->where('Account_id', $aid)
			->where('Project_id', $pid)
			->inner_join('project_privileges','pp.id = Project_Privileges_Id','pp')
			->find_one();
	}

	public function getProjectSummary() {
		return $this->stage1->getSummary();
	}

	public function getMostRecentActivity() {
		return "10/16/2013";
	}

	public function getProjectId() {
		return $this->project->id;
	}

	public function isProjectDiscontinued(){
		return $this->project->discontinued>0;
	}

	public function getDiscontinuedReason(){
		return $this->project->discontinued_reason;
	}

	public function getStage1Progress() {
		return $this->stage1->getProgress();
	}

	public function getStage2Progress() {
		return $this->stage2->progress;
	}

	public function getStage3Progress() {
		return $this->stage3->progress;
	}

	public function getStage() {
		//todo add completed case
		if($this->getStage3Progress() > 0){
			return 3;
		} else if ($this->getStage2Progress() > 0) {
			return 2;
		} else {
			return 1; //default
		}
	}

	public function setStage1Progress($progress){
		$this->stage1->setProgress($progress);
	}

	/**
	 * Returns a simple account model associated with the project lead for the given project.
	 * @return SimpleAccountModel
	 */
	public function getProjectOwner() {
		return new SimpleAccountModel($this->project->project_lead_id);
	}

	//////////////////////////////Static functions for getting collections of Project Models///////////////////////////////
	/**
	 * Returns all projects (array of ProjectModels) currently in Stage 1
	 */
	public static function getAllInStage1Projects() {
		$allStage1Projects = ORM::for_table('project')->select('project.*')->select('s.progress')->inner_join('stage_1','stage_1_id = s.id','s')->where_lt('s.progress', '100')->find_many();
		$stage1Projects = array();
		foreach ($allStage1Projects as $project) {
			array_push($stage1Projects, new ProjectModel($project));
		}
		return $stage1Projects;
	}
	
	public static function getPrevWidgetStage1Projects() {
		$stage1Projects = array();
		$topStage1Projects = ORM::for_table('project')
		->select('project.*')
		->select('s.progress')
		->order_by_desc('performance_index')
		->inner_join('stage_1','stage_1_id = s.id','s')
		->where_lt('s.progress', '100')
		->limit(6)
		->find_many();
		foreach($topStage1Projects as $project) {
			array_push($stage1Projects, new ProjectModel($project));
		}
		//if there were at least 6 projects
		if (sizeof($topStage1Projects) == 6) {
			$count = $topStage1Projects = ORM::for_table('project')
					->select('project.*')
					->select('s.progress')
					->order_by_desc('performance_index')
					->inner_join('stage_1','stage_1_id = s.id','s')
					->where_lt('s.progress', '100')
					->count('project.id') - 6;
			if ($count > 6) {
				$count = 6;
			}
			$bottomStage1Projects = ORM::for_table('project')
			->select('project.*')
			->select('s.progress')
			->order_by_asc('performance_index')
			->inner_join('stage_1','stage_1_id = s.id','s')
			->where_lt('s.progress', '100')
			->limit($count)
			->find_many();
			foreach($bottomStage1Projects as $project) {
				array_push($stage1Projects, new ProjectModel($project));
			}
		}
			shuffle($stage1Projects);
			return $stage1Projects;
	}

	/**
	 * Returns all projects (array of ProjectModels) currently in Stage 2
	 */
	public static function getAllInStage2Projects() {
		$allStage2Projects = ORM::for_table('project')
			->select('project.*')
			->select('s1.progress')
			->select('s2.progress')
			->inner_join('stage_1','stage_1_id = s1.id','s1')
			->inner_join('stage_2','stage_2_id = s2.id','s2')
			->where('s1.progress', '100')
			->where_lt('s2.progress', '100')
			->find_many();
		$stage2Projects = array();
		foreach ($allStage2Projects as $project) {
			array_push($stage2Projects, new ProjectModel($project));
		}
		return $stage2Projects;
	}

	public static function getPrevWidgetStage2Projects() {
		$stage2Projects = array();
		$topStage2Projects = ORM::for_table('project')
		->select('project.*')
		->select('s.progress')
		->select('s2.progress')
		->order_by_desc('performance_index')
		->inner_join('stage_1','stage_1_id = s.id','s')
		->inner_join('stage_2','stage_2_id = s2.id','s2')
		->where('s.progress', '100')
		->where_lt('s2.progress', '100')
		->limit(6)
		->find_many();
		foreach($topStage2Projects as $project) {
			array_push($stage2Projects, new ProjectModel($project));
		}
		//if there were at least 6 projects
		if (sizeof($topStage2Projects) == 6) {
			$count = ORM::for_table('project')
		->select('project.*')
		->select('s.progress')
		->select('s2.progress')
		->inner_join('stage_1','stage_1_id = s.id','s')
		->inner_join('stage_2','stage_2_id = s2.id','s2')
		->where('s.progress', '100')
		->where_lt('s2.progress', '100')->count('project.id') - 6;
			if ($count > 6) {
				$count = 6;
			}
			$bottomStage2Projects = ORM::for_table('project')
		->select('project.*')
		->select('s.progress')
		->select('s2.progress')
		->order_by_asc('performance_index')
		->inner_join('Stage_1','stage_1_id = s.id','s')
		->inner_join('Stage_2','stage_2_id = s2.id','s2')
		->where('s.progress', '100')
		->where_lt('s2.progress', '100')
		->limit($count)
		->find_many();
			foreach($bottomStage2Projects as $project) {
				array_push($stage2Projects, new ProjectModel($project));
			}
		}
		shuffle($stage2Projects);
		return $stage2Projects;
	}
	
	/**
	 * Returns all projects (array of ProjectModels) currently in Stage 3
	 */
	public static function getAllInStage3Projects() {
		$allStage3Projects = ORM::for_table('project')
			->select('project.*')
			->select('s1.progress')
			->select('s2.progress')
			->select('s3.progress')
			->inner_join('stage_1','stage_1_id = s1.id','s1')
			->inner_join('stage_2','stage_2_id = s2.id','s2')
			->inner_join('stage_3','stage_3_id = s3.id','s3')
			->where('s1.progress', '100')
			->where('s2.progress', '100')
			->where_lt('s3.progress', '100')
			->find_many();
		$stage3Projects = array();
		foreach ($allStage3Projects as $project) {
			array_push($stage3Projects, new ProjectModel($project));
		}
		return $stage3Projects;
	}
	

	public static function getPrevWidgetStage3Projects() {
		$stage3Projects = array();
		$topStage3Projects = ORM::for_table('project')
		->select('project.*')
		->select('s.progress')
		->select('s2.progress')
		->select('s3.progress')
		->order_by_desc('performance_index')
		->inner_join('Stage_1','stage_1_id = s.id','s')
		->inner_join('Stage_2','stage_2_id = s2.id','s2')
		->inner_join('Stage_3','stage_3_id = s3.id','s3')
		->where('s.progress', '100')
		->where('s2.progress', '100')
		->where_lt('s3.progress', '100')
		->limit(6)
		->find_many();
		foreach($topStage3Projects as $project) {
			array_push($stage3Projects, new ProjectModel($project));
		}
		//if there were at least 6 projects
		if (sizeof($topStage3Projects) == 6) {
			$count = ORM::for_table('project')
					->select('project.*')
					->select('s.progress')
					->select('s2.progress')
					->select('s3.progress')
					->inner_join('stage_1','stage_1_id = s.id','s')
					->inner_join('stage_2','stage_2_id = s2.id','s2')
					->inner_join('stage_3','stage_3_id = s3.id','s3')
					->where('s.progress', '100')
					->where('s2.progress', '100')
					->where_lt('s3.progress', '100')
					->count('project.id') - 6;
			if ($count > 6) {
				$count = 6;
			}
			$bottomStage3Projects = ORM::for_table('project')
			->select('project.*')
			->select('s.progress')
			->select('s2.progress')
			->select('s3.progress')
			->order_by_asc('performance_index')
			->inner_join('stage_1','stage_1_id = s.id','s')
			->inner_join('stage_2','stage_2_id = s2.id','s2')
			->inner_join('stage_3','stage_3_id = s3.id','s3')
			->where('s.progress', '100')
			->where('s2.progress', '100')
			->where_lt('s3.progress', '100')
			->limit($count)
			->find_many();
			foreach($bottomStage3Projects as $project) {
				array_push($stage3Projects, new ProjectModel($project));
			}
		}
		shuffle($stage3Projects);
		return $stage3Projects;
	}
	
	/**
	 * 
	 * Get's every project for the given account, also returns the account title.
	 * 
	 * returned as an array, iterrate over it like this:
	 * $projects = ProjectModel::getAllProjectsForAccount($accountId);
	 * foreach ($projects as $project) {
	 * 
	 * }
	 * 
	 * @param unknown $aid
	 * @return multitype: array of arrays, where the inner array has the project model and account title for the project
	 */
	public static function getAllProjectsForAccount($aid) {
		$allProjectsForAccount = ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('account','Account_id = a.id','a')
		->inner_join('account_title','Account_Title_ID = at.id','at')
		->where('a.id', $aid)
		->find_many();
		$accountProjectsAndTitles = array();
		foreach ($allProjectsForAccount as $project) {
			array_push($accountProjectsAndTitles,array('projectModel'=>new ProjectModel($project),'title'=>$project->title));
		}
		return $accountProjectsAndTitles;
	}

	/**
	 * This returns all the active projects a user is involved in
	 * @param $aid - the account id of the user whose projects are being viewed
	 * @param $offset - the page of projects that the user is viewing
	 * @param $limit - the number of projects to be returned (normally 6)
	 * @return multitype:
	 */
	public static function getActiveProjectsForAccount($aid, $offset, $limit){
		$projects = ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->where('project_has_account.Account_id', $aid)
		->where('p.discontinued', 0)
		->limit($limit)
		->offset(6 * ($offset-1))
		->order_by_desc('p.performance_index')
 		->find_many();
		$projectModels = array();
		foreach ($projects as $project){
			array_push($projectModels, new ProjectModel($project));
		}
		return $projectModels;
	}
	
	/**
	 * Gets the number of active projects that a user belongs to
	 * @param unknown $aid
	 */
	public static function getCountOfActiveProjectsForAccount($aid){
		return ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('account','Account_id = a.id','a')
		->where('a.id', $aid)
		->where('p.discontinued', 0)
		->count();
	}
	
	/**
	 * This returns all the active projects a user is involved in
	 * @param $aid - the account id of the user whose projects are being viewed
	 * @param $offset - the page of projects that the user is viewing
	 * @param $limit - the number of projects to be returned (normally 6)
	 * @return multitype:
	 */
	public static function getCompletedProjectsForAccount($aid, $offset, $limit){
		$projects = ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('stage_3', 'stage_3_id = s.id', 's')
		->where('project_has_account.Account_id', $aid)
		->where('s.progress', 100)
		->limit($limit)
		->offset(6 * ($offset-1))
		->order_by_desc('p.performance_index')
		->find_many();
		$projectModels = array();
		foreach ($projects as $project){
			array_push($projectModels, new ProjectModel($project));
		}
		return $projectModels;
	}
	
	/**
	 * Gets the number of active projects that a user belongs to
	 * @param unknown $aid
	 */
	public static function getCountOfCompletedProjectsForAccount($aid){
		return ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('stage_3', 'stage_3_id = s.id', 's')
		->where('project_has_account.Account_id', $aid)
		->where('s.progress', 100)
		->count();
	}

	/**
	 * This returns all the active projects a user is involved in
	 * @param $aid - the account id of the user whose projects are being viewed
	 * @param $offset - the page of projects that the user is viewing
	 * @param $limit - the number of projects to be returned (normally 6)
	 * @return multitype:
	 */
	public static function getDiscontinuedProjectsForAccount($aid, $offset, $limit){
		$projects = ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->where('project_has_account.Account_id', $aid)
		->where('p.discontinued', 1)
		->limit($limit)
		->offset(6 * ($offset-1))
		->order_by_desc('p.performance_index')
		->find_many();
		$projectModels = array();
		foreach ($projects as $project){
			array_push($projectModels, new ProjectModel($project));
		}
		return $projectModels;
	}
	
	/**
	 * Gets the number of active projects that a user belongs to
	 * @param unknown $aid
	 */
	public static function getCountOfDiscontinuedProjectsForAccount($aid){
		return ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('account','Account_id = a.id','a')
		->where('a.id', $aid)
		->where('p.discontinued', 1)
		->count();
	}

	public static function  getAllProjectsForAccountCollaboration($aid){
		$allProjectsForAccount = ORM::for_table('project_has_account')
		->where('Account_id',$aid)
		->find_many();
		return $allProjectsForAccount;

	}

	public static function  getAllAccountsForProjectCollaboration($pid){
		$allAccountsForProject = ORM::for_table('project_has_account')
		->where('Project_id',$pid)
		->find_many();
		return $allAccountsForProject;
	}
	
	/**
	 * Returns any collaboration invites. A 'collaboration invite' is an invite, sent from an owner of a project, to be a collaborator on that owner's project
	 * @return collaborator invites
	 */
	public static function getAllCollaborationInvitesForAccount($aid) {
		return ORM::for_table('project_has_account')
		->where('Account_id', $aid)
 		->where('Account_Title_ID', 2)
		->inner_join('project_privileges', array('project_has_account.project_privileges_id', '=', 'project_privileges.id'))
 		->inner_join('invite_permission', array('project_privileges.invite_permission_id', '=', 'invite_permission.id'))
 		->where('project_privileges.collaborator_permission', 0)
 		->where('invite_permission.invite_status', 0)
		->find_many();
	}

	/**
	 * Get all Projects with their title for filtering
	 * @return collaborator invites
	 */
	public static function getAllProjectsForFiltering() {
		 $projects = ORM::for_table ( 'project' )->select ( 'project.*' )->select ( 'problem_title' )->find_many ();
		 return $projects;
	}
	/**
	 * Returns new collaboration invites. A 'collaboration invite' is an invite, sent from an owner of a project, to be a collaborator on that owner's project
	 * @return new collaboration invites
	 */
	public static function getNewCollaborationInvitesForAccount($aid) {
		return ORM::for_table('project_has_account')
		->where('Account_id', $aid)
		->where('Account_Title_ID', 2)
		->inner_join('project_privileges', array('project_has_account.Project_Privileges_id', '=', 'project_privileges.id'))
		->inner_join('invite_permission', array('project_privileges.invite_permission_id', '=', 'invite_permission.id'))
		->where('project_privileges.collaborator_permission', 0)
		->where('invite_permission.invite_status', 0)
		->where('invite_permission.new', 1)
		->find_many();
	}
	/**
	 *
	 * Get's every account for a given project.
	 *
	 * @param unknown $aid
	 * @return multitype: array of arrays, where the inner array has the project model and account title for the project
	 */
	public static function getAllAccountsForProject($pid) {
	
		$allProjectsForAccount = ORM::for_table('project_has_account')
		->inner_join('project','Project_id = p.id','p')
		->inner_join('account','Account_id = a.id','a')
		->inner_join('project_privileges','Project_Privileges_id = pp.id', 'pp')
		->where('p.id', $pid)
		->find_many();
		return $allProjectsForAccount;
	}

	/**
	 * Returns all of the completed projects.
	 * @return multitype:
	 */
	public static function getAllCompletedProjects() {
		$allStage3Projects = ORM::for_table('project')
			->inner_join('Stage_3','stage_3_id = s3.id','s3')
			->where('s3.progress', '100')
			->find_many();
		$stage3Projects = array();
		foreach ($allStage3Projects as $project) {
			array_push($stage3Projects, new ProjectModel($project));
		}
		return $stage3Projects;
	}
	
	/**
	 * Creates an event for this project
	 * @param title: title of the thread
	 * @param content: content of the first post
	 * of the thread.
	 */
	public function createEvent($title, $date, $description){
		if(!isset($title) || !isset($date) || !isset($description)){
			return null;
		}
		//create the new thread
		$event = ORM::for_table('event')->create();
		$event->project_id = $this->getProjectid();
		$event->title = $title;
		$event->date_time = $date;
		$event->description = $description;
		$event->save();
	}
}
