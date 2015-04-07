<?php
require_once ('import/headimportviews.php');

session_start();
$user = $_SESSION['user'];
if($user != null && $user->getAccountId() == $_GET['id']){





?>
<div id="friend-requests-view" class='full-width'>
	<div id='box-div' class='simple-padding-small standard-border title width-80 center-in-container'>
		<h3>Friend requests</h3>
		<hr>
		<div class='message-container center-in-container'>
			<?php
			if(count($user->getFriendRequests()) == 0){?>
				<h3>No pending friend requests</h3>			

			<?php }	
			else{?>
				<ul data-role='listview' data-theme="a" data-inset='true'>
				<?php 		
					foreach ($user->getFriendRequests() as $request){
					$requestor = new SimpleAccountModel($request->friend_requestor);?>
						
						
						<li data-role='listitem' data-theme="a" id='request-from-<?php echo $requestor->getAccountId();?>'><div id='friend-request'>
							<div id='requestorId-".$request->getAccountId()."' class='friend-request-info'>
								<a href='#' onclick='PageChanger.loadProfileView({id:<?php echo $requestor->getAccountId();?>})'><img src='<?php  echo $requestor->getProfilePicUrl();?>'/></a>
								<div class='personal-info'>
									<a href='#' onclick='PageChanger.loadProfileView({id:<?php echo $requestor->getAccountId();?>})'><h2><?php echo $requestor->getFirstName()." ".$requestor->getLastName();?></h2></a>
											<h3 class='friend-request-location'><?php echo $requestor->getCity().", ".$requestor->getState();?></h3>
								</div>
								</div>
								<div class='friend-request-options'>
									<a data-role='button' data-mini='true' data-theme="a" onclick='acceptFriendRequest(<?php echo $requestor->getAccountId();?>)'>Accept</a>
									<a data-role='button' data-mini='true' data-theme="a" onclick='ignoreFriendRequest(<?php echo $requestor->getAccountId();?>)'>Ignore</a>
								</div>
							</div>
						</li>
					<?php }?>				
				</ul>
			<?php }?>
		</div>
	</div>
	
</div>
<div id="collaboration-requests-view" class='full-width'>
	<div id='box-div' class='simple-padding-small standard-border title width-80 center-in-container'>
		<h3>Project collaboration invitations</h3>
		<hr>
		<div class='message-container center-in-container'>

			<!-- Collaboration Invites  -->
			<!-- 1) Query Project_has_Account for rows where Account_id = userId and Account_Title_ID = 2. This gets all collaboratorships
				 2) Get the Project_Privileges_ids from those rows, and then get all the invite_permission_ids.
				 3) If 'invite_status' is 0, show it as an invite -->
		<?php 
		$collaborationInvites = ProjectModel::getAllCollaborationInvitesForAccount($_GET['id']);
		if(count($collaborationInvites) == 0){?>
			<h3>No pending collaboration invitations</h3>			
		<?php }	
		else{?>
		<ul data-role='listview' data-theme="a" data-inset='true'>	
					<?php 
					foreach($collaborationInvites as $invite){
						 $project = new ProjectModel($invite->Project_id);
						 $projectOwnerProfile = $project->getProjectOwner();
					?>
					<li data-role='listitem' data-theme="a" id='invite-for-project-<?php echo $project->getProjectId();?>'><div id='friend-request'>
						<div id='requestorId-".$request->getAccountId()."' class='friend-request-info'>
							<a href='#' onclick='PageChanger.loadProjectView({id:<?php echo $project->getProjectId();?>})'><img src='<?php if($project->getProfilePicture() != null) echo $project->getProfilePicture()->src ;?>'/></a>
							<div class='personal-info'>
								<a href='#' onclick='PageChanger.loadProjectView({id:<?php echo $project->getProjectId();?>})'><h2><?php echo $project->getProjectTitle();?>
								</h2></a>
								<h3 class='friend-request-location'>Invited by &nbsp;<a href='#' onclick='PageChanger.loadProfileView({id:<?php echo $projectOwnerProfile->getAccountId();?>})'><?php echo $projectOwnerProfile->getFirstName().' '.$projectOwnerProfile->getLastName();?></a></h3>
							</div>
							</div>
							<div class='friend-request-options'>
								<a data-role='button' data-mini='true' data-theme="a" onclick='acceptCollaborationInvite(<?php echo $invite->Project_Privileges_id.', '.$project->getProjectId();?>)'>Accept</a>
								<a data-role='button' data-mini='true' data-theme="a" onclick='ignoreCollaborationInvite(<?php echo $invite->Project_Privileges_id.', '.$project->getProjectId().', '.$user->getAccountId();?>)'>Ignore</a>
							</div>
						</div>
					</li>
					<?php }?>
				<?php }?>
			</ul>
		</div>
	</div>
	
</div>
<?php 
}else{?>
	<script type="text/javascript">PageChanger.loadMessageView({'messageType' : 'not_authorized'});</script>
<?php }?>

<script type='text/javascript'>

	function acceptFriendRequest(userId){
		var json = {"uid":userId,"action":"confirmAdd"};
		$.post('controllers/FriendshipController.php', json)
			.done(function(data){
				data = $.parseJSON(data);
				if(data.status){
					var name = $('#request-from-' + userId + " h2").html();
					$('#request-from-' + userId)
						.html("You are now friends with <a href='' onclick='PageChanger.loadProfileView({id:" + userId + "})'>" + name + "</a>");
								
				} else { 
					alert("Failed to send request");
					location.reload();
				}
			});
	}

	function ignoreFriendRequest(userId){
		var json = {"uid":userId,"action":"ignoreAdd"};
		$.post('controllers/FriendshipController.php', json)
			.done(function(data){
				data = $.parseJSON(data);
				if(data.status){
					var name = $('#request-from-' + userId + " h2").html();
					$('#request-from-' + userId)
					.html("You have ignored a request from <a href='' onclick='PageChanger.loadProfileView({id:" + userId + "})'>" + name + "</a>");
				} else { 
					alert("Failed to send request");
					location.reload();
				}
			});
	}

	function acceptCollaborationInvite(projectPrivilegesId, projectId){
		var json = {"ppid":projectPrivilegesId,"action":"acceptCollaborationInvite"};
		$.post('controllers/CollaborationInviteResponseController.php', json)
			.done(function(data){
				data = $.parseJSON(data);
				if(data.status){
					var name = $('#invite-for-project-' + projectId + " h2").html();
					$('#invite-for-project-' + projectId)
						.html("You are now a collaborator with <a href='' onclick='PageChanger.loadProjectView({id:" + projectId + "})'>" + name + "</a>");
				} else { 
					alert("Failed to send request");
					location.reload();
				}
			});
	}

	function ignoreCollaborationInvite(projectPrivilegesId, projectId, inviteeId){
		var json = {"ppid":projectPrivilegesId,"inviteeId":inviteeId,"projectId":projectId,"action":"ignoreCollaborationInvite"};
		$.post('controllers/CollaborationInviteResponseController.php', json)
			.done(function(data){
				data = $.parseJSON(data);
				if(data.status == "IgnoreSuccess"){
					var name = $('#invite-for-project-' + projectId + " h2").html();
					$('#invite-for-project-' + projectId)
					.html("You have ignored an invite to collaborate on <a href='' onclick='PageChanger.loadProjectView({id:" + projectId + "})'>" + name + "</a>");
				} else { 
					alert("Failed to send request");
					location.reload();
				}
			});
	}
</script>
