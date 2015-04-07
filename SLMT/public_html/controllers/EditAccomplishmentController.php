<?php
require_once ('../views/import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}
if (isset($_POST['delete'])) {
	ORM::for_table('teammember_accomplishment')->find_one($_POST['aid'])->delete();
} else {
	$acc = ORM::for_table('teammember_accomplishment')->find_one($_POST['aid']);
	$acc->accomplishment = strip_tags($_POST['accomplishment']);
	$acc->save();
}?>

<script type = 'text/javascript'>
	history.go(-1);
</script>