<?php if($project->isProjectDiscontinued()){ ?>
	<li class='no-border-radius-top-bottom-right' data-theme="b"
			style="overflow: wrap">This project is no longer active.
			</li>
<?php } else if(!isset($user)){?>
				<li class='no-border-radius-top-bottom-right' data-theme="b"
			style="overflow: wrap">Sign in or sign up to get more involved with
			this project!
			</li>
<?php } else if(isset ( $user ) && ($user->getAccountId () != $project->getProjectOwner ()->getAccountId ())){?>
				
		
		<li class='side-panel-item no-border-radius-top-bottom-right'
			data-theme="a"><a href="#">Propose a Solution</a></li>
		<li class='side-panel-item no-border-radius-top-bottom-right'
			data-theme="a"><a href="#">Message</a></li>
		<li onclick="requestCollaboration()"><a class="" title="Request"
			data-icon="edit" data-rel="popup" data-position-to="window"
			aria-haspopup="true" aria-owns="#requestCollaborationPopupDialog">
				Collaborate</a></li>
				<?php
} else if (isset ( $user ) && ($user->getAccountId () == $project->getProjectOwner ()->getAccountId ())) {
	
	?>
					<li onclick="PageChanger.loadInviteUserView()"><a>Invite
				Collaborators</a></li>
		<li onclick="PageChanger.loadAccountSettingsView()"><a>Manage
				Collaboration</a></li>
				<?php }?>
		
				<?php if(isset($canEdit) && $canEdit){?>
					<li
			onclick="PageChanger.loadProjectPermissionsView({projectid:<?php echo $_GET['id'];?>})"><a>Manage
				Permissions</a></li>
		<li><a class="edit-button" href="#editProjectDetailsPopupDialog"
			title="Edit" data-icon="edit" data-rel="popup"
			data-position-to="window" aria-haspopup="true"
			aria-owns="#editProjectDetailsPopupDialog"> Edit Project Details </a>
		</li>
<!-- 		<li><a class="edit-button" href="#ProjectBannerManagementView.php?id=<?php echo $_GET['id']?>" title="Edit"
			data-icon="edit" data-rel="popup" data-position-to="window"
			data-transition="pop" data-corners="true" data-shadow="true"
			data-iconshadow="true" data-wrapperels="span" data-theme"d">
			Configure Pictures</a>
		</li> -->
		<li onclick="PageChanger.loadProjectBannerManagementView({id:<?php echo $_GET['id'];?>})"><a>Configure Pictures</a></li>

		<!-- Page Editing  -->
		<div style='display: none'>
			<div data-role="popup" id="editProjectDetailsPopupDialog">
				<div data-theme="a" data-role="header" title="Edit" data-theme="a"
					class="ui-corner-top ui-header ui-bar-a" role="banner">
					<h1 class="ui-title" role="heading" aria-level="1">Project Detail
						Editor</h1>
				</div>
				<div data-role='collapsible-set'>
					<div data-theme="a" data-role="collapsible"
						data-collapsed-icon="arrow-r" and data-expanded-icon="arrow-d">
						<h3>Project Name</h3>
						<form id='project-name-form'  data-ajax="false"  method="POST"
							, action="controllers/SaveProjectController.php?id=<?php echo $project->getProjectId()?>">
							<input type="text" name="project_name" id='project_name'
								value="<?php echo $project->getProjectTitle();?>">
							<div class='ui-grid-a'>
								<div class='ui-block-a'></div>
								<div class='ui-block-b'>
									<input type="submit" data-theme"d" name="submit"
										value="Submit">
								</div>
							</div>
						</form>
					</div>
					<div data-theme="a" data-role="collapsible"
						data-collapsed-icon="arrow-r" and data-expanded-icon="arrow-d">
						<h3>Project Summary</h3>
						<form id='project-description-form'  data-ajax="false"
							 method="POST"
							 action="controllers/SaveProjectController.php?id=<?php echo $project->getProjectId()?>">
							<textarea type="textarea" name="project_description"
								id='project_description'><?php echo $project->getProjectSummary();?></textarea>
							<div class='ui-grid-a'>
								<div class='ui-block-a'></div>
								<div class='ui-block-b'>
									<input type="submit" data-theme"d" name="submit"
										value="Submit">
								</div>
							</div>
						</form>
					</div>
					<div data-theme="a" data-role="collapsible"
						data-collapsed-icon="arrow-r" and data-expanded-icon="arrow-d">
						<h3>Project Stage</h3>
						<form method='POST' data-theme"d" data-ajax = 'false'
							action='controllers/StageCompletionController.php'
							id='project-stage-form'>
							<fieldset data-role="controlgroup">
									<legend>Choose the project stage:</legend>
						         	<input type="radio" name="radio-choice-1" data-theme="e" id="radio-choice-1" value="think" <?php echo ($project->getStage()==1)?'checked="checked"':'' ?>/>
						         	<label for="radio-choice-1">Think</label>
						
						         	<input type="radio" name="radio-choice-1" data-theme="f" id="radio-choice-2" value="do"  <?php echo ($project->getStage()==2)?'checked="checked"':'' ?>/>
						         	<label for="radio-choice-2">Do</label>
						
						         	<input type="radio" name="radio-choice-1" data-theme="g" id="radio-choice-3" value="achieve"  <?php echo ($project->getStage()==3)?'checked="checked"':'' ?>/>
						         	<label for="radio-choice-3">Achieve</label>
						
						         	<input type="radio" name="radio-choice-1" data-theme="b" id="radio-choice-4" value="complete" <?php echo ($project->getStage3Progress()==100)?'checked="checked"':'' ?> />
						         	<label for="radio-choice-4">Complete</label>
								<input type='hidden' name = 'pid' value = '<?php echo $project->getProjectId();?>'/>
							</fieldset>
							<div class='ui-grid-a'>
								<div class='ui-block-a'></div>
								<div class='ui-block-b'>
									<input type="submit" data-theme"d" name="submit"
										value="Submit">
								</div>
							</div>
						</form>
					</div>
					<div data-theme="a" data-role="collapsible"
						data-collapsed-icon="arrow-r" and data-expanded-icon="arrow-d">
						<h3>Discontinue Project</h3>
						<form id='project-discontinuation-form'  data-ajax="false" onsubmit='return validateSubmission();'
							 method="POST" data-ajax = 'false'
							 action="controllers/StageCompletionController.php">
							<fieldset data-role="controlgroup">
								<textarea type="textarea" name="project-discontuation-description" 
									id='project-discontuation-description' placeholder='Provide a short reason why this project is being discontinued...'></textarea>
								<input type='hidden' name = 'pid' value = '<?php echo $project->getProjectId();?>'/>
							</fieldset>
							<div class='ui-grid-a'>
								<div class='ui-block-a'></div>
								<div class='ui-block-b'>
									<input type="submit" data-theme"d" name="submit"
										value="Submit">
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>


<?php }?>