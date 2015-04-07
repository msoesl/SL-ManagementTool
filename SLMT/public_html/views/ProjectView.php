<?php
require_once ('import/headimportviews.php');

session_start ();
$isValidId = isset ( $_GET ['id'] );
$project = null;



$added = null;
$deleted = null;
$flag = FALSE;
if(isset($_GET['added']) ){
	$added = $_GET['added'];
	$flag = TRUE;
}
if(isset($_GET['deleted'])){
	$deleted = $_GET['deleted'];
	$flag = TRUE;
}



$id = -1;
if ($isValidId) {
	$id = $_GET['id'];
	$project = new ProjectModel ($id);
}else{
	?>
		<script type="text/javascript">
			//redirect to 404 page if there is no id set
			PageChanger.load404View();
		</script>
	<?php
	die();
}

$user;

if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
$canEdit = isset ( $user ) && $project && $project->isValid () && $project->getProjectOwner ()->getAccountId () === $user->getAccountId ();
$isDisabled = $project->isProjectDiscontinued();

$max_stage = $project->getStage();
$stage = $project->getStage(); //Done due to memory pointers.
if (isset ( $_GET ['stage'] )) {
	$var_stage = $_GET ['stage'];
	if($var_stage < 1 || $var_stage > 3){
		$var_stage = $max_stage;
	}
	if ($var_stage <= $max_stage && $var_stage >= 1) {
		$stage = $var_stage;
	}
}

if ($isValidId && $project && $project->isValid ()) {
	?>
	<?php include_once ("ProjectNavWidget.php");?>
	<?php include_once ('ProjectOptionsWidget.php');?>
	
<div id='ProjectViewHeader' class="float-center">
<?php include_once 'ProjectHeader.php';?>
</div>
<div id='ProjectPageContainer' class='ui-grid-b float-center'>
	<div id="ProjectViewNavColumn" class='ui-block-a'>
		<?php include_once ("ProjectNavStatic.php");?>
	</div>
	<div id="ProjectViewMainContent"
		class='ui-block-b simple-padding-xsmall'>
		<?php	
 		if ($stage == 1) {
			include_once ('ThinkView.php');	
		} else if ($stage == 2) {
			include_once ('DoView.php');
		} else if ($stage == 3) {
			include_once ('AchieveView.php');
		}
		?>		
	</div>
	<div id='ProjectViewOptionColumn' class='ui-block-c'>
		<?php include_once('ProjectOptionsStatic.php');?>
	</div>
</div>


<div id='status'>
<?php getStatus();?>
</div>

<?php } else {

	echo "<script type=\"text/javascript\">
			
						PageChanger.loadMessageView({'messageType' : 'not_authorized'});
           			 </script>";
} ?>


<?php
function getStatus(){
	// For adding and deleting images for Configure Pictures for a project
	global $flag;
	global $added;
	global $deleted;

	if(!isset($added)){
		$added = 'NULL';
	}
	if(!isset($deleted)){
		$deleted ='NULL';
	}
	if(isset($flag) && $flag){


		if($added=='0' || $deleted=='0'){// Show error message first

			echo "<script type=\"text/javascript\">
						$('#Configure-Picture-error-message').popup();
						$('#Configure-Picture-error-message').popup(\"open\");
           			 </script>";
		}else if($added=='1' || $deleted=='1'){// Show success message

			echo "<script type=\"text/javascript\">
						$('#Configure-Picture-confirmation-message').popup();
						$('#Configure-Picture-confirmation-message').popup(\"open\");
           			 </script>";
		}
			
	}



}?>

<!-- Configure Picture Pass Dialog -->
<div style='display: none'>
	<div data-role="popup" id="Configure-Picture-confirmation-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Configure Picture</h1>
		</div>

		<div class='simple-padding-medium' data-theme="a">
			<p>
			<?php

			if($added=='1' && $deleted=='1'){
				echo 'Adding and deleting pictures have been done.';
			}else if($added=='1'){
				echo 'Adding picture has been done.';
			}else if($deleted=='1'){
				echo 'Deleting picture has been done.';
			}

			?>
			</p>
		</div>
	</div>
</div>

<!-- Configure error message Dialog -->
<div style='display: none'>
	<div data-role="popup" id="Configure-Picture-error-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Configure Picture</h1>
		</div>

		<div class='simple-padding-medium' data-theme="a">
			<p>
			<?php
			if($added=='0' && $deleted=='0'){
				echo 'Your request for adding or deleting';
				echo '<BR>pictures could not be completed.';
			}else if($added=='0'){
				echo 'Your request for adding pictures';
				echo '<BR>could not be completed.';
				echo '<BR>Picture must be less than 5MB.';
			}else if($deleted=='0'){
				echo 'Your request for deleting pictures';
				echo '<BR>could not be completed.';
			}
			?>
			</p>
		</div>
	</div>
</div>
