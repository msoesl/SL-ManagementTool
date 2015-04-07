<?php 
require_once ('import/headimportviews.php');

session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$isValidId = isset ( $_GET ['id'] );
$account = AccountModel::getAccountById($_GET['id']);
$profile = $account->profile;
$canEdit = isset ( $user ) && $isValidId && $user->getAccountId() == $_GET ['id'];
if(isset($_GET['id'])){
?>
<div id="friend-requests-view" class='full-width'>
	<div id='box-div' class='simple-padding-small standard-border title width-80 center-in-container'>
		<h3><?php echo $profile->firstname." ".$profile->lastname;?>'s friends</h3>
		<hr>
		<div class='message-container center-in-container'>
			
				<ul data-role='listview' data-theme="a" data-inset='true'>
				<?php 		
					foreach ($account->friends as $friend){
						$friendProf = $friend->getProfile();?>
							<li id='friend-<?php echo $friend->getAccountId();?>' data-theme="a" class='friend' data-role='listitem'><div id='friend-request'>
								<div class='friend-request-info'>
									<a href='' onclick='PageChanger.loadProfileView({id:<?php echo $friend->getAccountId();?>})'><img src='<?php  echo $friendProf->profile_pic_url;?>'/></a>
									<div class='personal-info'>
										<a href='' onclick='PageChanger.loadProfileView({id:<?php echo $friend->getAccountId();?>})'><h2><?php echo $friendProf->firstname." ".$friendProf->lastname;?></h2></a><br>
												<h3><?php echo $friendProf->city.", ".$friendProf->state;?></h3>
									</div>
									</div>
									<div class='friend-request-options'>
										<?php if($canEdit){?><a data-role='button' data-mini='true' data-theme="a" onclick='removeFriend(<?php echo $friend->getAccountId();?>)'>Remove</a> <?php }?>
									</div>
								</div>
							</li>
					<?php }?>						
			</ul>
		</div>
	</div>
	
</div>
<?php 
}else{?>
	<script type="text/javascript">PageChanger.loadHomepage();</script>
<?php }?>

<script type="text/javascript">
var removeFriend = function(userId) { 
	var json = {"uid":userId,"action":"remove"};
	$.post('controllers/FriendshipController.php', json)
		.done(function(data){
			console.log(data);
			data = $.parseJSON(data);
			if(data.status){
				var name = $('#friend-' + userId + " h2").html();
				$('#friend-' + userId)
				.html("You have unfriended <a href='' onclick='PageChanger.loadProfileView({id:" + userId + "})'>" + name  + "</a>");
			} else { 
				alert("Failed to delete friend");
				location.reload();
			}
		});
}
</script>