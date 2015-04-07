<?php
require_once ('import/headimportviews.php');

session_start ();

if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
	$sysPrivs = $user->getSystemPrivileges ();
	if ($sysPrivs->ban_users_permission > 0) {
		?>
<div class='general-page'>
	<div class='ui-grid-b'>
		<div id='user-search-view' class='ui-block-a' style = 'width:48%; min-height:500px;'>
			<div>
				<h1>Suspend Users</h1>
				<hr>
			</div>
			<h3 class='heading ban-text'>Search for a user to suspend...</h3>
			<ul data-theme="a" data-role="listview" data-filter="true"
				data-filter-reveal="true"
				data-filter-placeholder="Search for a specific user..."
				data-inset="true">
				<?php
		$profiles = ORM::for_table ( 'account' )->select ( 'account.*' )->select ( 'p.firstname' )->select ( 'p.lastname' )->inner_join ( 'profile', 'p.id=Profile_id', 'p' )->where ( 'is_banned', '0' )->find_many ();
		foreach ( $profiles as $profile ) {
			
			
			?>
			
				<li onClick='banUser(<?php echo $profile->id;?>, "<?php echo $profile->email_address; ?>")'><?php echo $profile->firstname.' '.$profile->lastname?></li>
				<?php }?>
			</ul>
			<div>
				&nbsp;
				<h3 class='heading ban-text'>... or ban a user that's been reported 5 or more times.</h3>
				<ul data-theme="a" data-role="listview">
				<?php
		
		$profiles = ORM::for_table ( 'account' )->select ( 'account.*' )->select ( 'p.firstname' )->select ( 'p.lastname' )->inner_join ( 'profile', 'p.id=Profile_id', 'p' )->where ( 'is_banned', '0' )->where_gt ( 'reported', 4 )->find_many ();
		?>
				<?php
		$profilesExist = false;
		foreach ( $profiles as $profile ) {
			$profilesExist = true;
			?>
				<li onClick='banUser(<?php echo $profile->id;?>, "<?php echo $profile->email_address; ?>")'><?php echo $profile->firstname.' '.$profile->lastname?> (<?php echo $profile->reported;?>)</li>
				<?php }?>
				<?php if (!$profilesExist) {?>
				<li>No accounts have been reported 5 or more times.</li>
				<?php }?>
			</ul>
			</div>
		</div>
		<div class='ui-block-b' style = 'width:48%; min-height:500px; padding-left:1%; padding-top:1em; border-left:1px solid black'>
			<div>
				<h1>Unsuspend Users</h1>
				<hr>
			</div>
			<h3 class='heading ban-text'>Click to unsuspend a user from the system.</h3>
			<ul data-theme="a" data-role="listview">
				<?php
		$profiles = ORM::for_table ( 'account' )->select ( 'account.*' )->select ( 'p.firstname' )->select ( 'p.lastname' )->inner_join ( 'profile', 'p.id=Profile_id', 'p' )->where ( 'is_banned', '1' )->find_many ();
		?>
				<?php
		$profilesExist = false;
		foreach ( $profiles as $profile ) {
			$profilesExist = true;
			?>
							<li onClick='unbanUser(<?php echo $profile->id;?>)'><?php echo $profile->firstname.' '.$profile->lastname?></li>
							<?php }?>
							<?php if (!$profilesExist) {?>
							<li>No accounts have been suspended.</li>
				<?php }?>
			</ul>
		</div>
	</div>
</div>
<!-- Emailing Suspended Dialog -->
<div style='display: none'>
	
	<div data-role="popup" id="email-ban-message"
		data-dismissible="false">
		<form data-ajax='false' id='email-suspended-user'
	action='controllers/EmailBannedUser.php' method='POST'
	enctype="multipart/form-data" >
		<div data-role="header" title="Email" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Send Email to Suspended User</h1>
		</div>
		<div class='simple-padding-medium' data-theme="a">
			<p> User has been suspended successfully. The user must be informed.</p>
			<input type="email"	data-theme="a" name="email" id='email' value="">
			<textarea type="textarea" name="reasoning" id='reason'  data-theme="a">Why suspended?</textarea>
			<input type="submit" value="Send" data-theme="a">
		</div>
		</form>
	</div>
	
</div>
<script type="text/javascript">
	function banUser(userId, email) {
		if (confirm('Are you sure you want to suspended this user?')) {
			$.post('controllers/UserBanController.php', {uid:userId})
				.done(function(data){
					data = $.parseJSON(data);
					if (data.success) {
						document.getElementById('email').value = email;
						$('#email-ban-message').popup("open");
					} else {
						alert(data.failure);
						PageChanger.loadHomepage();
					}
				});
			
		} else {
			alert('User was not suspended');
		}
	}
	function unbanUser(userId) {
		if (confirm('Are you sure you want to unsuspend this user?')) {
			$.post('controllers/UnbanController.php', {uid:userId})
			.done(function(data){
				data = $.parseJSON(data);
				if (data.success) {
					alert(data.success);
					location.reload();
				} else {
					alert(data.failure);
					PageChanger.loadHomepage();
				}
			});
		} else {
			alert('User was not unsuspended');
		}
	}
</script>
<?php
	} else {
		?>
<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'not_authorized'});
		</script>
<?php 	}} else {?>
<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'not_authorized'});
		</script>
<?php }?>
