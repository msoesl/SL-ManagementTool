<?php
	require_once ('import/headimportviews.php');
	//The number of projects in the achieve stage
	$numProjects = ORM::for_table('project')
		->select('s1.progress')
		->select('s2.progress')
		->select('s3.progress')
		->inner_join('stage_1','stage_1_id = s1.id','s1')
		->inner_join('stage_2','stage_2_id = s2.id','s2')
		->inner_join('stage_3','stage_3_id = s3.id','s3')
		->where('s1.progress', '100')
		->where('s2.progress', '100')
		->where_lt('s3.progress', '100')
		->count();
	//The current page number
	$currentPageNum = 1;
	$numPages = 1;
	//The number of pages required for this stage
	$numPages = intval($numProjects/6);
	//If $actualNumProjects/6 does not result in a whole number, then that means there is a few extra projects. Add one page to account for those extra projects.
	if((($numProjects/6) - $numPages) != 0){
		$numPages++;
	}
	if(isset($_GET['page'])){
		$currentPageNum = (($_GET['page'] > $numPages || $_GET['page'] <= 0) ? 1 : $_GET['page']);
	}
	//Get all projects in Stage 3, ordered by their performance index
	$achieveProjects = array();
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
							->order_by_desc('project.performance_index')
							->limit(6)
							->offset(6 * ($currentPageNum-1))
							->find_many();
	foreach ($allStage3Projects as $project) {
		array_push($achieveProjects, new ProjectModel($project));
	}
?>

<div class = 'filter-page stage3_projects'>
	<div class='learn-more-title filter-title'>
		<h1>Projects in <i style='font-weight:bold;color:#4AACC5'>Achieve</i> Stage</h1>
		<hr>
		<div class='nav-row'>
			<a id='learn-more-popup-button' href='#achieve-info-popup' data-role='button' data-rel='popup' data-icon='info' style='margin:0 auto'>Learn more!</a>
		</div>
		<p class='info-static'><i>When you have committed to serving the needs of others, stewardship has been achieved. The servant-leader uses stewardship to encourage others to work towards a greater good in society. Servant-leaders encourage their colleagues to grow professionally, personally, and spiritually. This commitment to every individual in an organization makes for a successful servant-leader. Having a community feel in a group or workplace is key to being an effective servant-leader. Working collaboratively leads to a healthy organization. Leaders and workers that build relationships achieve a successful atmosphere.</i></p>
	</div>
	<ul class="preview_widget_listview projects-viewer-container" data-theme="a" data-role="listview">
		<?php
			/*this will iterate over the projects*/
			foreach($achieveProjects as $project) {?>
			<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
			<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme="a" style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
				 <a href="#">
					<h1><?php echo $project->getProjectTitle();?></h1>
				 	<div class="underlay"></div>			 	
				 </a>
				 <div class="extra_project_details">
				 	<h1>Thinking</h1>
				 	<p><?php echo $project->getProjectSummary();?></p>						 	
				 </div>	
			</li>
		<?php }?>		
	</ul>
	
	<?php $currentPageNum = ($numPages == 0)?0:$currentPageNum;?>
	<p style='margin-top:20px;text-align:center'>Page <?php echo $currentPageNum;?> of <?php echo $numPages;?></p>
	<div class='nav-row'>
		<span class='nav-options nav-options-large-screen'>
		<?php if($numProjects > 6){?>
			<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'1'})">First</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
			<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>	
		</span>
		<span class='nav-options nav-options-small-screen'>
			<div>
				<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
				<div class='small-spacing' style='display:inline-block;width:20px'></div>
				<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			</div>
			<div>
				<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'1'})">First</a>
				<div class='big-spacing' style='display:inline-block;width:50px'></div>
				<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadAchieveStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>				
			</div>
		<?php }?>
		</span>
	</div>	
</div>

<div data-role='popup' id='achieve-info-popup' data-transition='slideup' data-position-to='window' data-history='false'>
	<ul data-role='listview'>
		<li data-role='list-divider'>About the Achieve Stage</li>
		<li><i>When you have committed to serving the needs of others, stewardship has been achieved. The servant-leader uses stewardship to encourage others to work towards a greater good in society. Servant-leaders encourage their colleagues to grow professionally, personally, and spiritually. This commitment to every individual in an organization makes for a successful servant-leader. Having a community feel in a group or workplace is key to being an effective servant-leader. Working collaboratively leads to a healthy organization. Leaders and workers that build relationships achieve a successful atmosphere.</i></li>
	</ul>
</div>
<!-- include this last, inits the listeners -->
<script type="text/javascript">
	PreviewWidget.init();	
</script>