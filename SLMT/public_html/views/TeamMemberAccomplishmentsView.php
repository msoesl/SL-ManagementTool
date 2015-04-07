<?php
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$id = $_GET ['pid'];
$proj = new ProjectModel ( $id );
$stage = $proj->getStage3 ();
?>
<div id='reflection-collapsible'>
	<div class='popup-heading'>
		<h3 class="ui-collapsible-heading">
		<a href='javascript:void(0)' class="ui-collapsible-heading-toggle ui-btn ui-btn-icon-left ui-btn-up-g" data-corners="false" data-shadow="false" data-wrapperels="span" data-icon="false"  data-theme="g"><span class="ui-btn-inner"><span class="ui-btn-text">
		Team member Accomplishments
		</span></a>
		</h3>
	</div>
	<div class='popup-content'>
		<p class='medium-heading-text'>Add a Team Member Accomplishment:</p>
		<hr>
		<form method='POST' action='controllers/AchieveModController.php' data-ajax='false'>
			<div class='ui-grid-a'>
				<div class='ui-block-a'>
					<p class='popup-field-text'>Choose a Team member:</p>
				</div>
				<div class='ui-block-b'>
					<select name='teammember'>
					<?php
					$accounts = ORM::for_table ( 'project_has_account' )->select ( 'a.Profile_id' )->select ( 'a.id', 'aid' )->select ( 'p.*' )->where ( 'Project_id', $id )->inner_join ( 'account', 'a.id = Account_id', 'a' )->inner_join ( 'profile', 'p.id = a.Profile_id', 'p' )->find_many ();
					foreach ( $accounts as $account ) {
						?>
						<option value='<?php echo $account->Profile_id?>'><?php echo $account->firstname .' '.$account->lastname?></option>
					<?php }?>
				</select>
				</div>
			</div>
			<p>What has the team member accomplished?</p>
			<textarea name='teammemberaccomplishment'></textarea>
			<div class='ui-grid-a'>
				<div class='ui-block-a'></div>
				<div class='ui-block-b'>
					<button>Add Accomplishment</button>
				</div>
			</div>			
			<input style = 'display:none' name = 'controller-type' value = 'TeamMemberAccomplishments'>
			<input style = 'display:none' name = 'pid' value = '<?php echo $id?>'>
		</form>

	</div>
</div>