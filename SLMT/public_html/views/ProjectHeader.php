<?php
if(!isset($project)){
	echo "done goofed";
	die;
}

$isDiscontinued = $project->isProjectDiscontinued();
?>
<h1><?php echo $project->getProjectTitle() ?></h1> 
<?php if($isDiscontinued) { echo "<h3 class='discontinued-header'> This project has been discontinued </h3>"; } ?>
<hr>