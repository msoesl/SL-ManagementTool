<div id='project-nav-menu-fixed' class="fixed-menu"
	data-role="content" style="min-width: 225px; float: right;">
	<ul data-role="listview" data-inset="true" data-theme="a">
		<li id="goto_think" class="side_panel_item"
			<?php if($max_stage >= 1){
				if($stage == 1){?>
					data-theme="e"
				<?php }?>
				onclick="PageChanger.loadProjectView({'id' :'<?php echo $id?>', 'stage':'1'})" 
			<?php } else {?>
				style="opacity:0.2"
			<?php }?>>
			<?php if($max_stage >= 1){?>
				<a href='#'> Think </a>
			<?php } else {?>
				 Think 
			<?php }?></li>
		<li id="goto_do" class="side_panel_item"
			<?php if($max_stage >= 2){
				if($stage == 2){?>
					data-theme="f"
				<?php }?>
				onclick="PageChanger.loadProjectView({'id' :'<?php echo $id?>', 'stage':'2'})" 
			<?php } else {?>
				style="opacity:0.2"
			<?php }?>>
			<?php if($max_stage >= 2){?>
				<a href='#'> Do </a>
			<?php } else {?>
				 Do 
			<?php }?></li>
		<li id="goto_achieve" class="side_panel_item"
			<?php if($max_stage >= 3){
				if($stage == 3){?>
					data-theme="g"
				<?php }?>
				onclick="PageChanger.loadProjectView({'id' :'<?php echo $id?>', 'stage':'3'})" 
			<?php } else {?>
				style="opacity:0.2"
			<?php }?>>
			<?php if($max_stage >= 3){?>
				<a href='#'> Achieve </a>
			<?php } else {?>
				 Achieve 
			<?php }?></li>
	</ul>
</div>
