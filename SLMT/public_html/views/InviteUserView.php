<?php
require_once ('import/headimportviews.php');

session_start ();

if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];

	if(isset($user)){
		?>
<div class='general-page'>
	<div class='ui-grid-b'>
		<div id='user-search-view' class='ui-block-a'
			style='width: 48%; min-height: 500px;'>
			<div>
				<h1>Projects</h1>
				<hr>
			</div>
			<h3 class='heading ban-text'>Which projects you would invite users to:</h3>
			<div>
			<?php
			$projects = ProjectModel::getAllProjectsForAccountCollaboration($user->getAccountId());
			?>
			<?php
			$projectsExist = false;
			foreach ( $projects as $project ) {
				$pro = new ProjectModel($project->Project_id);
				$projectsExist = true;
				?>
				<div>	
					<label ><input type="checkbox" name="project" value="<?php echo $pro->getProjectId(); ?>">
					<?php echo $pro->getProjectTitle(); ?></label>
				</div>
				<?php }?>
				</div>
		</div>
		<div class='ui-block-b' 
			style='width: 48%; min-height: 500px; padding-left: 1%; padding-top: 1em; border-left: 1px solid black'>
			
			<div>
				<h1>Invite A User</h1>
				<hr>
			</div>
			<h3 class='heading ban-text'>Search for a user to invite...</h3>
			<ul data-theme="a" data-role="listview" data-filter="true"
				data-filter-reveal="true"
				data-filter-placeholder="Search by name or city"
				data-inset="true">
				<?php
				$profiles = ORM::for_table ( 'account' )->select ( 'account.*' )->select ( 'p.firstname' )->select ( 'p.lastname' )->select ( 'p.city' )->inner_join ( 'profile', 'p.id=Profile_id', 'p' )->find_many ();
				
				foreach ( $profiles as $profile ) {
					?>
				<li onClick='inviteUser(<?php echo $profile->id;?>)'><a><?php echo $profile->firstname.' '.$profile->lastname.' , '.$profile->city?></a>
				</li>
				<?php }?>
			</ul>
			
		</div>
	</div>

</div>
<script type="text/javascript">
	// Post the user id and project ids that he will be invited to
	function inviteUser(userId) {
		// Get the selected projects
	     var checked = checkedBoxes();
		if(checked!=null && checked.length>0){
			 var postData = jQuery.makeArray();
			 
		     for(var i=0; i<checked.length; i++){
		    	 postData.push(checked[i].value); 
		     }
		     postData.push(userId);
		     
		if (confirm('Are you sure you want to invite this user?')) {
			$.post('controllers/InviteUserController.php', {array:postData})
				.done(function(data){
					data = $.parseJSON(data);
					if (data.success) {
						alert(data.success);
						location.reload();
					} else {
						alert(data.failure);
						location.reload();
					}
				});
		} else {
			alert('User was not invited');
		}
		}else{
			alert('You must at least check on project to invite users to.');
		}
	}
	// Return the selected projects 
	function checkedBoxes(){
	var inputs = document.getElementsByTagName("input");
	var cbs = []; //will contain all checkboxes
	var checked = []; //will contain all checked checkboxes
	for (var i = 0; i < inputs.length; i++) {
	  if (inputs[i].type == "checkbox") {
	    cbs.push(inputs[i]);
	    if (inputs[i].checked) {
	      checked.push(inputs[i]);
	    }
	  }
	}
	
	var nbChecked = checked.length; //number of checked checkboxes
	if(nbChecked>0){
		
		return checked;
	}else{
		return null;
	}
	}	
</script>
				<?php
	}
} else {?>
<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'not_authorized'});
		</script>
<?php }
?>