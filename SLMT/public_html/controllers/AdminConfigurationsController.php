<?php
require_once ('../views/import/headimportviews.php');

session_start();


if(isset($_SESSION['user'])){
	$user = $_SESSION['user'];
	$sysPrivs = $user->getSystemPrivileges();
		if ($sysPrivs->system_configuration_permission>0) {
			$configs = ORM::for_table('system_configurations')->find_many();
			foreach ($configs as $config) {
				if (isset($_POST[$config->config_key])) {
					$config->value = 1;
				} else {
					$config->value = 0;
				}
				$config->save();
			}
			
		header( 'Location: ../#MessageView.php?messageType=admin_configs_confirm') ;
	} else {
		header( 'Location: ../#MessageView.php?messageType=not_authorized') ;
	}
} else {
	header( 'Location: ../#MessageView.php?messageType=not_authorized') ;
}
