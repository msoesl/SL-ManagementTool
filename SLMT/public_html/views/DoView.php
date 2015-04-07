<?php
if (! isset ( $project )) {
	// Should probably switch this for now, but maybe replace this with 404
	echo 'you done goofed.';
	die ();
}
if ($isValidId && $project && $project->isValid ()) {
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

	<?php include_once ('EventCalendarWidget.php'); ?>
</div>

<?php } else { ?>

<script type="text/javascript">
PageChanger.loadMessageView({'messageType' : 'not_authorized'});
</script>

<?php } ?>