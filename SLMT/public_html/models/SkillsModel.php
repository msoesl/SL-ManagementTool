<?php 
/*
This class represents the Skills.
The Skills has id and skill name.
@author almatroudis

*/
class SkillsModel{

	
	private $skill;
	
	
	/**
	 * Constructor that creates a new Skill Model
	 * @param  $skillId  
	 */
	public function __construct($skillId) {
		
		
		$this->skill = ORM::for_table('skills')->find_one($skillId);
		
	}
	
	public function getSkillName(){
		return $this->skill->skill_name;
	}
	
	public function getSkillId() {
		return $this->skill->id();
	}
	
	/**
	 * 
	 * Gets every skill for a given user
	 * 
	 * returned as an array of Skill Model objects
	 * 
	 * @param unknown $aid
	 * @return an array with Skill Models Objects.
	 */
	public static function getAllSkillsForAccount($aid) {
		$allSkills = ORM::for_table('account_has_skills')
			->inner_join('skills','Skills_id = s.id','s')
			->inner_join('account', 'Account_id = a.id', 'a')
			->find_many();
		$accountSkills = array();
		foreach ($allSkills as $skill) {
			$newSkillObject = new SkillsModel($skill->Skills_id);
			array_push($accountSkills,$newSkillObject);
		}
		return $accountSkills;
	}	
	
	/**
	 * Add a skill
	 * @param unknown $skillName
	 */
	public static function addSkill($skillName) {
		$skill = ORM::for_table('skills')->where('skill_name', $skillName)->find_one();
		if (!$skill) {
			$skill = ORM::for_table('skills')->create();
			$skill->skill_name = strip_tags($skillName);
			$skill->save();
		}
		return new SkillsModel($skill->id());
	}
}