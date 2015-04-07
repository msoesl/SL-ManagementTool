<?php
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

if (isset($_GET['new'])) {
	$banner = ORM::for_table ( 'banner' )->create();
} else if (isset($_GET['bid'])) {
	$id = $_GET ['bid'];
	$banner = ORM::for_table ( 'banner' )->find_one ( $id );
} else {
?>
	<script>
	window.location.href = '#404.php';
	</script>
<?php 
die;
}


?>
<div class='banner-edit-view'>
	<div class='popup-heading'>
		<h3 class="ui-collapsible-heading">
			Edit Banner: <?php echo $banner->title?>
		</h3>
	</div>
	<div class='popup-content'>
		<form data-ajax='false' method="post" enctype="multipart/form-data" action = 'controllers/BannerEditController.php'>
			<table>
				<tr>
					<td>
						Choose a Banner:
					</td>
					<td>
					<?php if (!isset($_GET['new'])) {?>
						<input type="hidden" name="id" value = '<?php echo $banner->id?>'/>
					<?php }?>
					</td>
				</tr>
				<tr>
					<td class = 'banner-cell'><img src = '<?php echo $banner->src?>' /></td>
					<td>
						 <input type="file" name="banner-pic" accept="image/*">
					</td>

				</tr>
				<tr>
					<td>Title:</td>
					<td><input type='text' name='title'
						value='<?php echo $banner->title?>' /></td>

				</tr>
				<tr>
					<td>Links To:</td>
					<td><input type='text' name='url' value='<?php echo $banner->url?>' />
					</td>
				</tr>
				<tr>
					<td>Alternate Text:</td>
					<td><input type='text' name='alttext'
						value='<?php echo $banner->alt_text?>' /></td>
				</tr>
				<tr>
					<td>Sort Order (Determines Banner Ordering from low to high):</td>
					<td><input type='text' name='sort_order'
						value='<?php echo $banner->sort_order?>' /></td>
				</tr>
				<tr>
					<td>Is Enabled?</td>
					<td><select name="isenabled" id="flip-1" data-role="slider">
							<option value="0" <?php echo ($banner->enabled == 0)?'selected':''?>>No</option>
							<option value="1"<?php echo ($banner->enabled >0)?'selected':''?>>Yes</option>
					</select></td>
				</tr>
			</table>
			<div class = 'ui-grid-b'>
				<div class = 'ui-block-a'>
				</div>
				<div class = 'ui-block-b'>
				</div>
				<div class = 'ui-block-c'>
					<button>Save</button>
				</div>
			</div>
		</form>
	</div>
</div>