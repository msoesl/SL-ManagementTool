<?php
require_once ('import/headimportviews.php');?>

<div class="float-center">
	<form id='security_question_request_form' data-ajax="false"
		action="controllers/ForgottenPasswordController.php" method='POST'>
	
		<div id="forgotten_password_view" class='forgotten_password_view simple-padding-small'>
			<div class='simple-padding-small standard-border title' id="box-div">
				<h3 class="margin-bottom-20">Enter your username</h3>
				<h6 style="font-size: .67em">We will use this to ask you the security
					question you provided us.</h6>
				<hr>
	
				<div class="message-container">
					<table>
						<tr>
							<td id="login-information-text-container">Username:</td>
							<td id="login-information-fields-container"><input type="text"
								data-theme="a" name="username" id='username'></td>
							<input type="hidden" name="formStep" value="requestSecurityQuestion"/>
						</tr>
						<tr>
							<td><input id="username_submit" type="submit" name="submit"
								value="Answer Your Question" data-theme="a"></td>
						</tr>
						<tr>
							<td id='error-message' colspan='2'><?php
							if (isset ( $_GET ['error'] )) {
								echo "Username does not exist";
							}
							?>
						</tr>
					</table>
				</div>
	
			</div>
	
		</div>
	</form>
	<form data-ajax='false' id='security_question_form'
		action='controllers/ForgottenPasswordController.php' method='POST'
		enctype="multipart/form-data" style='display:none'>
	
		<div id="security_question_view" class='forgotten_password_view simple-padding-small'>
			<div class='simple-padding-small standard-border title' id="box-div">
				<h3 class="margin-bottom-20">Security Question</h3>
				<h6 style="font-size: .67em">Answer your question correctly. We will then send you a new password you can use to log in</h6>
				<hr>
				<div class="message-container">
					<table>
						<tr>
							<td id="security_question">Security Question</td>
						</tr>
						<tr>
							<td id="login-information-fields-container"><input type="text"
								data-theme="a" name="securityQuestionResponse"
								id='securityQuestionResponse'></td>
							<input type="hidden" name="formStep" value="requestNewPassword"/>
							<input type="hidden" id="user_id" name="userId" value=""/>
						</tr>
						<tr>
							<td><input type="submit" name="submit" value="Retrieve Password" data-theme="a"></td>
						</tr>
						<tr>
							<td id='error-message' colspan='2'><?php
							if (isset ( $_GET ['error'] )) {
								echo 'Security question response was incorrect' ;
							}
							?>					
							
						
						</tr>
		
					</table>
		
				</div>
			</div>
		</div>
	</form>
</div>


<script type="text/javascript">

						//JQuery fadeIn

	var accountId;
	var question;
	var email;
	var dataJSON;
    $('#security_question_request_form').submit(function(e)
    {
           var postData = $(this).serializeArray();
           var formURL = $(this).attr("action");
           $("#security_question_request_form").fadeOut("fast");
           //$("#forgotten_password_loading").fadeIn("fast");
           $.ajax(
                 {
                    url : formURL,
                    type: "POST",
                    data : postData,
                    success:function(data, textStatus, jqXHR) 
                    {
                           console.log(data);
                           dataJSON = $.parseJSON(data);
                           accountId = dataJSON.id;
						   if(accountId != 0){
	                           question = dataJSON.question;
							   //$("#forgotten_password_loading").fadeOut("fast");
	                           $("#security_question_view #security_question").html(question);
	                           $("#security_question_view #user_id").val(accountId);
	                           $("#security_question_form").fadeIn("slow");
						   }
						   else{
	                           $("#security_question_request_form").fadeIn("slow");
							   
							   $("#forgotten_password_view #error-message").html("Username does not exist. Try again.");
						   }
                           
                    },
                    error: function(jqXHR, textStatus, errorThrown) 
                    {
                       //if fails      
                    }
          });
          e.preventDefault(); //STOP default action
  });

    $('#security_question_form').submit(function(e)
    	    {
	               var postData = $(this).serializeArray();
    	           var formURL = $(this).attr("action");
        		   $(this).fadeOut("fast");
        		   
    	           $.ajax(
    	                 {
    	                    url : formURL,
    	                    type: "POST",
    	                    data : postData,
    	                    success:function(data, textStatus, jqXHR) 
    	                    {    	                    
    	                           dataJSON = $.parseJSON(data);
    	                           email = dataJSON.email;
    	                           if(email != 'error'){        	                          
								   		PageChanger.loadMessageView({'messageType':'new_password_sent_confirm', 'email':email});
    	                           }
    	                           else{
    								   $("#security_question_view #error-message").html("The response was incorrect");
    	                    		   $("#security_question_form").fadeIn("fast");
    								}
    	                    },
    	                    error: function(jqXHR, textStatus, errorThrown) 
    	                    {
    	                       //if fails      
    	                    }
    	          });
    	          e.preventDefault(); //STOP default action
    	  });

</script>