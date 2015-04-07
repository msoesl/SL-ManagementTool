<?php
require_once('../views/import/headimportviews.php');

if(!isset($_GET['accountid'])){
	echo "<script> PageChanger.load404View(); </script>";
	die;
};

$error = false;
if(isset($_GET['error'])){
	$error = true;
};

$account = ORM::for_table('account')->find_one($_GET['accountid']);

if($account){
?>

<div id="problem_submission_confirmation_page" class="general-page">
	<div class="simple-padding-small standard-border white-background message-container">

		<?php if($account->locked_code == -1){ ?>
			<h2 class='title centered-text margin-bottom-20'> Congratulations!  Your account is unlocked! You can now log in. </h2>
		<?php }else{ ?>
			<?php if($error){ ?>
				<p class="centered-text margin-bottom-20" style="color: red;"> That was the wrong code! </p>
			<?php } ?>

			<p class="centered-text margin-bottom-20"> You should have recieved an email with a code in it.  Please copy paste the code here. </p>
			<form  data-ajax='false' enctype="multipart/form-data" method="POST" action="controllers/AccountUnlocker.php?id=<?php echo $account->id ?>">
				<table style="width:100%;">
					<tr>
						<td><label>Code</label></td>
						<td><input type="text" name="code"></input></td>
					</tr>
				</table>
				<input type="submit" value="submit">
			</form>
		<?php }?>



		<a data-role="button" class="return-home-button" data-theme="a"
			onclick="PageChanger.loadHomepage()">Return home</a>
	</div>
</div>

<?php } else {
	echo "<script> PageChanger.load404View(); </script>";
}

?>