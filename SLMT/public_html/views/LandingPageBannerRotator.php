<?php 
require_once('../views/import/headimportviews.php');
?>
 <script type = 'text/javascript'>
	function redirectTo(url) {
		window.location = url;
	}
 </script>
<div class = "homepage-slider flexslider">
	<ul class="slides">
		<?php
		$banners = ORM::for_table('banner')->where('enabled',1)->order_by_asc('sort_order')->find_many();
		foreach ($banners as $banner) {
		?>
		<li><a href='javascript:void(0)'><img onclick = "redirectTo('<?php echo $banner->url;?>')" src="<?php echo $banner->src?>" alt = '<?php echo $banner->alt_text;?>'/></a></li>
		<?php }?>
	</ul>
</div>

<script>
	$('.flexslider').flexslider({
	    animation: "slide"
	  });
</script>	