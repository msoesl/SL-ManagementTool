<?php
	require_once ('import/headimportviews.php');
session_start();
$user = null;
if(isset($_SESSION['user'])){
	$user = $_SESSION ['user'];

}

$id = -1;
if(isset($_GET['id'])){
	$id = $_GET['id'];
}else{
	echo '<script> PageChanger.load404View(); </script>';
	die;
}

$project = new ProjectModel($id);

if (isset($user) && $user->getAccountId() === $project->getProjectOwner()->getAccountId()){
		$banners = ORM::for_table('project_banner')->where('project_id', $id)->order_by_asc('sort_order')->find_many();
	?>

<script type = 'text/javascript'>
function openProjectEditBannerView(bannerId) {
	if ($(window).width() > 796) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/ProjectBannerEditView.php?bid='+bannerId+'&id=<?php echo $id; ?>', 
		openjs:function(){
			$(document).trigger('create');
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400});
	} else {
		$('.ui-page').prepend('<div class="form-mask"></div>');
		TINY.box.show({mask:false,url:'views/ProjectBannerEditView.php?bid='+bannerId+'&id=<?php echo $id; ?>', 
			openjs:function(){
				$(document).trigger('create');
			},closejs:function(){
				$('.form-mask').remove();
			},width:Math.floor($(window).width()),height:400});
	}
}

function addNewProjectBannerView() {
	if ($(window).width() > 796) {
	$('.ui-page').prepend('<div class="form-mask"></div>');
	TINY.box.show({mask:false,url:'views/ProjectBannerEditView.php?new=true&id=<?php echo $id; ?>', 
		openjs:function(){
			$(document).trigger('create');
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400});
	} else {
		$('.ui-page').prepend('<div class="form-mask"></div>');
		TINY.box.show({mask:false,url:'views/ProjectBannerEditView.php?new=true&id=<?php echo $id; ?>', 
			openjs:function(){
				$(document).trigger('create');
			},closejs:function(){
				$('.form-mask').remove();
			},width:Math.floor($(window).width()),height:400});
	}
} 

function deleteProjectBannerView(bid) {
	if (confirm("Are you sure you want to delete this banner?")) {
		window.location = "controllers/ProjectDeleteBannerController.php?bid="+bid;
	}
}
</script>
<div class = 'banner-management-view'>
	<div class='learn-more-title'>
		<div class = 'ui-grid-a'>
				<div class = 'ui-block-a'>
					<h1>Manage Banners</h1>
				</div>
				<div class = 'ui-block-b banner-button'>
					<?php if (sizeof($banners)<10) {?>
					<button onclick = 'addNewProjectBannerView()'>Add New Banner</button>
					<?php }?>
				</div>
			
		</div>
		<p><i>Maximum of 10 Banners can be specified</i></p>
		<hr>
	</div>
	<?php 
		if (sizeof($banners)>0) {
	?>
	<table>
	<?php 
		foreach($banners as $banner) {
	?>
		<tr>
		<td class = 'banner-cell'>
			<img src = '<?php echo $banner->src?>'/>
		</td>
		<td class = 'second'>
			<h1><?php echo $banner->title?></h1>
			<table class = 'banner-info'>
				<tr>
					<td>Is Enabled? </td>
					<td><?php echo $banner->enabled>0?'Yes':'No'?></td>
				</tr>
				<tr>
					<td>URL : </td>
					<td><?php echo $banner->url?></td>
				</tr>
				<tr>
					<td>Alt. Text : </td>
					<td><?php echo $banner->alt_text?></td>
				</tr>
				<tr>
					<td>Sort Position : </td>
					<td><?php echo $banner->sort_order?></td>
				</tr>
			</table>
		</td>
		<td>
			<button onclick = 'openProjectEditBannerView(<?php echo $banner->id?>)'>Edit</button>
		</td>
		<td class = 'last'>
			<button onclick = 'deleteProjectBannerView(<?php echo $banner->id?>)'>Delete</button>
		</td>
		</tr>
		<tr>
			<td>
				<hr>
			</td>
			<td>
				<hr>
			</td>
			<td>
				<hr>
			</td>
			<td>
				<hr>
			</td>
		</tr>
	<?php }?>
	</table>
	<?php 
	} else {?>
		<p>No Banners currently exist.</p>
	<?php }?>
</div>
<?php } else {// if not registered user.

echo "<script type=\"text/javascript\">
		PageChanger.loadMessageView({'messageType' : 'not_authorized'});
      </script>";
}?>