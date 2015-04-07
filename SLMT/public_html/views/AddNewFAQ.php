<?php 
require_once ('import/headimportviews.php');

$action;
$faqId;
$faq;
$action;
if(isset($_GET['edit'])){
	if($_GET['edit'] == 1){
		$action = 'modify-faq';
	}else{
		$action = 'new-faq';
	}
}
if(isset($_GET['faqId'])){
	$faqId = $_GET['faqId'];
	$faq = ORM::for_table('frequently_asked_questions')->find_one($faqId);
}
?>

<?php if($action == 'new-faq'){?><h3><b><i>Create your new FAQ here...</i></b></h3><?php }
	 else{?> <h3><b><i>Edit your FAQ here...</i></b></h3><?php }?>
<form data-ajax="false" name="new-faq-form" id="new-faq-form" onsubmit='return validateSubmission();' action="controllers/FAQManagementController.php" method="post" enctype="multipart/form-data" style="width:80%;margin:20px auto;">
	<label class="form-li-label" for="faq-question"><h3>Question:</h3></label>
	<textarea name="faq-question" id="faq-question" form="new-faq-form"><?php if($action == 'modify-faq') echo $faq->question?></textarea>
	<label class="form-li-label" for="faq-answer"><h3>Answer:</h3></label>
	<textarea name="faq-answer" id="faq-answer" form="new-faq-form" style='height:100px'><?php if($action == 'modify-faq') echo $faq->answer?></textarea>
	<input type='hidden' name='faq-action' value='<?php echo $action?>'/>
	<input type='hidden' name='id' value='<?php echo $faqId;?>'/>
	<div style="width:30%;margin-top:20px;min-width:100px">
		<input type="submit" name="submit" value="<?php echo ($_GET['edit'] == 1) ? 'Save' : 'Submit'?>" data-theme="a"  />		
	</div>
</form>

<script type="text/javascript">
	function validateSubmission(){			

	}
</script>