<?php require_once("views/import/phpimport.php");?>
<?php 
	session_start();
    $user = NULL;
	if (isset($_SESSION['user'])) {
		$user = $_SESSION['user'];
	}
	ini_set('display_errors',1);
	//error_reporting(0);
	error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang='en' dir='ltr'>
<head>
<meta name='viewport' content='width=device-width, initial-scale=1' />
<meta charset="utf-8">

<title>SLMT</title>
<!-- Any Script Imports (PHP, CSS or JS) that are global to every page go in headimport.php -->
		<?php require_once("views/import/headimport.php");?>
		<!-- Page Specific Script imports go here -->
</head>
<body class="standard-background">
	<div data-role="page" class="transparent-background">
		<?php require_once("views/headerbar.php");?>
		<div id='page-content'>
			
		</div>
		<?php
		// only if we actually have a footer... otherwise remove this beast
		// require_once("viwes/footerbar.php");
		?>
	</div>
</body>
</html>