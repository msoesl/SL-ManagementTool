<?php
	require_once ('import/headimportviews.php');
	$trophyProjects = ProjectModel::getAllCompletedProjects();
?>

<script type="text/javascript" src="libs/plugins/Snow/snow.js"></script>
<div class = 'filter-page completed_projects'>
	<div class='learn-more-title'>
		<h1 onclick="$('#page-content').snow(); document.getElementById('cheer').play();">The <span class = 'completed-color'>Trophy</span> Room</h1>
		<p><i>Check out a few of our past achievements. These projects have been completed by effective servant-leaders.</i></p>
		<hr>
	</div>
	<ul class="preview_widget_listview" data-theme="a" data-role="listview">
			<?php
				/*this will iterate over the projects*/
				foreach($trophyProjects as $project) {?>
				<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme="a" style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
					 <a href="#">
						<h1><?php echo $project->getProjectTitle();?></h1>
					 	<div class="underlay"></div>			 	
					 </a>
					 <div class="extra_project_details">
					 	<h1>Completed</h1>
					 	<p><?php echo $project->getProjectSummary();?></p>						 	
					 </div>	
				</li>
			<?php }?>
				
	</ul>
</div>

<audio id="cheer" src="res\sound\cheer.wav"></audio>

<!-- include this last, inits the listeners -->
<script type="text/javascript">
	PreviewWidget.init();	
</script>