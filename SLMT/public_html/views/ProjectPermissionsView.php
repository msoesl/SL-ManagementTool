<?php
require_once ('import/headimportviews.php');
$id = $_GET ['projectid'];
$projectModel = new ProjectModel ( $id );
// TODO: Setup ACL

$allAccounts = ProjectModel::getAllAccountsForProject ( $id );
?>
<div class='general-page'>
	<form data-ajax='false' id='project-permissions-form' method = 'POST' action= 'controllers/ProjectPermissionsController.php?pid=<?php echo $id?>'>
	
	<div class='learn-more-title'>
		<h1>
			Permissions for <i>"<?php echo $projectModel->getProjectTitle()?>"</i>
		</h1>
		<button data-theme="a">Save</button>
		<hr>
	</div>
	<table id='permissions-table'>
		<tr class='table-header'>
			<th>User</th>
			<th>Moderator</th>
			<th>Editor</th>
			<th>Control Stages</th>
			<th>User <br>Permissions
			</th>
			<th>Add / Delete<br>Users
			</th>
		</tr>
		<?php foreach( $allAccounts as $account) {
			$aid = $account->Account_id;
			?>
		<tr>
			<td><?php echo $account->username?></td>
			<td>
				<div class='perm-checkbox'>
				<?php if ($account->moderate_discussions_permission > 0 ){?>
				<input checked data-theme="a" type="checkbox"
						name="moderate-permission<?php echo $aid;?>" id="moderate-permission<?php echo $aid;?>" class="custom" />
				<?php } else {?>
				<input data-theme="a" type="checkbox" name="moderate-permission<?php echo $aid;?>"
						id="moderate-permission<?php echo $aid;?>" class="custom" />
				<?php }?>
				<label for="moderate-permission<?php echo $aid;?>">Moderate</label>
				</div>
			</td>
			<td>
				<div class='perm-checkbox'>
				<?php if ($account->edit_project_content_permission > 0 ){?>
				<input checked data-theme="a" type="checkbox" name="edit-permission<?php echo $aid;?>"
						id="edit-permission<?php echo $aid;?>" class="custom" />
				<?php } else {?>
				<input data-theme="a" type="checkbox" name="edit-permission<?php echo $aid;?>"
						id="edit-permission<?php echo $aid;?>" class="custom" />
				<?php }?>
				<label for="edit-permission<?php echo $aid;?>">Edit Project</label>
				</div>
			</td>
			<td>
				<div class='perm-checkbox'>
				<?php if ($account->control_project_stages_permission > 0 ){?>
				<input checked data-theme="a" type="checkbox"
						name="manage-stages-permission<?php echo $aid;?>" id="manage-stages-permission<?php echo $aid;?>"
						class="custom" /> <label for="manage-stages-permission<?php echo $aid;?>">Manage
						Stages</label>
				<?php } else {?>
				<input data-theme="a" type="checkbox"
						name="manage-stages-permission<?php echo $aid;?>" id="manage-stages-permission<?php echo $aid;?>"
						class="custom" /> <label for="manage-stages-permission<?php echo $aid;?>">Manage
						Stages</label>
				<?php }?>
				</div>
			</td>
			<td>
				<div class='perm-checkbox'>
				<?php if ($account->collaborator_permission > 0 ){?>
				<input checked data-theme="a" type="checkbox" name="user-permission<?php echo $aid;?>"
						id="user-permission<?php echo $aid;?>" class="custom" />
				<?php } else {?>
				<input data-theme="a" type="checkbox" name="user-permission<?php echo $aid;?>"
						id="user-permission<?php echo $aid;?>" class="custom" />
				<?php }?>
					 <label
						for="user-permission<?php echo $aid;?>">User Permissions</label>
				</div>
			</td>
			<td>
				<div class='perm-checkbox'>
				<?php if ($account->delete_users_from_project_permission > 0 ){?>
				<input checked data-theme="a" type="checkbox" name="add-delete-permission<?php echo $aid;?>"
						id="add-delete-permission<?php echo $aid;?>" class="custom" /> 
				<?php } else {?>
				<input data-theme="a" type="checkbox" name="add-delete-permission<?php echo $aid;?>"
						id="add-delete-permission<?php echo $aid;?>" class="custom" /> 
				<?php }?>
					<label
						for="add-delete-permission<?php echo $aid;?>">Add / Delete Users</label>
				</div>
			</td>
		</tr>
		<?php }?>
	</table>
	</form>
</div>

<script type="text/javascript">
	$('#project-permissions-form').submit(function(e)
		{
			e.preventDefault();
			var postData = $(this).serializeArray();
			var formURL = $(this).attr("action");
			
			$.ajax(
					{
						url : formURL,
						type: "POST",
						data : postData,
						success:function(data, textStatus, jqXHR) 
						{
							console.log(data);
							var retVal = $.parseJSON(data);
							if(retVal.status === 'Success'){
								alert('Project permissions saved successfully');
							}else{
								console.log(retVal);
								alert('An error occurred while trying to save project privileges, please contact your system administrator');
							}
						}
					}
				);
		});
</script>