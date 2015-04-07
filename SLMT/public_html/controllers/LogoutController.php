<?php
try {
	session_start();
	$_SESSION['user'] = null;
	session_destroy();
	echo json_encode(array('status'=>'success'));
}catch (Exception $e) {
	echo json_encode(array('status'=>'failed'));
}