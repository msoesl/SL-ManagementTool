<?php 
/*
This class represents the staeg1.
The Skills has id , progress of the project and summary.

*/
class Stage1Model{

	/**
	 * Stage 1 ORM object
	 * @var unknown
	 */
	private $stage1;
	
	
	/**
	 * Creates a Stage 1 model
	 * @param unknown $sid
	 */
	public function __construct($sid) {
		$this->stage1 = ORM::for_table('stage_1')->find_one($sid);
	}
	
	/**
	 * Returns the summary
	 */
	public function getSummary() {
		return $this->stage1->problem_summary;
	}
	
	/**
	 * Returns the stage 1 progress
	 */
	public function getProgress() {
		return $this->stage1->progress;
	}
	
	/**
	 * Returns the summary
	 */
	public function setSummary($sum) {
		$this->stage1->problem_summary = $sum;
		$this->stage1->save();
	}
	
	/**
	 * Returns the stage 1 progress
	 */
	public function setProgress($progress) {
		$this->stage1->progress = $progress;
		$this->stage1->save();
	}
	
}
?>