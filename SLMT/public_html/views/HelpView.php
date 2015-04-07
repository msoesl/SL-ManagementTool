<?php 
require_once ('import/headimportviews.php');
$faqs = ORM::for_table("frequently_asked_questions")->find_many();
?>
<div class='width-80' style='margin:0 auto;max-width:1000px' data-role='content'>
	<h1 id='title'>Frequently Asked Questions</h1>
	<hr>
	<h4>Please use the following frequently asked questions to assist you in using the SMT. If your question is not answered here, please click <a href='#new-question-popup' data-rel='popup'>here</a> to let us know how we can help.</h4>	
	<br>
	<?php foreach($faqs as $faq){?>
		<div class='faq-view'>
			<h3 class='faq-question'><b><?php echo $faq->question;?></b></h3>
			<p class='faq-answer' style='margin-left:10px'><?php echo $faq->answer;?></p>
			<br>
		</div>
	<?php }?>
</div>
    <div data-role="popup" id="new-question-popup" data-position-to='window'>
        <div data-role="header" title="Edit" data-theme="f"
            class="ui-corner-top ui-header ui-bar-a" role="banner">
            <h1 class="ui-title">Contact Us</h1>
        </div>
        <div class='simple-padding-medium'>
            <form id = 'new_event_form'
                data-ajax='false' method='POST' 
                action = 'controllers/FAQManagementController.php'>
                <span>Your Email:</span><input type="text" name="user-email" id="user-email" required></input>
                <span>Question:</span><textarea name="user-question" id='user-question' data-theme="f" required></textarea>
                <input type='hidden' name='faq-action' value='new-question'/>
                <div class='ui-grid-a'>
                    <div class='ui-block-a'></div>
                    <div class='ui-block-b'>
                        <input type="submit" name="submit" value="Submit" data-theme="a">
                    </div>
                </div>
            </form>
        </div>
    </div>
