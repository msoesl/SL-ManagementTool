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
	$eid = $_GET['eid'];
	$attendees = ORM::for_table ( 'event_has_account' )->select ( 'a.*' )->select ( 'pro.profile_pic_url' )->where ( 'event_id', $eid )->inner_join ( 'account', 'account_id = a.id', 'a' )->inner_join ( 'profile', 'pro.id = a.Profile_id', 'pro' )->find_many ();
	?>
	<h3 class='title'>Attendees</h3>
	<?php if ($attendees) {?>
	<table id='photo-grid'>
		<?php
		$count = 0;
		foreach ( $attendees as $attendee ) {
			if (0 == ($count % 4)) {
				echo '<tr>';
			}
			?>
		<td>
		<a href = 'javascript:void(0)' onclick='new function(){window.parent.TINY.box.hide();$(".form-mask").remove();PageChanger.loadProfileView({id:<?php echo $attendee->id?>});}'><div style="background:url(<?php echo $attendee->profile_pic_url?>) no-repeat center center"></div></a>
		<h3><a href = 'javascript:void(0)' onclick='new function(){window.parent.TINY.box.hide(); $(".form-mask").remove();PageChanger.loadProfileView({id:<?php echo $attendee->id?>});}'><?php echo $attendee->username?></a></h3></td>
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
		<p>There are currently 0 people attending</p>
	<?php }?>
</div>