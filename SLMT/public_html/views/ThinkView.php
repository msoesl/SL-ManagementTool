<?php

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
$isDiscontinued = $project->isProjectDiscontinued();

if ($project && $project->isValid ()) {
	?>

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
	<h2>Description</h2>
	<hr>
	<div id="project_view_description" class="description">
		<?php if($project->isProjectDiscontinued()){ ?>
			<div class="discontinued-block">
				<h3 class="discontinued-header centered-text">Unfortunately, this project has been discontinued</h3>
				<p>
					The project owner's reason for discontinuing this project is: 
				</p>
				<p>
					<?php echo $project->getDiscontinuedReason(); ?>
				</p>
				<p>
					The forum will remain open for discussion, but the project no longer being mantained.
				</p>		
			</div>
		<?php }?>
		<?php echo $project->getProjectSummary()?>
	</div>
	<h2>Proposed & Accepted Solutions</h2>
	<hr>
	<?php include_once("ProposedSolutionWidget.php");?>
</div>

<?php } else { ?>
<!-- <script>PageChanger.load404Page();</script> -->
<?php } ?>