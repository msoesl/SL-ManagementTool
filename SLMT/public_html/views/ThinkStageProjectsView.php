<?php
	require_once ('import/headimportviews.php');
	//The number of projects in the think stage
	$numProjects = ORM::for_table('project')
		->select('s.progress')
		->inner_join('stage_1','stage_1_id = s.id','s')
		->where_lt('s.progress', '100')
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
	$thinkProjects = array();
	$allStageThinkProjects = ORM::for_table('project')
							->select('project.*')
							->select('s.progress')
							->inner_join('stage_1','stage_1_id = s.id','s')
							->where_lt('s.progress', '100')
							->order_by_desc('project.performance_index')
							->offset(6 * ($currentPageNum-1))
							->limit(6)
							->find_many();
	foreach ($allStageThinkProjects as $project) {
		array_push($thinkProjects, new ProjectModel($project));
	}
	

?>

<div class = 'filter-page stage1_projects'>
	<div class='learn-more-title filter-title'>
		<h1>Projects in <i style='font-weight:bold;color:#9BBB58'>Think</i> Stage</h1>
		<hr>
		<div class='nav-row'>
			<a id='learn-more-popup-button' href='#think-info-popup' data-role='button' data-rel='popup' data-icon='info' style='margin:0 auto'>Learn more!</a>
		</div>
		<p class='info-static'><i>Servant-leaders seek to nurture their abilities to &quot;dream great dreams.&quot; This creates a mindset that encourages thinking outside of the box. A balance must be found between conceptualization and day-to-day thinking in order to be an effective leader. The intuitive mind of the servant-leader embraces foresight. This is the ability to take lessons from the past and apply them to potential issues that will happen in the future. Thinking of possible problems creates a proactive atmosphere.</i></p>
	</div>
	<div class='projects-viewer-container'>
		<ul class="preview_widget_listview" data-theme='a' data-role="listview">
			<?php
				/*this will iterate over the projects*/
				foreach($thinkProjects as $project) {?>
				<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme='f' style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
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
	</div>
	<?php $currentPageNum = ($numPages == 0)?0:$currentPageNum;?>
	<p style='margin-top:20px;text-align:center;'>Page <?php echo $currentPageNum;?> of <?php echo $numPages;?></p>
	<div class='nav-row'>
		<span class='nav-options nav-options-large-screen'>
		<?php if($numProjects > 6){?>
			<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'1'})">First</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
			<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>	
		</span>
		<span class='nav-options nav-options-small-screen'>
			<div>
				<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
				<div class='small-spacing' style='display:inline-block;width:20px'></div>
				<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			</div>
			<div>
				<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'1'})">First</a>
				<div class='big-spacing' style='display:inline-block;width:50px'></div>
				<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadThinkStageProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>				
			</div>
		<?php }?>
		</span>
	</div>	
</div>

<div data-role='popup' id='think-info-popup' data-transition='slideup' data-position-to='window' data-history='false'>
	<ul data-role='listview'>
		<li data-role='list-divider'>About the Think Stage</li>
		<li data-role='listitem'><i>Servant-leaders seek to nurture their abilities to &quot;dream great dreams.&quot; This creates a mindset that encourages thinking outside of the box. A balance must be found between conceptualization and day-to-day thinking in order to be an effective leader. The intuitive mind of the servant-leader embraces foresight. This is the ability to take lessons from the past and apply them to potential issues that will happen in the future. Thinking of possible problems creates a proactive atmosphere.</i></li>
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