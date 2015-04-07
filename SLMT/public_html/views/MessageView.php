<?php
require_once('import/headimportviews.php');

session_start ();
?>

<div id="problem_submission_confirmation_page" class="general-page">
	<div
		class="simple-padding-small standard-border white-background message-container">
		<h2 class='title centered-text margin-bottom-20'>
		<?php

		if(isset($_GET['messageType'])){
			if($_GET['messageType'] == 'problem_submission_confirm'){
				echo 'Thank you for your submission!';
			}else if($_GET['messageType'] == 'admin_configs_confirm'){
				echo 'Admin configurations saved';
			}else if($_GET['messageType'] == 'problem_submission_denied'){
				echo 'Please log in or sign up to submit a problem';
			}else if($_GET['messageType'] == 'confirm_question_submission'){
				echo 'Thank you for your question! We look forward to helping you as soon as we can.';
			}else if($_GET['messageType'] == 'not_authorized'){
				echo 'You are not authorized to view this page';
				if (isset ( $_SESSION ['user'] )) {// If the user logged in, show the home page.
					$user = $_SESSION ['user'];

					?>
			<script type="text/javascript">
				PageChanger.loadHomepage();
			</script>
			<?php
				}

			}else if($_GET['messageType'] == 'new_password_sent_confirm'){
				echo 'A new password has been sent to '.$_GET['email'];
			}else if($_GET['messageType'] == 'private_profile_access_denied'){
				echo 'Sorry, this profile is private';
			}
		}
		?>
		</h2>
		<a data-role="button" class="return-home-button" data-theme="a"
			onclick="PageChanger.loadHomepage()">Return home</a>
	</div>
</div>
