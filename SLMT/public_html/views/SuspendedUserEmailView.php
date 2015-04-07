<?php
require_once ('import/headimportviews.php');
$id = -1;
if(isset($_GET['userid'])){
	$id = $_GET['userid'];
}
?>
<div
	class='simple-padding-small box-div standard-border standard-background'
	id='email-admin'>
	<?php if(isset($_GET['message']) && $_GET['message']=='3'){// If the form submitted, only show the submission result?>
	<h3 class='title'>Submission Result</h3>
	<hr>
	<br>
	<?php  echo getErrorMessage($_GET['message']);
	}else{// If not, it might be the email has not been sent or the user is still did not submit the form yet?>
	<h3 class='title'>Suspension Defense Form</h3>
	<hr>
	<br>
	<p>
		Since your account has been suspended, you have only one chance to
		defend yourself. When sumbitting this form, an email will be sent to
		the Administrator. If your reasoning<br> <br> is accepted, your
		account will be activiated and an email will be sent to your account's
		email. To start fill the form, please <a onclick="return openP()">Click
			here </a>. Otherwise, you could do it later.
			<?php if(isset($_GET['message'])){?>

</div>
<div id='display-suspension-result'
	class='simple-padding-small box-div standard-border standard-background'>
	<h3 class='title'>Submission Result</h3>
	<hr>
	<?php
	if($_GET['message']!='3'){//successful

		?>
	<div id='error-message-color'>
	<?php echo getErrorMessage($_GET['message']);?>
	</div>
	<?php
	}
	?>
</div>
	<?php }?>
	<?php }?>


<!-- Emailing Admin for suspension Dialog -->
<div style='display: none'>

	<div data-role="popup" id="email-admin-by-suspended-user"
		data-dismissible="true">
		<form data-ajax='false' id='email-admin-suspended-user'
			action='controllers/SuspendedUserEmailController.php' method='POST'
			enctype="multipart/form-data">
			<div data-role="header" title="Email"
				data-theme="a" 
			class="ui-corner-top ui-header ui-bar-a modalContent"
				role="banner">
				<h1 class="ui-title" role="heading" aria-level="1">Send Email to
					Admin User</h1>
			</div>
			<div class='simple-padding-medium'data-theme="a">
				<p>Explain your situation:</p>
				<textarea type="textarea" name="reasoning" id='reason'
					data-theme="a" placeholder="Type here...."></textarea>
				<input type="submit" value="Send"data-theme="a"> <input type="hidden"
					name="userid" id="userid" value=<?php echo $id;?>>
			</div>
		</form>
	</div>

</div>

<script type="text/javascript">
function openP(){
	$('#email-admin-by-suspended-user').popup();
	$('#email-admin-by-suspended-user' ).popup( 'open' );
}

</script>
	<?php
	// Return the correct message based on the code that is being sent from the controller.
	function getErrorMessage($num){

		switch ($num){

			case '1':
				return 'You have to write a message to send the email.';
			case '2':
				return 'An error occured. Please, try again later.';
			case '3':
				return 'Your email has been sent successfully. A response will be sent as soon as possible.';
		}
	}

	?>
