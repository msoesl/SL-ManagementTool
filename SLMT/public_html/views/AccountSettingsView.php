<?php
require_once('import/headimportviews.php');

session_start();
$user = null;
if(isset($_SESSION['user'])){
	$user = $_SESSION ['user'];

}

if (isset($user)){
	?>
<div class='simple-padding-small standard-border title divstyles'>
	<h3>
		General Account Management
	</h3>
	<hr>
	<div>
		<table id='permissions-table'>
			<tr>
				<td class="ownedprojecttd tdheader" style="text-align: center">Private Profile</td>
				<td class="ownedprojecttd tdheader" style="text-align: center">Email Notifications</td>
			</tr>
			<tr>
				<td>
					<div class='perm-checkbox'>
						<form id='flip-isPrivate-form'
							enctype="multipart/form-data" data-ajax='false' method='POST'
							action='controllers/AccountSettingsController.php'>
						<?php if ($user->isPrivate()){?>
							<input checked data-theme="a" type="checkbox" onclick="this.form.submit();"
							name="is-private" id="is-private" class="custom" />
						<?php } else {?>
							<input data-theme="a" type="checkbox" onclick="this.form.submit();"
							name="is-private" id="is-private" class="custom" />
						<?php }?>
						<input type="hidden" name="userId" value="<?php echo $user->getAccountId()?>">
						<input type="hidden" name="actionType" value="flipIsPrivate">
						<label for="is-private">Private</label>
					</form>
					</div>
				</td>
				<td>
					<div class='perm-checkbox'>
					<form id='flip-recieveEmails-form'
							enctype="multipart/form-data" data-ajax='false' method='POST'
							action='controllers/AccountSettingsController.php'>
						<?php if ($user->canRecieveEmails()){?>
							<input checked data-theme="a" type="checkbox" onclick="this.form.submit();"
							name="can-recieve-emails" id="can-recieve-emails" class="custom" />
						<?php } else {?>
							<input data-theme="a" type="checkbox" onclick="this.form.submit();"
							name="can-recieve-emails" id="can-recieve-emails" class="custom" />
						<?php }?>
						<input type="hidden" name="userId" value="<?php echo $user->getAccountId()?>">
						<input type="hidden" name="actionType" value="flipRecieveEmails">
						<label for="can-recieve-emails">Recieve Email Notifications</label>
					</form>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>
	
<div class='simple-padding-small standard-border title divstyles'>
	<h3>
		Owned Project By:
		<?php echo $user->getUsername();?>
	</h3>
	<hr>
	<div>
		<table>
			<tr>
				<td class="ownedprojecttd tdheader">Project</td>
				<td class="ownedprojecttd tdheader">Stage</td>
				<td class="ownedprojecttd tdheader">Collaborators</td>
			</tr>
			<tr>
			<?php

			// Get all project for the logged in user.
			$projects = ProjectModel::getAllProjectsForAccountCollaboration($user->getAccountId());


			foreach ($projects as $project){// project is a model of Project_has_Account

				$projectItself = new ProjectModel($project->Project_id);

				// Only Display projects that are owned by the user.
				if(isset($projectItself) && $project->Account_Title_ID == 1){// Project Owner
					?>
				<td class="ownedprojecttd tdelements"><?php  echo $projectItself->getProjectTitle();?>
				</td>
				<td class="ownedprojecttd tdelements"><?php 

				$currentStage = $projectItself->getStage();
				if($currentStage==1){
					echo 'Think';
				}else if($currentStage==2){
					echo 'Do';
				}else{
					echo 'Achieve';
				}
				?>
				</td>


				<td class="ownedprojecttd tdelements">
					<form id='remove-Collaborator-form' enctype="multipart/form-data"
						data-ajax='false' method='POST'
						action='controllers/AccountSettingsController.php'
						onsubmit="return confirm('Do you really want to remove this user?');">


						<select type="text" name="userId" id='collaborator-remove'
							data-theme"d">
							<?php
							$accountsForAproject = ProjectModel::getAllAccountsForProjectCollaboration($projectItself->getProjectId());

							$projectHasAccountsOtherThanOwner = FALSE;
							foreach($accountsForAproject as $acc){// acc is a model of Project_has_Account

								if(isset($acc)){

									if( ($acc->Account_id != $user->getAccountId())){// Not the project owner
											
											
										$projectPrivileges = new ProjectPrivilegesModel($acc->Project_Privileges_id);
											
										if($projectPrivileges->getCollaboratorPermission() == 1){// Mean is a collaborator

											$simpleAcc = new SimpleAccountModel($acc->Account_id);

											?>

							<option value="<?php echo $simpleAcc->getAccountId();?>">
							<?php echo $simpleAcc->getUsername();?>
							</option>

							<?php
							$projectHasAccountsOtherThanOwner =TRUE;

										}
									}

							 }
							}
							$none = FALSE;
							if(!$projectHasAccountsOtherThanOwner ){// Add none if there is no one collaborating on a project?>
							<option value="None">
							<?php echo 'None';?>

							</option>
							<?php $none = TRUE;}?>
						</select>
						<?php if(!$none){?>
						<input type="submit" value="Remove" data-theme"d"
							data-mini="true" data-inline="true"> <input type="hidden"
							name="projectId"
							value="<?php echo $projectItself->getProjectId() ?>"> <input
							type="hidden" name="actionType" value="removeCollaborator">
							<?php } else{ ?>
						<input type="submit" value="Remove" data-theme"d"
							data-mini="true" data-inline="true" disabled>

							<?php }?>
					</form>
				</td>
			</tr>
			<?php }
			}?>
		</table>
	</div>

</div>
<div class='simple-padding-small standard-border title divstyles'>
	<h3>
		Collaborated Project By:
		<?php echo $user->getUsername();?>
	</h3>
	<hr>



	<div>
		<table>
			<tr>
				<td class="collaboratedprojecttd tdheader">Project</td>
				<td class="collaboratedprojecttd tdheader">Stage</td>
				<td class="collaboratedprojecttd tdheader">Status</td>
				<td class="collaboratedprojecttd tdheader">Owner</td>
			</tr>

			<?php

			$projects = ProjectModel::getAllProjectsForAccountCollaboration($user->getAccountId());


			foreach ($projects as $project){// project is a model of Project_has_Account
					


				$projectItself = new ProjectModel($project->Project_id);

				// Only Display projects that are NOT owned by the logged in user and he is a collaborator on them.
				if(isset($projectItself) && $project->Account_Title_ID == 2){
					?>

			<tr>
				<td class="collaboratedprojecttd tdelements"><?php echo $projectItself->getProjectTitle(); ?>
				</td>
				<td class="collaboratedprojecttd tdelements"><?php 
				$currentStage = $projectItself->getStage();
				if($currentStage==1){
					echo 'Think';
				}else if($currentStage==2){
					echo 'Do';
				}else{
					echo 'Achieve';
				}
				?></td>
				<td class="collaboratedprojecttd tdelements">
					<form id='unactivate-Collaborator-form'
						enctype="multipart/form-data" data-ajax='false' method='POST'
						action='controllers/AccountSettingsController.php'
						onsubmit="return confirm('Do you really want to remove yourself from this project?');">
						<input type="submit" value="Unactiviate" data-theme"d"
							data-inline="true" data-mini="true"> <input type="hidden"
							name="projectId"
							value="<?php echo $projectItself->getProjectId();?>"> <input
							type="hidden" name="userId"
							value="<?php echo $user->getAccountId()?>"> <input type="hidden"
							name="actionType" value="Remove Me">
					</form>
				
				<td class="collaboratedprojecttd tdelements"><?php echo $projectItself->getProjectOwner()->getUsername(); ?>
				</td>
			</tr>
			<?php 		}
			}?>
		</table>
	</div>

</div>

<div class='simple-padding-small standard-border title divstyles'>
	<h3>Pending Collaborative Requests</h3>
	<hr>
	<div>
		<table>
			<tr>
				<td class="collaboratedprojecttd tdheader">Project</td>
				<td class="collaboratedprojecttd tdheader">Stage</td>
				<td class="collaboratedprojecttd tdheader">Reply</td>
				<td class="collaboratedprojecttd tdheader">Requester</td>
			</tr>
			<?php

			$projects = ProjectModel::getAllProjectsForAccountCollaboration($user->getAccountId());

			foreach ($projects as $project){
					

				$projectItself = new ProjectModel($project->Project_id);

				// Only Display projects that are owned by the logged in user.
				if(isset($projectItself) && $project->Account_Title_ID == 1){

					$accountsForAproject = ProjectModel::getAllAccountsForProjectCollaboration($projectItself->getProjectId());


					foreach($accountsForAproject as $acc){

						if(isset($acc)){
							if( ($acc->Account_id != $user->getAccountId())){// Not the project owner
									
									
								$projectPrivileges = new ProjectPrivilegesModel($acc->Project_Privileges_id);

								if($projectPrivileges->getCollaboratorPermission() == 0){// Means requesting a collaborator

									$simpleAcc = new SimpleAccountModel($acc->Account_id);
									?>

			<tr>
				<td class="collaboratedprojecttd tdelements"><?php  echo $projectItself->getProjectTitle();?>
				</td>
				<td class="collaboratedprojecttd tdelements"><?php
				$currentStage = $projectItself->getStage();
				if($currentStage==1){
					echo 'Think';
				}else if($currentStage==2){
					echo 'Do';
				}else{
					echo 'Achieve';
				}
				?>
				</td>

				<td class="collaboratedprojecttd tdelements">
				<div>
				<div>
					<form id='Accept-Collaborator-form' enctype="multipart/form-data"
						data-ajax='false' method='POST'
						action='controllers/AccountSettingsController.php' class="accept-reject-forms"
						onsubmit="return confirm('Do you really want to accept this user?');">
						<input type="submit" value="Accept" data-theme"d"
							data-inline="true" data-mini="true"> <input type="hidden"
							name="projectId"
							value="<?php echo $projectItself->getProjectId();?>"> <input
							type="hidden" name="userId"
							value="<?php echo $simpleAcc->getAccountId()?>"> <input
							type="hidden" name="actionType" value="acceptRequest">
					</form>
					</div>
					<div>
					<form id='Reject-Collaborator-form' enctype="multipart/form-data"
						data-ajax='false' method='POST'
						action='controllers/AccountSettingsController.php'
						onsubmit="return confirm('Do you really want to reject this user?');">
						<input type="submit" value="Reject" data-theme"d"
							data-inline="true" data-mini="true" > <input type="hidden"
							name="projectId"
							value="<?php echo $projectItself->getProjectId();?>"> <input
							type="hidden" name="userId"
							value="<?php echo $simpleAcc->getAccountId()?>"> <input
							type="hidden" name="actionType" value="rejectRequest">
					</form>
					</div>
					</div>
				</td>
				<td class="collaboratedprojecttd tdelements"><?php echo$simpleAcc->getUsername();?>
				</td>
			</tr>
			<?php		}
							}
						}
					}
				}
			}?>
		</table>
	</div>
</div>
			<?php }else{// if not registered user.

				echo "<script type=\"text/javascript\">
			
						PageChanger.loadMessageView({'messageType' : 'not_authorized'});
           			 </script>";
			}?>

<!-- Collaboration confirmation message Dialog -->
<div style='display: none'>
	<div data-role="popup" id="Collaboration-confirmation-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme"d"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Collaboration</h1>
		</div>

		<div class='simple-padding-medium' data-theme"d">
			<p>Your request has been completed.</p>
		</div>
	</div>
</div>
<!-- Collaboration error message Dialog -->
<div style='display: none'>
	<div data-role="popup" id="Collaboration-error-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme"d"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Collaboration</h1>
		</div>

		<div class='simple-padding-medium' data-theme"d">
			<p>Your request has Not been completed.</p>
		</div>
	</div>
</div>


<div id='status'>
<?php getStatus();?>
</div>

<?php
function getStatus(){

	if(isset($_GET['status'])){// if there is an error
		if($_GET['status']=='1'){

			$_GET['status']= null;
			echo "<script type=\"text/javascript\">
			$('#Collaboration-error-message').popup();
            	$('#Collaboration-error-message').popup(\"open\");
          	if(typeof window.history.pushState == 'function') {
        		window.history.pushState({}, \"Hide\", \"#AccountSettingsView.php\");
    		}
            </script>";
		}else{// if request is sccessful

			$_GET['status']= null;
			echo "<script type=\"text/javascript\">
			$('#Collaboration-confirmation-message').popup();
            	$('#Collaboration-confirmation-message').popup(\"open\");
            	   
    
				if(typeof window.history.pushState == 'function') {
        			window.history.pushState({}, \"Hide\", \"#AccountSettingsView.php\");
   				}
            </script>";
		}





	}
}
?>
