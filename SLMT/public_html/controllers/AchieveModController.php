<?php
require_once ('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$id = $_POST['pid'];
$proj = new ProjectModel($id);
$stage = $proj->getStage3();


switch ($_POST['controller-type']) {
	case 'ProjectAchievementsSummary':
		$html = $_POST['new-value'];
		$stage->projects_achievements_summary = $html;
		$stage->save();
		break;
	case 'WhatWentRight':
		$html = $_POST['new-value'];
		$stage->what_went_right = $html;
		$stage->save();
	break;
	case 'WhatWentWrong':
		$html = $_POST['new-value'];
		$stage->what_went_wrong = $html;
		$stage->save();
	break;
	case 'WhatCanBeChangedInTheFuture':
		$html = $_POST['new-value'];
		$stage->future_changes = $html;
		$stage->save();
	break;
	case 'TeamMemberAccomplishments':
		$sid = $stage->id;
		$acc = ORM::for_table('teammember_accomplishment')
			->create();
		$acc->profile_id = $_POST['teammember'];
		$acc->stage3_id = $sid;
		$acc->accomplishment = $_POST['teammemberaccomplishment'];
		$acc->save();
	break;
	case 'AdditionalInformation':
		$html = $_POST['new-value'];
		$stage->additional_information = $html;
		$stage->save();
	break;
	case 'EditAccomplishment':
		$html = $_POST['new-value'];
		$stage->additional_information = $html;
		$stage->save();
	break;
}
?>
<script type = 'text/javascript'>
	window.location.href = '../#ProjectView.php?id=<?php echo $id?>&stage=3';
</script>