<?php
require_once ('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
$type = "think";
if (isset($_POST['radio-choice-1'])) {
	$type = $_POST['radio-choice-1'];
}
$project = ORM::for_table('project')
	->find_one($_POST['pid']);
if ($type == 'think') {
	$s1 = ORM::for_table('stage_1')->find_one($project->stage_1_id);
	$s2 = ORM::for_table('stage_2')->find_one($project->stage_2_id);
	$s3 = ORM::for_table('stage_3')->find_one($project->stage_3_id);
	$s1->progress = 1;
	$s2->progress = 0;
	$s3->progress = 0;
	$s1->save();
	$s2->save();
	$s3->save();
} else if ($type == 'do') {
	$s1 = ORM::for_table('stage_1')->find_one($project->stage_1_id);
	$s2 = ORM::for_table('stage_2')->find_one($project->stage_2_id);
	$s3 = ORM::for_table('stage_3')->find_one($project->stage_3_id);
	$s1->progress = 100;
	$s2->progress = 1;
	$s3->progress = 0;
	$s1->save();
	$s2->save();
	$s3->save();
} else if ($type == 'achieve') {
	$s1 = ORM::for_table('stage_1')->find_one($project->stage_1_id);
	$s2 = ORM::for_table('stage_2')->find_one($project->stage_2_id);
	$s3 = ORM::for_table('stage_3')->find_one($project->stage_3_id);
	$s1->progress = 100;
	$s2->progress = 100;
	$s3->progress = 1;
	$s1->save();
	$s2->save();
	$s3->save();
	
} else if ($type == 'complete') {
	$s1 = ORM::for_table('stage_1')->find_one($project->stage_1_id);
	$s2 = ORM::for_table('stage_2')->find_one($project->stage_2_id);
	$s3 = ORM::for_table('stage_3')->find_one($project->stage_3_id);
	$s1->progress = 100;
	$s2->progress = 100;
	$s3->progress = 100;
	$s1->save();
	$s2->save();
	$s3->save();
}

if(isset($_POST['project-discontuation-description'])){
	$project->discontinued = 1;
	$project->discontinued_reason = strip_tags($_POST['project-discontuation-description']);
	$project->save();
}
?>
<script type = 'text/javascript'>
	window.location.href = '../#ProjectView.php?id=<?php echo $_POST['pid']?>';
</script>