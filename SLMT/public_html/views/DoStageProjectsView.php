<?php
	require_once ('import/headimportviews.php');
	//The number of projects in the do stage
	$numProjects = ORM::for_table('project')
							->select('s1.progress')
							->select('s2.progress')
							->inner_join('stage_1','stage_1_id = s1.id','s1')
							->inner_join('stage_2','stage_2_id = s2.id','s2')
							->where('s1.progress', '100')
							->where_lt('s2.progress', '100')
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
	//Get all projects in Stage 2, ordered by their performance index
	$doProjects = array();
	$allStageDoProjects = ORM::for_table('project')
							->select('project.*')
							->select('s1.progress')
							->select('s2.progress')
							->inner_join('stage_1','stage_1_id = s1.id','s1')
							->inner_join('stage_2','stage_2_id = s2.id','s2')
							->where('s1.progress', '100')
							->where_lt('s2.progress', '100')
							->order_by_desc('project.performance_index')
							->limit(6)
							->offset(6 * ($currentPageNum-1))
							->find_many();
	foreach ($allStageDoProjects as $project) {
		array_push($doProjects, new ProjectModel($project));
	}
?>

<div class = 'filter-page stage2_projects'>
	<div class='learn-more-title filter-title'>
		<h1>Projects in <i style='font-weight:bold;color:#8064A1'>Do</i> Stage</h1>
		<hr>
		<div class='nav-row'>
			<a id='learn-more-popup-button' href='#do-info-popup' data-role='button' data-rel='popup' data-icon='info' style='margin:0 auto'>Learn more!</a>
		</div>
		<p class='info-static'><i>Healing oneself and others is a great strength of the servant-leader. Learning to heal is a powerful force for transformation and being an effective leader. Servant-leaders rely on persuasion, rather than positional authority in making decisions. Convincing others instead of coercing compliance is one of the clearest distinctions between the traditional model of leadership and the servant-leader.</i></p>
	</div>

	<ul class="preview_widget_listview projects-viewer-container" data-theme="a" data-role="listview">
		<?php
			/*this will iterate over the projects*/
			foreach($doProjects as $project) {?>
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
			<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'1'})">First</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
			<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>	
		</span>
		<span class='nav-options nav-options-small-screen'>
			<div>
				<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
				<div class='small-spacing' style='display:inline-block;width:20px'></div>
				<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			</div>
			<div>
				<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'1'})">First</a>
				<div class='big-spacing' style='display:inline-block;width:50px'></div>
				<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadDoStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>				
			</div>
		<?php }?>
		</span>
	</div>	
</div>

<div data-role='popup' id='do-info-popup' data-transition='slideup' data-position-to='window' data-history='false'>
	<ul data-role='listview'>
		<li data-role='list-divider'>About the Do Stage</li>
		<li data-role='listitem'><i>Healing oneself and others is a great strength of the servant-leader. Learning to heal is a powerful force for transformation and being an effective leader. Servant-leaders rely on persuasion, rather than positional authority in making decisions. Convincing others instead of coercing compliance is one of the clearest distinctions between the traditional model of leadership and the servant-leader.</i></li>
	</ul>
</div>
<!-- include this last, inits the listeners -->
<script type="text/javascript">
	PreviewWidget.init();	
	if(<?php echo $currentPageNum;?> <= 1){
		$(".previous").addClass('ui-disabled');
		$(".first").addClass('ui-disabled');
	}
	if(<?php echo $currentPageNum;?> == <?php echo $numPages;?>){
		$(".next").addClass('ui-disabled');
		$(".last").addClass('ui-disabled');
	} 
</script>