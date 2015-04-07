<?php 
require_once ('import/headimportviews.php');
$faqs = ORM::for_table("frequently_asked_questions")->find_many();

$admin = ORM::for_table('account')
->inner_join('system_privileges', 'sp.id = account.id', 'sp')
->where('sp.system_admin', 1)
->find_one();

session_start();

if(!isset($_SESSION['user']) || $_SESSION['user']->getAccountId() != $admin->id){?>
	<script type='text/javascript'>
		PageChanger.loadMessageView({'messageType' : 'not_authorized'});
	</script>
<?php }
?>
<div id='faq-management-page' class='width-80' style='margin:0 auto;max-width:1000px' data-role='content'>
	<h1 id='title' style='display:inline-block'>Manage Frequently Asked Questions</h1>
	<a id='add-new-faq-button' href="#" data-role='button' onclick="openFAQDialog(0)" style='display:inline-block;'>Add a new FAQ</a>
	<hr>
	<h4>Below are the current frequently asked questions shown on the help page. You can modify them or delete them.</h4>
	<br>
	<?php foreach($faqs as $faq){?>
		<div class='faq-edit-container'>
			<div class='faq'>
				<h3 class='faq-question'><b><?php echo $faq->question;?></b></h3>
				<div class='small-break' style='height:5px'></div>
				<p class='faq-answer' style='margin-left:10px'><?php echo $faq->answer;?></p>
			</div>
			<div class='faq-edit-options'>
				<a class='faq-edit-button' id='edit-faq-button' href="#" data-role='button' onclick="openFAQDialog({edit:1, faqId:<?php echo $faq->id;?>})" data-icon='edit'></a>
				<a class='faq-edit-button' id='delete-faq-button' data-rel="popup" href="#popUp-<?php echo $faq->id;?>" data-role='button' data-icon='delete'></a>
			</div>
		</div>
		<div data-role="popup" data-shadow='true' data-position-to='window' data-overlay-theme='a' id="popUp-<?php echo $faq->id;?>" data-dismissible="false">
			<div data-role="header" role="banner" data-theme='a' class='ui-bar-a ui-header ui-corner-top'>
				<h1 role='heading' aria-level='1' class='ui-title'>Delete FAQ</h1>
			</div>
			<div data-role='content' role="main" data-theme='a' style='width:80%;margin:10px auto'>
				<h3 style='text-align:center'>Delete this FAQ?</h3>
				<p style='margin-top:10px;margin-bottom:20px;text-align:center'><i> "<?php echo $faq->question; ?>"</i></p>
				<form data-ajax='false' name='delete-faq-form' action="controllers/FAQManagementController.php" method="post" enctype="multipart/form-data">
					<input type='hidden' name='faq-action' value='delete-faq'/>	
					<input type='hidden' name='id' value='<?php echo $faq->id;?>'/>
					<input type='submit' name='delete' value='Delete'/>
				</form>
				<a href='#' data-rel='back' data-role='button'>Close</a>
			</div>
		</div>
		<hr>
		<br>
	<?php }?>
</div>

<script text="style/javascript">

function openFAQDialog(json){
	TINY.box.show({mask:true,url:'views/AddNewFAQ.php?edit='+json.edit+'&faqId='+json.faqId, 
		openjs:function(){
			$(document).trigger('create');
		},closejs:function(){
			$('.form-mask').remove();
		},width:Math.floor($(window).width()*.6),height:400})
}

</script>