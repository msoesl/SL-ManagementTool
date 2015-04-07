
<?php
if ($user != NULL) {
	$admin_privs = $user->getSystemPrivileges ();
}

?>
<div id="header_bar" class="full-wdth" data-role="header" data-theme="a"
	data-position="fixed">

	<div id='header_left' class="no-padding no margin inline">
		<table>
			<tr>
				<td><a href="javascript:void(0)"
					onclick="PageChanger.loadHomepage()"><img
						style='margin-top: 4px; padding-left: 0.5em; padding-right: 0.5em;'
						height='50' src='res/images/msoe-logo.png' /> </a></td>
				<td><a href="javascript:void(0)"
					onclick="PageChanger.loadHomepage()"><img
						style='margin-top: 4px; padding-left: 0.5em; padding-right: 0.5em;'
						height='50' src='res/images/logo-no_text.png' /> </a></td>
				<td style='padding-right: 0.5em;'>
					<h2 class="website-title no-margin" data-inline="true">
						<span>
							<!-- Don't worry, it's being done with CSS3 -->
						</span>
					</h2>
				</td>
				<td><a href="#projectMenu" data-rel="popup" data-role="button"
					data-inline="true" class="header-bar-nav-icon" data-icon="grid">Projects</a>
					<div data-role="popup" id="projectMenu" data-theme="a"
						data-inline="true">
						<ul data-role="listview" data-inset="true"
							style="min-width: 210px;" data-theme="a">
							<li data-role="divider" data-theme="b">View Projects</li><!-- TODO diff color -->
							<li onclick='PageChanger.loadAllProjectsView()'><a href="#">All</a></li>
							<li onclick='PageChanger.loadProblemSubmissionView()'><a href="#">Stage 1 - Observe</a></li>
							<li onclick='PageChanger.loadThinkStageProjectsView()'><a href="#">Stage 2 - Think</a></li>
							<li onclick='PageChanger.loadDoStageProjectsView()'><a href="#">Stage 3 - Do</a></li>
							<li onclick='PageChanger.loadAchieveStageProjectsView()'><a href="#">Stage 4 - Achieve</a></li>
							<li><a href="#" onclick='PageChanger.loadTrophyRoom()'>Trophy
									Room</a></li>
						</ul>
					</div></td>
				<td>
					<?php if($user != NULL){?><a href="#" class="header-bar-nav-icon"
					data-icon="plus" data-role="button" data-inline="true"
					onclick='PageChanger.loadProblemSubmissionView()'>Submit a Problem</a><?php }?>
					</td>
			</tr>
		</table>
	</div>
	<div id="header_right" class="float-right inline">
		<table class="float-right">
			<tr>
				<td>
				
				<?php include_once 'SearchBar.php';?>
				
					</td>
				<td><a href="#helpMenu" data-rel="popup" data-role="button"
					data-inline="true" data-icon="info">Learn More</a>
					<div data-role="popup" id="helpMenu" data-theme="a">
						<ul data-role="listview" data-inset="true"
							style="min-width: 210px;" data-theme="a">
							<li><a href="#" class="header-bar-nav-icon"
								onclick='PageChanger.loadLearnMorePage()'>About SMT</a></li>
							<li onclick='PageChanger.loadHelpView()'><a href="#">Got Questions?</a></li>
							<li onclick='PageChanger.loadCreditsView()'><a href="#">SMT Credits</a></li>
						</ul>
					</div></td>
					<?php
					if ($user != NULL) {
						if ($admin_privs->system_admin > 0) {
							?>
					
				<td><a href="#adminMenu" class="header-bar-nav-icon"
					data-rel="popup" data-role="button" data-inline="true"
					data-icon="gear">Admin</a>
					<div data-role="popup" id="adminMenu" data-theme="a">
						<ul data-role="listview" data-inset="true"
							style="min-width: 210px;" data-theme="a">
							<li data-role="divider"
								data-theme="b">Admin
								Panel</li> <!-- TODO diff color -->
							<?php if($admin_privs->system_configuration_permission>0){?>
							<?php } if($admin_privs->ban_users_permission>0){?>
							<li><a href="#" onclick="PageChanger.loadAdminBanView()">Suspend
									Users</a></li>
							<?php }?>
							<?php if ($user!=null && $admin_privs->system_admin > 0) {?>
							<li id='user-logout'><a href="javascript:void(0);"
								onclick='PageChanger.loadBannerManagementView()'>Frontpage Banner Management</a></li>
							<li id='user-logout'><a href="javascript:void(0);"
								onclick='PageChanger.loadPrevWidgetManagementView()'>Project Preview Management</a></li>
							<li><a href='#' onclick='PageChanger.loadHelpPageManagementView()'>Help Page Management</a></li>
							<?php }?>
							
							
						</ul>
					</div></td><?php }?>
				<td>
					<div id = 'profile-menu-container' style='position:relative'>
						<?php 
						$numNewFriendRequests = count($user->getNewFriendRequests());
						$newCollaborationInvites = ProjectModel::getNewCollaborationInvitesForAccount($user->getAccountId());
						$numNewCollaborationInvites = count($newCollaborationInvites);
						$numNewNotifications = $numNewFriendRequests + $numNewCollaborationInvites;
						$inviteIds = array();
						foreach($newCollaborationInvites as $invite){
							array_push($inviteIds, $invite->invite_permission_id);
						}
							if($numNewNotifications > 0){?>
							<div id="notification-flag">
								<p><?php echo $numNewNotifications;?>
							</div>
							<?php }?>
						<a href="#profileMenu" data-rel="popup" data-role="button"
						data-inline="true" data-icon="arrow-d"
						onclick='clearNotificationFlag(<?php echo json_encode($inviteIds);?>)'>Profile</a>
					</div>
					<div data-role="popup" id="profileMenu" data-theme="a">
						<ul data-role="listview" data-inset="true"
							style="min-width: 210px;" data-theme="a">
							<li data-theme="b">Hi <?php echo $user->getUsername()?></li>
							<li><a href="#"
								onclick="PageChanger.loadAccountSettingsView()">My Account</a></li>
							<li><a href="javascript:void(0);"
								onclick='PageChanger.loadProfileView({id: <?php echo $user->getAccountId() ?>})'>My
									Profile</a></li>
							<li><a href="#" onclick="PageChanger.loadPersonalProjectsView({<?php echo "id: ".$user->getAccountId();?>})">My Projects</a></li>
							<li><a id='profile-menu-notifications-item' href="#"
								onclick='PageChanger.loadFriendRequestView({id: <?php echo $user->getAccountId() ?>})'>My Notifications</a></li>
							<li id='user-logout'><a href="javascript:void(0);"
								onclick='new function() {logout();}'>Sign out</a></li>
						</ul>
					</div></td>
					<?php } else {?>
				<td><a class='logInPopup' href="#logInPopup" data-rel="popup"
					data-position-to="window" data-role="button" data-inline="true"
					data-theme="a" data-transition="pop" data-icon="arrow-d"
					data-iconpos="right">Sign in</a>
					<div data-role="popup" id="signInMenu" data-theme="a">
						<div data-role="popup" id="logInPopup" data-theme="a"
							data-overlay-theme="e" class="ui-corner-all"
							data-dismissible="false">
							<form data-ajax='false' id='login-form' method='POST'
								action='controllers/LoginController.php'>
								<div style="padding: 10px 20px;">
									<h3>Please sign in</h3>
									<label for="un" class="ui-hidden-accessible">Username:</label>
									<input type="text" name="user" id="un" value=""
										placeholder="username" data-theme="a"> <label for="pw"
										class="ui-hidden-accessible">Password:</label> <input
										type="password" name="pass" id="pw" value=""
										placeholder="password" data-theme="a">
									<div data-role="content" 
										class="ui-corner-bottom ui-content">
										<a href="#" data-role="button" data-inline="true"
											data-rel="back" data-theme="a">Cancel</a>
										<button type="submit" data-theme="a" data-icon="check"
											data-inline="true">Sign in</button>

									</div>
									<br> Forgot your password? <a href="javascript:void(0);"
										onclick='PageChanger.loadForgottenPasswordView()'>Help us help
										you</a> <br> Don't have an account? <a
										href="javascript:void(0);"
										onclick='PageChanger.loadaccountCreationView()'>Sign up</a>

								</div>
							</form>
						</div>
					</div></td>
					<?php }?>
			</tr>
		</table>
	</div>
</div>


<script type="text/javascript">
	var defendStatus = false;
    var form = $('#login-form');
    form.submit(function (ev) {
        overlay.show();
        $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize(),
            success: function (data) {
               var retVal = $.parseJSON(data);
               if (retVal.status == 'Success') {
                   	var url = (location.href).split('&ui-state=dialog');
                   	window.location.href = url[0];
                   	location.reload();
               } else if (retVal.status == 'Failed') {
					alert('Login Failed, please try again.');
               } else  if (retVal.status == 'Banned'){
					alert('Your user account has been suspended.');
               }else{                	 
                	$('#logInPopup' ).popup( "close" );
                   	PageChanger.loadSuspendedUserEmailView({'userid' : retVal.status});
               }
               overlay.hide();
            }
        });
        ev.preventDefault();
    });
	function logout() {
	  	$.post( "controllers/LogoutController.php").done(
	  		  	function(data) {
                   	var url = (location.href).split('&ui-state=dialog');
                   	window.location.href = url[0];
                   	location.reload();
		  		}
		  );
	}
	function clearNotificationFlag(inviteIds){
		$("#notification-flag").hide();
		var json = {
				"action":"markNotificationsAsOld",
				'inviteIds': inviteIds
				};
		//By: Josh Avery...
		//Currently, clearing notifications for all events will be done through the FriendshipController because it 
		//originally handles the command 'markRequestsAsOld. 
		$.post('controllers/FriendshipController.php', json)
			.done(function(data){
				console.log(data);  
				data = $.parseJSON(data);
				if(data.success = "Success"){
					$('#profile-menu-notifications-item').html("View notifications (" + data.notifications + ")");
				}	
		});
	}

	function clearFriendRequestNotifications(){

	}

</script>
