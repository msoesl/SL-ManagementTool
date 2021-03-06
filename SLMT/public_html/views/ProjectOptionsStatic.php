<?php
if (! isset ( $canEdit )) {
	echo 'Done goofed';
}
?>

<div id='project-options-menu-fixed' class='fixed-menu'
	style="max-width: 225px; float: left;">
	<!-- Profile Picture -->
	<div
		onclick="PageChanger.loadProfileView({id: <?php echo $project->getProjectOwner()->getAccountId()?>});"
		id="collab_picture">
		<img
			style="width: auto; height: auto; max-height: 200px; max-width: 100%; cursor: pointer;"
			src="<?php echo $project->getProjectOwner()->getProfilePicUrl() ?>">
		<div id="collab_name" class="name">
			<b>Project Owner:<b><br /> <?php echo $project->getProjectOwner()->getProfile()->firstname." ".$project->getProjectOwner()->getProfile()->lastname;?>
		</div>
	</div>
	<ul data-role="listview" data-inset="true" data-theme="a">
		<?php include("ProjectOptionsMenuItems.php");?>
	</ul>
</div>
</div>





<!-- Collaborative Request  -->
<div style='display: none'>
	<div data-role="popup" id="requestCollaborationPopupDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Project Detail
				Editor</h1>
		</div>
		<div data-role="collapsible" data-collapsed-icon="arrow-r"
			data-expanded-icon="arrow-d">
			<h3>Project Name</h3>
			<form id='project-name-form' data-ajax='false' method='POST'
				action='controllers/RequestCollaborationController.php'>
				<input type="text" name="project_name"
					value="<?php echo $project->getProjectTitle();?>">
			</form>
		</div>
		<div class='ui-grid-a'>
			<div class='ui-block-a'>
				<a href="#" data-role="button" data-inline="true" data-rel="back"
					data-theme="a">Cancel</a>
			</div>
			<div class='ui-block-b'>
				<input type="submit" name="submit" value="Submit" data-theme"d">
			</div>
		</div>
	</div>
</div>

<!-- Collaborative Request Pass Dialog -->
<div style='display: none'>
	<div data-role="popup" id="Collaborative-Request-confirmation-message"
		data-dismissible="true">
		<div data-role="header" title="Create" data-theme"d"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Collaborative
				Request</h1>
		</div>

		<div class='simple-padding-medium' data-theme"d">
			<p>Your request has been sent.</p>
		</div>
	</div>
</div>

<!-- Collaborative Request Fail Dialog -->
<div style='display: none'>
	<div data-role="popup" id="error-message" data-dismissible="true">
		<div data-role="header" title="Create" data-theme"d"
			class="ui-corner-top ui-header ui-bar-a modalContent" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Collaborative
				Request</h1>
		</div>

		<div class='simple-padding-medium' data-theme"d">
			<p>Your request has Not been sent. Try again.</p>
		</div>
	</div>
</div>

<!-- Image Editor Dialog -->
<div style='display: none' class="image-editing-dialog">
	<div data-role="popup" id="imageEditorDialog">
		<div data-role="header" title="Edit" data-theme="a"
			class="ui-corner-top ui-header ui-bar-a" role="banner">
			<h1 class="ui-title" role="heading" aria-level="1">Configure Pictures</h1>
		</div>
		<div class='simple-padding-medium'>
			<form enctype="multipart/form-data" id='project-pictures-form'
				, data-ajax="false" , method="POST"
				, action="controllers/SaveProjectController.php?id=<?php echo $project->getProjectId()?>">
				<?php include('imageuploaderwidget.php'); ?>

				<input type="submit" name"submit" value="Save" data-theme"d">
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
function requestCollaboration(){
	
    $.post('controllers/RequestCollaborationController.php?projectId='+<?php echo $project->getProjectId()?>+'&ownerId='+
    											<?php echo $project->getProjectOwner()->getAccountId()?>).done(function(result){
         if(result == 1){
        	$('#Collaborative-Request-confirmation-message').popup("open");
         }else{
        	 $('#error-message').popup("open");
         }
    });
 }

function validateSubmission(){			
	if(document.getElementsByName("project-discontuation-description")[0].value == ""){
		alert("Title and Description fields are required");
		return false;
	}
}

</script>
