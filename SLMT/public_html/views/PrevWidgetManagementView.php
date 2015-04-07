<?php
	require_once ('import/headimportviews.php');
session_start();
$user = null;
if(isset($_SESSION['user'])){
	$user = $_SESSION ['user'];

}

if (isset($user) && $user->getSystemPrivileges()->system_admin > 0){?>
<script type = 'text/javascript'>
function recalculateImportance() {
	if (confirm("Do you want to recalculate Project Importance? This will cause the projects in the preview widget to change. This operation also may take a few minutes.")) {
		window.location = "controllers/ProjectImportanceController.php";
	}
}
</script>
<div class = 'width-80 margin-left-10'>
	<div class='learn-more-title'>
		<h1>Frontpage Project Preview Management</h1>
		<hr>
		<p class = 'margin-top-1em'>Click this button to recalculate which projects appear in the frontpage preview widget.
				Projects order is calculated based on how many collaborators are on each project, how many solutions have been submitted, and how many events have been scheduled.
				The top six and bottom six projects in each stage will be displayed. </p>
		<div class = 'ui-grid-a'>
			<div class = 'ui-block-a width-60'>
			</div>
			<div class = 'ui-block-b width-40'>
				<button onclick='recalculateImportance()'>Recalculate Importance</button>
			</div>
		</div>
		<hr>
		<p class = 'margin-top-1em'>
			Below is how the Preview Widget Currently Appears on the frontend:
		</p>
		&nbsp;
	</div>
	
		<?php include('previewwidget.php');?>
</div>
<?php } else {// if not registered user.

echo "<script type=\"text/javascript\">
		PageChanger.loadMessageView({'messageType' : 'not_authorized'});
      </script>";
}?>