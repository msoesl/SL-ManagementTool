
<?php $flag = '0';?>
  <a href="#search" data-rel="popup" data-role="button"
					data-inline="true" data-icon="search" >Search</a>


	<div data-role="popup" id="search" data-theme="a" >

	 <ul data-theme="a" data-filter-theme='a' data-role="listview" data-filter="true"
	data-filter-reveal="true" data-filter-placeholder="Search for a project...."
	data-inset="true" >
	
	<?php
	
	$projects = ProjectModel::getAllProjectsForFiltering();
	foreach ( $projects as $project ) {
		
		$proj = new ProjectModel($project->id);
		$pics = $proj->getAllPictures ();
		$picURL = FALSE;
		foreach ( $pics as $pic ) {// just get  the first picture.
		
		$picURL = $pic->src;
		break;
		}
		?>
	<li onClick='searchResult(<?php echo $project->id;?>)'><img src="<?php  if($picURL){echo $picURL;} ?>" style="max-width:80px;max-height:80px;"><a><?php  echo $project->problem_title?>
	</a>
	</li>
	<?php }	?>
	
</ul></div>
<script>
// Load the selected project
function searchResult(projectId) {

		PageChanger.loadProjectView({'id': projectId});
	
	
}
</script>
