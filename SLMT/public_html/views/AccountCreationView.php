<?php
require_once ('import/headimportviews.php');




?>
<form data-ajax='false' id='account-creation-form'
	action='controllers/AccountCreationController.php' method='POST'
	enctype="multipart/form-data" onsubmit="return doCheck('phoneNumber');">

	<div id="account-creation-view" class='simple-padding-small'>
		<div class='simple-padding-small standard-border message-container'
			id="box-div">


			<h3>
				Please fill in the following fields below in order to create an
				account. <BR> Fields marked with a (*) are required.
			</h3>
			<?php
			if (isset($_GET['error'])){?>
			<BR> <BR>
			<div id='error-message'>
			<?php

			echo getErrorMessage($_GET['error']);?>
			</div>
			<?php }?>


		</div>




		<div class='simple-padding-small standard-border title' id="box-div">
			<h3>Login Information</h3>
			<hr>

			<div class="message-container">
				<table>

					<tr>
						<td id="login-information-text-container">User Name*:</td>
						<td id="login-information-fields-container"><input type="text"
							data-theme="a" name="userName" id='userName'>
						</td>
					</tr>
					<tr>
						<td id="login-information-text-container">Password*:</td>
						<td id="login-information-fields-container"><input type="password"
							data-theme="a" name="password" id='password'>
						</td>
					</tr>
					<tr>
						<td id="login-information-text-container">Verfiy Password*:</td>
						<td id="login-information-fields-container"><input type="password"
							data-theme="a" name="verfiyPassword" id='verfiyPassword'>
						</td>
					</tr>


				</table>

			</div>

		</div>


		<div class='simple-padding-small standard-border title' id="box-div">

			<h3>Personal Information</h3>
			<hr>

			<div class="message-container" id='personal-info-table'>



				<table>
					<tr>
						<td id="text-container">First Name*:</td>
						<td id="fields-container"><input type="text"
							data-theme="a"
							name="firstName" id='firstName'>
						</td>
					</tr>
					<tr>
						<td id="text-container">Last Name*:</td>
						<td id="fields-container"><input type="text"
							data-theme="a"
							name="lastName" id='lastName'>
						</td>
					</tr>
					<tr>
						<td id="text-container">Phone Number:</td>
						<td id="fields-container"><input type="text"
							data-theme="a"
						 name="phoneNumber" id='phoneNumber'>
						</td>
					</tr>
					<tr>
						<td id="text-container">Age:</td>
						<td id="fields-container"><input value="" type="number" name="age"
							id='age' min="0" data-theme="a" name="age" id='age'>
						</td>
					</tr>
					<tr>
						<td id="text-container">Gender:</td>
						<td id="fields-container"><select value="Female" type="text"
							name="gender" id='gender'data-theme="a">
								<option value="Female">Female</option>
								<option value="Male">Male</option>
						</select>
						</td>
					</tr>
					<tr>
						<td id="text-container">Email*:</td>
						<td id="fields-container"><input type="email"
							data-theme="a"
							name="email" id='email'>
						</td>
					</tr>
					<tr>
						<td id="text-container">City:</td>
						<td id="fields-container"><input type="text"
							data-theme="a"
							name="city" id='city'>
						</td>
					</tr>
					<tr>
						<td id="text-container">State:</td>
						<td id="fields-container"><select value="Alabama" type="text"
							name="state" id='state'data-theme="a">
								<option value="AL">Alabama</option>
								<option value="AK">Alaska</option>
								<option value="AZ">Arizona</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>
								<option value="CT">Connecticut</option>
								<option value="DE">Delaware</option>
								<option value="FL">Florida</option>
								<option value="GA">Georgia</option>
								<option value="HI">Hawaii</option>
								<option value="ID">Idaho</option>
								<option value="IL">Illinois</option>
								<option value="IN">Indiana</option>
								<option value="IA">Iowa</option>
								<option value="KS">Kansas</option>
								<option value="KY">Kentucky</option>
								<option value="LA">Louisiana</option>
								<option value="ME">Maine</option>
								<option value="MD">Maryland</option>
								<option value="MA">Massachusetts</option>
								<option value="MI">Michigan</option>
								<option value="MN">Minnesota</option>
								<option value="MS">Mississippi</option>
								<option value="MO">Missouri</option>
								<option value="MT">Montana</option>
								<option value="NE">Nebraska</option>
								<option value="NV">Nevada</option>
								<option value="NH">New Hampshire</option>
								<option value="NJ">New Jersey</option>
								<option value="NM">New Mexico</option>
								<option value="NY">New York</option>
								<option value="NC">North Carolina</option>
								<option value="ND">North Dakota</option>
								<option value="OH">Ohio</option>
								<option value="OK">Oklahoma</option>
								<option value="OR">Oregon</option>
								<option value="PA">Pennsylvania</option>
								<option value="RI">Rhode Island</option>
								<option value="SC">South Carolina</option>
								<option value="SD">South Dakota</option>
								<option value="TN">Tennessee</option>
								<option value="TX">Texas</option>
								<option value="UT">Utah</option>
								<option value="VT">Vermont</option>
								<option value="VA">Virginia</option>
								<option value="WA">Washington</option>
								<option value="WV">West Virginia</option>
								<option value="WI">Wisconsin</option>
								<option value="WY">Wyoming</option>
						</select>
						</td>
					</tr>

					
					<tr>
						<td>Profile Picture:</td>
						<td><input type="file" name="file" id='file'data-theme="a" onclick="return pictureConfirmation()">
						</td>
						
					</tr>
					
					<tr>
						<td id="text-container">About Me:</td>
						<td id="text-area"><textarea type="textarea" name="about_me"
								id='about_me'data-theme="a">About Me</textarea>
						</td>
					</tr>


				</table>


			</div>
		</div>

		<div class='simple-padding-small standard-border title' id="box-div">
			<h3>Password Recovery</h3>
			<h6 style="font-size: .67em">Create your own security question and
				answer. When you indicate you forgot your password, you will be
				asked this question for verification.</h6>
			<hr>

			<div class="message-container">
				<table>
					<tr>
						<td id="login-information-text-container">Security Question*:</td>
						<td id="login-information-fields-container"><input type="text"
							data-theme="a" name="securityQuestion" id='securityQuestion'>
						</td>
					</tr>
					<tr>
						<td id="login-information-text-container">Answer*:</td>
						<td id="login-information-fields-container"><input type="password"
							data-theme="a" name="securityAnswer" id='securityAnswer'>
						</td>
					</tr>
					<tr>
						<td id="login-information-text-container">Repeat Answer*:</td>
						<td id="login-information-fields-container"><input type="password"
							data-theme="a" name="securityAnswerRepeat"
							id='securityAnswerRepeat'>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="submit" value="Create Account" data-theme="a">
						</td>

					</tr>
				</table>

			</div>
		</div>

	</div>
</form>
			<?php 
			// Return the correct error message based on the error code.
			// The error is caught in the Controller.
			function getErrorMessage($num){

				switch ($num){

					case '1':
						return 'The user name is empty.';
					case '2':
						return 'The password is empty';
					case '3':
						return 'The verify password is empty';
					case '4':
						return 'The first name is empty.';
					case '5':
						return 'The last name is empty.';
					case '6':
						return 'The email is empty.';
					case '7':
						return 'The passwords are NOT the same.';
					case '8':
						return 'The password must be between 8 and 40 long.';
					case '9':
						return 'The username already exists.';
					case '10':
						return 'The email already exists.';
					case '11':
						return 'The given security responses do not match.';
						// 100 For login exception
					case '100':
						return 'You could not login to your account. Try again.';




				}
			}

			?>
<script type="text/javascript">

function doCheck(field) {
if (isNaN(document.getElementById(field).value)) {
alert('This is not a number! Please enter a valid number before submitting the form.');
document.getElementById(field).focus();
document.getElementById(field).select(); 
return false;
}

else {
return true;
}
}

function pictureConfirmation() {

	return alert("The maximum size is 5 MB. If your image is greater, a default picture will be your picture."
			+" The maximum width and height must be 400. If it is not, it will be resized.");
	
	}
</script>
