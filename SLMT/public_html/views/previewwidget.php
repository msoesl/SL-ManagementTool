<?php 
	require_once('import/headimportviews.php')
?>
<div id="preview_widget" data-role="content">

    <div data-role="navbar" id="navbar">
		<ul>
			<li class="think-tab"><a href="#" data-tab-class="stage1_projects" class="ui-btn-active ui-state-persist" data-theme="a">Think</a></li>
			<li class="do-tab"><a href="#" data-tab-class="stage2_projects" data-theme="a">Do</a></li>
			<li class="achieve-tab"><a href="#" data-tab-class="stage3_projects" data-theme="a">Achieve</a></li>
		</ul>
	</div>
	
	<div id="preview_results_container" data-role="content">
		<!-- STAGE 1 -->
		<div class="stage1_projects" data-role="content">	
			<ul class="preview_widget_listview" data-theme="a" data-role="listview">
			<?php

				$stage1Projects = ProjectModel::getPrevWidgetStage1Projects();

				/*this will iterate over the projects*/
				foreach($stage1Projects as $project) {?>
				<?php $jsonObj = array('id'=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme="a" style='background:url(<?php if ($project->getProfilePicture()) { echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>			 
					 <a href="#">
					  	<h1><?php echo $project->getProjectTitle();?></h1>
					 	<div class="progress_status">Think</div>
					 	<div class="underlay"></div>	
					 </a>
					 <div class="extra_project_details">
					 	<h1>Read more...</h1>
					 	<p><?php echo $project->getProjectSummary();?></p>						 	
					 </div>		
				</li>
			<?php }?>
			
			</ul>
		</div>
		
		<!-- ALL STAGE 2 -->
		 <div class="stage2_projects ui-screen-hidden" data-role="content">
			<ul class="preview_widget_listview" data-theme="a" data-role="listview">
			<?php
				/*this will get all of the projects*/
				$stage2Projects = ProjectModel::getPrevWidgetStage2Projects();

				/*this will iterate over the projects*/
				foreach($stage2Projects as $project) {?>
				<?php $jsonObj = array('id'=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme="a" style='background:url(<?php if ($project->getProfilePicture()) {echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
					 <a href="#">
					  	<h1><?php echo $project->getProjectTitle();?></h1>
					 	<div class="progress_status">Do</div>
					 	<div class="underlay"></div>	
					 </a>
					 <div class="extra_project_details">
					 	<h1>Read more...</h1>
					 	<p><?php echo $project->getProjectSummary();?></p>						 	
					 </div>		
				</li>
			<?php }?>
				
			</ul>
		</div>
	
		<!-- ALL STAGE 3 -->
		<div class="stage3_projects ui-screen-hidden" data-role="content">
			<ul class="preview_widget_listview" data-theme="a" data-role="listview">
			<?php
				/*this will get all of the projects*/
				$stage3Projects = ProjectModel::getPrevWidgetStage3Projects();

				/*this will iterate over the projects*/
				foreach($stage3Projects as $project) {?>
				<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme="a" style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src ;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
					 <a href="#">
					  	<h1><?php echo $project->getProjectTitle();?></h1>
					 	<div class="progress_status">Achieve</div>
					 	<div class="underlay"></div>	
					 </a>
					 <div class="extra_project_details">
					 	<h1>Read more...</h1>
					 	<p><?php echo $project->getProjectSummary();?></p>						 	
					 </div>		
				</li>
			<?php }?>
				
			</ul>
		</div>
	</div>
</div>
<!-- include this last, inits the listeners -->
<script type="text/javascript">
	PreviewWidget.init();	
</script>