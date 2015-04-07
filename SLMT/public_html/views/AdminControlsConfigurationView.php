<?php 
	require_once('import/headimportviews.php');
	
	session_start();

	if(isset($_SESSION['user'])){
		$user = $_SESSION['user'];
		$sysPrivs = $user->getSystemPrivileges();
		if ($sysPrivs->system_configuration_permission>0) {
		$sysConfigs = ORM::for_table('system_configurations')->find_many();
?>
<div class="form_view_container">
	<div class='form_header simple-padding-small standard-border message-container'>
		<h3 class='title'>Configure Admin Options</h3>
	</div>
	<form data-ajax='false' id="admin_config_form" class='simple-padding-small standard-border' action="controllers/AdminConfigurationsController.php" method="post" enctype="multipart/form-data">
		<ol>
			<?php foreach ($sysConfigs as $config) {?>
			<li>
				<input type="checkbox" name="<?php echo $config->config_key?>" id="<?php echo $config->config_key?>" <?php if($config->value>0){echo 'checked';}?> data-theme="a"/>
				<label for="<?php echo $config->config_key?>"><?php echo $config->name?></label>
			</li>	
			<?php }?>
			<li>
			<label for="submit"> </label>
				<input type="submit" name="submit" value="Submit" data-theme"d">
			</li>	
		</ol>	
		
	</form>
</div>

<?php
	} else{
?>
		<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'not_authorized'});
		</script>
<?php 	}} else {?>
		<script type="text/javascript">
			PageChanger.loadMessageView({'messageType' : 'not_authorized'});
		</script>
<?php }?>