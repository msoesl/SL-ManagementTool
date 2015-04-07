<?php 
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

	$id = $_GET['aid'];
	?>
<div id='reflection-collapsible'>
	<div class='popup-heading'>
		<h3 class="ui-collapsible-heading">
			<a href="#"
				data-corners="false" data-shadow="false" data-iconshadow="true"
				data-wrapperels="span" data-icon="plus" data-iconpos="left"
				data-theme="a"><span class="ui-btn-inner"><span class="ui-btn-text">
						Edit Team Member Accomplishment </span></span></a>
		</h3>
	</div>
	<div class='popup-content'>
		<form method = 'post' action = 'controllers/EditAccomplishmentController.php' data-ajax='false'>
		<p>Edit or Delete an Accomplishment</p>
		<?php $accomplishment = ORM::for_table ( 'teammember_accomplishment' )->find_one($id);?>
		<input name='aid' type='hidden' value = '<?php echo $id?>'/>
		<textarea name = 'accomplishment' ><?php echo $accomplishment->accomplishment?></textarea>
		<div class = 'ui-grid-a'>
			<div class = 'ui-block-a'>
				<input type="checkbox" name="delete" id = 'delete' class="custom" />
				<label for="delete">Delete?</label>
			</div>
			<div class = 'ui-block-b'>
				<button>Save</button>
			</div>
		</div>
		</form>
	</div>
</div>