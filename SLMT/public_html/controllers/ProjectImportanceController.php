<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];
$problemSolutionCounts = array();
$problemSolutionMax = 0;
$problemSolutionMin = 1000;

$problemCollaboratorCounts = array();
$problemCollaboratorMax = 0;
$problemCollaboratorMin = 1000;

$problemEventsCounts = array();
$problemEventMax = 0;
$problemEventMin = 1000;

$allProjects = ORM::for_table('project')->find_many();

//count and find min and max of all project attributes
foreach($allProjects as $project) {
	//count problem solutions and update values
	$problemSolutionCounts[$project->id] = ORM::for_table('problem_solution')
		->where('stage_2_id', $project->stage_2_id)
		->count('id');
	$problemSolutionMax = ($problemSolutionCounts[$project->id]>$problemSolutionMax)?$problemSolutionCounts[$project->id]:$problemSolutionMax;
	$problemSolutionMin = ($problemSolutionCounts[$project->id]<$problemSolutionMin)?$problemSolutionCounts[$project->id]:$problemSolutionMin;
	
	//count collaborators and update values
	$problemCollaboratorCounts[$project->id] = ORM::for_table('project_has_account')
			->where('Project_id', $project->id)
			->count('id');
	$problemCollaboratorMax = ($problemCollaboratorCounts[$project->id]>$problemCollaboratorMax)?$problemCollaboratorCounts[$project->id]:$problemCollaboratorMax;
	$problemCollaboratorMin = ($problemCollaboratorCounts[$project->id]<$problemCollaboratorMin)?$problemCollaboratorCounts[$project->id]:$problemCollaboratorMin;
	

	//count events and update values
	$problemEventsCounts[$project->id] = ORM::for_table('event')
			->where('Project_id', $project->id)
			->count('id');
	$problemEventMax = ($problemEventsCounts[$project->id]>$problemEventMax)?$problemEventsCounts[$project->id]:$problemEventMax;
	$problemEventMin = ($problemEventsCounts[$project->id]<$problemEventMin)?$problemEventsCounts[$project->id]:$problemEventMin;
}

//calculate min-max
foreach ($allProjects as $project) {
	$problemSolutionCounts[$project->id] = ($problemSolutionCounts[$project->id] - $problemSolutionMin)/$problemSolutionMax;
	$problemCollaboratorCounts[$project->id] = ($problemCollaboratorCounts[$project->id] - $problemCollaboratorMin)/$problemCollaboratorMax;
	$problemEventsCounts[$project->id] = ($problemEventsCounts[$project->id] - $problemEventMin)/$problemEventMax;
	$project->performance_index = $problemSolutionCounts[$project->id] + $problemCollaboratorCounts[$project->id] + $problemEventsCounts[$project->id];
	
	if ($project->performance_index ==0 ) {
		//for the index to be really really tiny <0.1 guaranteed
		$smallRandomIndex = mt_rand() / mt_getrandmax() / 10;
		$project->performance_index = $smallRandomIndex;
	}
	$project->save();
}

header( 'Location: ../#PrevWidgetManagementView.php');