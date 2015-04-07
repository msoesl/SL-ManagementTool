<?php
require_once('../views/import/headimportviews.php');
session_start();
$project = null;
if(isset($_GET['project_id'])){
	$project = new ProjectModel ($_GET['project_id'] );
}

if(isset($project)){
	if(isset($_POST['new_event_title']) && isset($_POST['new_event_date'])  && isset($_POST['new_event_description'])){
		$title = strip_tags($_POST['new_event_title']);
		$date = strip_tags($_POST['new_event_date']);
		$desc = strip_tags($_POST['new_event_description']);
		$project->createEvent($title, $date, $desc);
	}
}

?>
<script type = 'text/javascript'>
	window.location.href = '../#ProjectView.php?id=<?php echo $_GET['project_id']?>&stage=2';
</script>