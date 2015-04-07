<?php 
require_once ('import/headimportviews.php');
session_start ();
$user = null;
if (isset ( $_SESSION ['user'] )) {
	$user = $_SESSION ['user'];
}

	$id = $_GET['pid'];
	$proj = new ProjectModel($id);
	$stage = $proj->getStage3();
	?>
<div id='reflection-collapsible'>
	<div class='popup-heading'>
		<h3 class="ui-collapsible-heading">
		<a href='javascript:void(0)' class="ui-collapsible-heading-toggle ui-btn ui-btn-icon-left ui-btn-up-g" data-corners="false" data-shadow="false" data-wrapperels="span" data-icon="false"  data-theme="g"><span class="ui-btn-inner"><span class="ui-btn-text">
		Additional Information
		</span></a>
		</h3>
	</div>
	<div class='popup-content'>
		<p>Enter any additional information about the project that you would like to share into the editor below.</p>
		<hr>
		<div id="wysihtml5-toolbar" style="display: none;">
			<a data-wysihtml5-command="bold">Bold</a> | <a
				data-wysihtml5-command="italic">Italic</a> | <a
				data-wysihtml5-command="insertUnorderedList">List (Unordered)</a> |
			<a data-wysihtml5-command="insertOrderedList">List (Numbered)</a> | <a
				data-wysihtml5-command="createLink">Insert Link</a> | 
			<div data-wysihtml5-dialog="createLink" style="display: none;">
				<label> Link: <input data-wysihtml5-dialog-field="href"
					value="http://" class="text">
				</label> <a href="javascript:void(0)";  data-wysihtml5-dialog-action="save">OK</a> 
				<a href="javascript:void(0)"; 
					data-wysihtml5-dialog-action="cancel">Cancel</a>
			</div>
			<a data-wysihtml5-command="insertImage">Insert Image</a>
			<div data-wysihtml5-dialog="insertImage" style="display:none">
				<label> Image: <input data-wysihtml5-dialog-field="src"
					value="http://">
				</label> <a href="javascript:void(0)"; data-wysihtml5-dialog-action="save">OK</a> 
				<a href="javascript:void(0)"; data-wysihtml5-dialog-action="cancel">Cancel</a>
			</div>
		</div>
		<form data-ajax = 'false' method='POST' action='controllers/AchieveModController.php'>
			<input style = 'display:none' name = 'controller-type' value = 'AdditionalInformation'>
			<textarea name = 'new-value'id="wysihtml5-textarea" placeholder='<?php echo htmlspecialchars(($stage->additional_information)?$stage->additional_information:'',ENT_QUOTES )?>'
				></textarea>
			<input style = 'display:none' name = 'pid' value = '<?php echo $id?>'>
			<div class='ui-grid-c'>
				<div class='ui-block-a'></div>
				<div class='ui-block-b'></div>
				<div class='ui-block-c'></div>
				<div class='ui-block-d'>
					<button>Submit</button>
				</div>
			</div>
		</form>
	</div>
</div>