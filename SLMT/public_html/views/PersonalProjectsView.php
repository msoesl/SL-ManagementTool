<?php
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}else{?>
	<script type="text/javascript">
		PageChanger.loadMessageView({'messageType' : 'private_profile_access_denied'});
	</script>	
<?php }

if(isset($_GET['id'])){
	$accountId = $_GET['id'];
	$userProfile = AccountModel::getAccountById($accountId);
	if($userProfile){
		$isPrivate = $userProfile->profile->is_private;
		if($accountId == $user->getAccountId() || !$isPrivate || ($isPrivate && $user->hasFriend($accountId))){
			$firstname = $userProfile->profile->firstname;
			$lastname = $userProfile->profile->lastname;
			
			//The number of projects in the achieve stage
			$numProjects = ProjectModel::getCountOfActiveProjectsForAccount($accountId);
			
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
			$activeProjects = ProjectModel::getActiveProjectsForAccount($accountId, $currentPageNum, 6);
		} else{?>
			<script type="text/javascript">
				PageChanger.loadMessageView({'messageType' : 'private_profile_access_denied'});
			</script><?php 	
		}
	}else{?>
		<script type="text/javascript">
			PageChanger.loadHomepage();
		</script><?php 
	}
}else{?>
	<script type="text/javascript">
		PageChanger.loadHomepage();
	</script><?php 
}
?>
<div class = 'filter-page'>
	<div class='learn-more-title filter-title'>
		<h1><a href="#" onclick="PageChanger.loadProfileView({id: '<?php echo $accountId;?>'})"><?php echo $firstname.' '.$lastname?>'s</a> Projects</h1>
		<h2>which are currently active</h2>	
		<hr>
		<p>
			<?php if(count($activeProjects) > 0){?>
			<i>Check out all the <b>active</b> projects <?php echo $firstname?> is involved in! You can also view projects 
			<?php }else{?>
			<i><?php echo $firstname;?> is not involved in any projects that are active. You can see if there are projects
			<?php }?>
									<?php echo $firstname?> was a part of which have either been
									<a href='#' onclick='PageChanger.loadPersonalCompletedProjectsView({id: <?php echo $accountId;?>})'>completed</a>
									 or 
									 <a href='#' onclick="PageChanger.loadPersonalDiscontinuedProjectsView({id: <?php echo $accountId;?>})">discontinued</a>.</i></p>
	</div>
	<div class='projects-viewer-container'>
		<ul class="preview_widget_listview" data-theme='a' data-role="listview">
			<?php
				/*this will iterate over the projects*/
				foreach($activeProjects as $project) {?>
				<?php $jsonObj = array("id"=>$project->getProjectId()); ?>
				<li id = 'large_window_list_item-<?php echo $project->getProjectId()?>' class='large_window_list_item' data-role='listitem' data-theme='f' style='background:url(<?php if($project->getProfilePicture()) { echo $project->getProfilePicture()->src;}?>); background-size:100%;' onclick='PageChanger.loadProjectView(<?php echo json_encode($jsonObj)?>)'>
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
	<p style='margin-top:20px;text-align:center'>Page <?php echo $currentPageNum;?> of <?php echo $numPages;?></p>
	<div class='nav-row'>
		<span class='nav-options nav-options-large-screen'>
		<?php if($numProjects > 6){?>
			<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'1', 'id': '<?php echo $accountId;?>'})">First</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $currentPageNum-1?>', 'id': '<?php echo $accountId;?>'})">Previous</a>
			<div class='big-spacing' style='display:inline-block;width:50px'></div>
			<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $currentPageNum+1?>', 'id': '<?php echo $accountId;?>'})">Next</a>
			<div class='small-spacing' style='display:inline-block;width:20px'></div>
			<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $numPages?>', 'id': '<?php echo $accountId;?>'})">Last</a>	
		</span>
		<span class='nav-options nav-options-small-screen'>
			<div>
				<a href="#" class='big-nav-button previous' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $currentPageNum-1?>', 'id': '<?php echo $accountId;?>'})">Previous</a>
				<div class='small-spacing' style='display:inline-block;width:20px'></div>
				<a href="#" class='big-nav-button next' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $currentPageNum+1?>', 'id': '<?php echo $accountId;?>'})">Next</a>
			</div>
			<div>
				<a href="#" class='small-nav-button first' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'1', 'id': '<?php echo $accountId;?>'})">First</a>
				<div class='big-spacing' style='display:inline-block;width:50px'></div>
				<a href="#" class='small-nav-button last' data-role='button' onclick="PageChanger.loadPersonalProjectsView({'page' :'<?php echo $numPages?>', 'id': '<?php echo $accountId;?>'})">Last</a>				
			</div>
		<?php }?>
		</span>
	</div>
</div>

<script type="text/javascript">

	function openExtraProjects(type){		
		TINY.box.show({url:'views/PersonalProjectsViewExtras.php?id=<?php echo $_GET['id'];?>&type='+ type});
		$(document).trigger('create');	}
	
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