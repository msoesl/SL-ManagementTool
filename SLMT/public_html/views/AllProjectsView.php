<?php
	require_once ('import/headimportviews.php');
	//The number of projects in the think stage
	$numProjects = ORM::for_table('project')->count();
	//The current page number
	$currentPageNum = 1;
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
	$allProjects = array();
	$rawProjectsList = ORM::for_table('project')
							->order_by_desc('project.performance_index')
							->offset(6 * ($currentPageNum-1))
							->limit(6)
							->find_many();
	foreach ($rawProjectsList as $project) {
		array_push($allProjects, new ProjectModel($project));
	}
	

?>

<div class = 'filter-page'>
	<div class='learn-more-title filter-title'>
		<h1>All Projects</h1>
		<hr>
		<p> Get involved with projects that are currently in the works. Servant-leaders are doing great things on MSOE&#39;s campus and around the world.</p>
	</div>
	<div class='projects-viewer-container'>
		<ul class="preview_widget_listview" data-theme='a' data-role="listview">
			<?php
				/*this will iterate over the projects*/
				foreach($allProjects as $project) {?>
				<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme='f' style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
					 <a href="#">
						<h1><?php echo $project->getProjectTitle();?></h1>
					 	<div class="underlay"></div>			 	
					 </a>
					 <div class="extra_project_details">
					 	<h1>Read More</h1>
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
			<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'1'})">First</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
			<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>	
		</span>
		<span class='nav-options nav-options-small-screen'>
			<div>
				<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $currentPageNum-1?>'})">Previous</a>
				<div class='small-spacing' style='display:inline-block;width:20px'></div>
				<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $currentPageNum+1?>'})">Next</a>
			</div>
			<div>
				<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'1'})">First</a>
				<div class='big-spacing' style='display:inline-block;width:50px'></div>
				<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadAllProjectsView({'page' :'<?php echo $numPages?>'})">Last</a>				
			</div>
		<?php }?>
		</span>
	</div>	
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