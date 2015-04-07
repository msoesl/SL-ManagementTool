<?php
$project = false;
if ($_GET ['id']) {
	$project = new ProjectModel ( $id );
	$stage3 = $project->getStage3 ();
}

if (! isset ( $project )) {
	// Should probably switch this for now, but maybe replace this with 404
	echo 'you done goofed.';
	die ();
}
$user;

if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

$canEdit = isset ( $user ) && $project && $project->isValid () && $project->getProjectOwner ()->getAccountId () === $user->getAccountId ();
if ($isValidId && $project && $project->isValid ()) {
	?>
<script type='text/javascript'>
function openProjectSummary(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/AchieveProjectSummaryView.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
			
			var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
				  toolbar:      "wysihtml5-toolbar", // id of toolbar element
				  parserRules:  wysihtml5ParserRules // defined in parser rules set 
				});
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
function openWhatWentRightView(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/WhatWentRightView.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
			
			var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
				  toolbar:      "wysihtml5-toolbar", // id of toolbar element
				  parserRules:  wysihtml5ParserRules // defined in parser rules set 
				});
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
function openWhatWentWrongView(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/WhatWentWrongView.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
			
			var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
				  toolbar:      "wysihtml5-toolbar", // id of toolbar element
				  parserRules:  wysihtml5ParserRules // defined in parser rules set 
				});
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
function openWhatCanBeChangedInFuture(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/ChangedInFuture.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
			
			var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
				  toolbar:      "wysihtml5-toolbar", // id of toolbar element
				  parserRules:  wysihtml5ParserRules // defined in parser rules set 
				});
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
function openTeamMemberAccomplishments(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/TeamMemberAccomplishmentsView.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
function openAdditionalInformation(projectId) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/AdditionalInformationView.php?pid='+projectId, 
		openjs:function(){
			$(document).trigger('create');
			
			var editor = new wysihtml5.Editor("wysihtml5-textarea", { // id of textarea element
				  toolbar:      "wysihtml5-toolbar", // id of toolbar element
				  parserRules:  wysihtml5ParserRules // defined in parser rules set 
				});
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}

function openEditAccomplishment(aid) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/EditAccomplishmentView.php?aid='+aid+'&pid=<?php echo $_GET['id']?>', 
		openjs:function(){
			$(document).trigger('create');
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}
</script>
<div id="project_view" class="float-center project-view">
	<div class="flexslider">
		<ul class="slides">
		<?php
	$pics = $project->getAllPictures ();
	foreach ( $pics as $banner ) {
		?>
			<li><a href='javascript:void(0)'><img onclick = "window.location = '<?php echo $banner->url;?>'" src="<?php echo $banner->src?>" alt = '<?php echo $banner->alt_text;?>'/></a></li>
		<?php }?>
	</ul>
	</div>

	<script>
$('.flexslider').flexslider({
	animation: "slide"
});
</script>

	<h2>Reflection</h2>
	<hr>
	<div id='reflection-collapsible' data-role="collapsible-set">

		<div data-theme='g' data-role="collapsible" data-collapsed="false">
			<h3>Project Achievements Summary</h3>
			<div class='collapsible-content'>
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openProjectSummary(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>edit</a>
			<?php }?>
				<div>
					<?php echo $stage3->projects_achievements_summary?>
				</div>
			</div>
		</div>

		<div data-theme='g' data-role="collapsible">
			<h3>What Went Right</h3>
			<div class='collapsible-content'>
			
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openWhatWentRightView(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>edit</a>
			<?php }?>
				<div>
					<?php echo $stage3->what_went_right?>
				</div>
			</div>
		</div>

		<div data-theme='g' data-role="collapsible">
			<h3>What Went Wrong</h3>
			<div class='collapsible-content'>
			
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openWhatWentWrongView(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>edit</a>
			<?php }?>
				<div>
					<?php echo $stage3->what_went_wrong?>
				</div>
			</div>
		</div>

		<div data-theme='g' data-role="collapsible">
			<h3>What Can Be Changed in the Future</h3>
			<div class='collapsible-content'>
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openWhatCanBeChangedInFuture(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>edit</a>
			<?php }?>
				<div>
					<?php echo $stage3->future_changes?>
				</div>
			</div>
		</div>


		<div data-theme='g' data-role="collapsible">
			<h3>Team Member Accomplishments</h3>
			<div class='collapsible-content'>
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openTeamMemberAccomplishments(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>add</a><?php }?>
							<table>
			<?php
			$team = ORM::for_table ( 'teammember_accomplishment' )->where ( 'stage3_id', $stage3->id )->find_many ();
			foreach ( $team as $member ) {
				$profile = ORM::for_table ( 'profile' )->where ( 'id', $member->profile_id )->find_one ();
				?>
							<tr>
							<td class = 'first'><img class='accomplishment-picture' src='<?php echo $profile->profile_pic_url?>' /></td>
							<td class ='second' >
								<h3><?php echo $profile->firstname.' '.$profile->lastname;?></h3>
								<p>
									<?php echo $member->accomplishment?>
								</p>
			<?php if ($canEdit) {?>
								<a href = 'javascript:void(0);' onclick = 'openEditAccomplishment(<?php echo $member->id?>)'>edit</a>
			<?php }?>
							</td>
						</tr>
					<?php }?>
				</table>
			</div>
		</div>

		<div data-theme='g' data-role="collapsible">
			<h3>Additional Information</h3>
			<div class='collapsible-content'>
			<?php if ($canEdit) {?>
				<a class='editLink'
					onclick='openAdditionalInformation(<?php echo ($_GET ['id'])?$_GET ['id']:''?>)'
					href='javascript:void(0);'>edit</a><?php }?>
				<div>
					<?php echo $stage3->additional_information?>
				</div>
			</div>
		</div>

	</div>
</div>

<?php } else { ?>

That's not a valid Project!
<button onclick="PageChanger.loadHomepage()" data-theme="a">Go Home</button>

<?php } ?>