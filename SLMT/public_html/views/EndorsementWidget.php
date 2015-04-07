<?php
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
?>
<script type = 'text/javascript'>
	var removeEndorse = function(skillId) {
		$.post( "controllers/EndorsementController.php", { skill: skillId, type:'remove'} )
		.done(function(data){
			console.log(data);
			window.location.reload();
		});
	}
</script>
<div id="endorsement-widget"
	class='ui-body-c personal-profile-view box-div standard-background tinyboxcontainer'>
	<?php
	$sid = $_GET ['sid'];
	$pid = $_GET ['pid'];
	$endorsements = ORM::for_table ( 'endorsement' )->select ( 'a.*' )->select ( 'pro.profile_pic_url' )->where ( 'Account_has_Skills_id', $sid )->inner_join ( 'account', 'Account_id = a.id', 'a' )->inner_join ( 'profile', 'pro.id = a.Profile_id', 'pro' )->find_many ();
	if (isset($user) && $user->getAccountId () != $pid) {
		$isEndorsedAlready = ORM::for_table ( 'endorsement' )->where ( 'Account_has_Skills_id', $sid )->where ( 'Account_id', $user->getAccountId () )->find_one ();
		if (! $isEndorsedAlready) {
			?>
		<button data-theme="a" onclick = 'new function(){$.post( "controllers/EndorsementController.php", { skill:<?php echo $sid?>, type:"add"} ).done(function(data){window.location.reload();});}'>Click to Endorse</button>
	<?php
		} else {
			?>
		<button data-theme="a" onclick = 'new function(){$.post( "controllers/EndorsementController.php", { skill:<?php echo $sid?>, type:"remove"} ).done(function(data){window.location.reload();});}'>Remove Endorsement</button>
	<?php	}?>
	<h3 class='title'>Endorsers</h3>
	<hr>
	<?php
	} else if (isset($user)){
		?>
	<h3 class='title'>Your Endorsers</h3>
	<hr>
	<?php } else {?>
	<h3 class='title'>Endorsers</h3>
	<hr>
	<?php }?>
	<?php if ($endorsements) {?>
	<table id='photo-grid'>
		<?php
		$count = 0;
		foreach ( $endorsements as $endorsee ) {
			if (0 == ($count % 4)) {
				echo '<tr>';
			}
			?>
		<td>
		<a href = 'javascript:void(0)' onclick='new function(){window.parent.TINY.box.hide();$(".form-mask").remove();PageChanger.loadProfileView({id:<?php echo $endorsee->id?>});}'><div style="background:url(<?php echo $endorsee->profile_pic_url?>) no-repeat center center"></div></a>
		<h3><a href = 'javascript:void(0)' onclick='new function(){window.parent.TINY.box.hide(); $(".form-mask").remove();PageChanger.loadProfileView({id:<?php echo $endorsee->id?>});}'><?php echo $endorsee->username?></a></h3></td>
		<?php
			$count ++;
			if (0 == ($count % 4)) {
				echo '</tr>';
			}
		}
		//if close off the table row if we weren't a multiple of 4 in the end
		if (0 != ($count % 4)) {
			echo '</tr>';
		}
		?>
	</table>
	<?php } else {?>
		<p>Noone has endorsed this skill.</p>
	<?php }?>
</div>