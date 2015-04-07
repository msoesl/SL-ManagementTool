<?php 
require_once('import/headimportviews.php');
session_start();
$user = null;
if(!isset($_SESSION['user'])){?>
	<script type="text/javascript">
		PageChanger.loadMessageView({'messageType' : 'problem_submission_denied'});
	</script><?php 
}
	
?>

<div class="form_view_container" >

	<div class='form_header simple-padding-small standard-border'>
		<h3 class='title'>Submit a Problem</h3>
		<hr>
		<p><span style='color:red'>Important!</span><i> If you have observed a problem and thought about a way to solve it, please complete the form below. By submitting this form you agree to be the leader of this project and find collaborators to assist you.</i> </p>
	</div>
	 
	<form data-ajax="false" name="problem_submission_form" id="problem_submission_form" class='simple-padding-small standard-border' onsubmit='return validateSubmission();' action="controllers/ProblemSubmitController.php" method="post" enctype="multipart/form-data">
		<div>
				<label class="form-li-label" for="problem_title">Title:</label>
				<textarea name="problem_title" id="problem_title" form="problem_submission_form" value=""/>
				<label class="form-li-label" for="problem_description">Description:</label>
				<textarea name="problem_description" id="problem_description" form="problem_submission_form"></textarea>
				<label class="form-li-label" for="problem_image_uploader">You can add images when the project is created.</label>
				<label class="form-li-label" for="submit"> </label>
				<input type="submit" name="submit" value="Submit" data-theme="a" >
		</div>	
		
	</form>
	
</div>

<script type="text/javascript">
	function validateSubmission(){			
		if(document.getElementsByName("problem_title")[0].value == "" || document.getElementsByName("problem_description")[0].value == ""){
			alert("Title and Description fields are required");
			return false;
		}
	}
</script>
