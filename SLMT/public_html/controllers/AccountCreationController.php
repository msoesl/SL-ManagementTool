<?php
require_once('../views/import/headimportviews.php');
include("resize-class.php");

// Boolean check for username existence
$usernameExisted =FALSE;
// The max length of the password
$passwordMaxLength = 40;
// The min length of the password
$passwordMinLength = 8;
// The following are the User's inputs.
// Username
$userName = $_POST['userName'];
// Password
$password = $_POST['password'];
// Verifypassword
$verfiyPassword = $_POST['verfiyPassword'];
// First name
$firstName = $_POST['firstName'];
// Last Name
$lastName = $_POST['lastName'];
// Age
$age = $_POST['age'];
// Email
$email = $_POST['email'];
// Phone
$phoneNumber = $_POST['phoneNumber'];
// About Me
$about_me =  $_POST['about_me'];
// City
$city =  $_POST['city'];
// State
$state = $_POST['state'];
// Gender
$gender = $_POST['gender'];
//security question
$securityQuestion = $_POST['securityQuestion'];
//security response
$securityAnswer = $_POST['securityAnswer'];
//security response
$securityAnswerVerify = $_POST['securityAnswerRepeat'];
// private
$privateFlage = 1;
// Not private
$notPrivateFlag = 0;
// Yes meanes private profile
$YES = 'Yes';
// initial privileges that will be give to any registered user.
$initialSystemPrivilegesid = 1;

$sp = ORM::for_table('system_privileges')->create();
$sp->save();
$initialSystemPrivilegesid = $sp->id;

// Max-size of the uploaded image
$maxImageSize = 5242880; // 5 MB

// Default profile image path
$defaulImagetPath =  "profilefiles/default/user-img.jpg";

// Decalre an array to be returned with the results of the user's inputs.
$result = array();


// Function for basic field validation (present and neither empty nor only white space)
function IsNullOrEmptyString($question){
	return (!isset($question) || trim($question)==='');
}

// Check for the required fields. They must be non-empty
if(IsNullOrEmptyString($userName)){
	sendErrorCode('1');

}else if(IsNullOrEmptyString($password)){
	sendErrorCode('2');

}else if(IsNullOrEmptyString($verfiyPassword)){
	sendErrorCode('3');
	

}else if(IsNullOrEmptyString($firstName)){
	sendErrorCode('4');

}else if(IsNullOrEmptyString($lastName)){
	sendErrorCode('5');

}else if(IsNullOrEmptyString($email)){
	sendErrorCode('6');

	// Check if the password and verfiy password are the same
}else if(!($password === $verfiyPassword)){
	sendErrorCode('7');

	// Check for the length of the password, it must be > 8 && <40
}else if(strlen($password)<$passwordMinLength || strlen($password)>$passwordMaxLength){
	sendErrorCode('8');
	
// Check if the password and verfiy password are the same
}else if(!($securityAnswer === $securityAnswerVerify)){
	sendErrorCode('11');

}else if(strpos($email, '@msoe') === false){
	sendErrorCode('12');
} else {
	// Check if the user name already exists.

	// Get all existed accounts to check the entered username and emaill address if they are already used
	$accounts =ORM::for_table('account')->find_many();
	// Convert the username to lowercase
	$userName = strtolower($userName);
	// Check all accounts
	foreach ($accounts as $account){
		if($account->username === $userName){// Check the username
			sendErrorCode('9');
			$usernameExisted = TRUE;
		}else if($account->email_address === $email){// check the email
			sendErrorCode('10');
			$usernameExisted = TRUE;
		}
	}
	if(!$usernameExisted){// Means the name doesn't exist and all user inputs are valid, so create an account
		// Create the user profile
		$newProfile = ORM::for_table('profile')->create();

		// Get the user first name
		$newProfile->firstname = strip_tags($firstName);
		// Get the user last name
		$newProfile->lastname = strip_tags($lastName);

		// If any of these fields is filled, insert its value into the ORM object.
		if(!IsNullOrEmptyString($city)){
			$newProfile->city = strip_tags($city);
		}
		if(!IsNullOrEmptyString($state)){
			$newProfile->state = strip_tags($state);
		}
		if(!IsNullOrEmptyString($phoneNumber)){
			$newProfile->contact_number = strip_tags($phoneNumber);
		}
		if(!IsNullOrEmptyString($age)){
			$newProfile->age = strip_tags($age);
		}
		if(!IsNullOrEmptyString($gender)){
			$newProfile->gender = strip_tags($gender);
		}
		if(!IsNullOrEmptyString($about_me)){
			$newProfile->about_me = strip_tags($about_me);
		}

		//default new profile to not private
		$newProfile->is_private = $notPrivateFlag;
		
		$newProfile->save();

		// Get the url of the uploaded file
		$pic_url = uploadImage($newProfile->id);

		// Save the url for the user's image
		$newProfile->profile_pic_url = strip_tags($pic_url);
		// Save the user's profile
		$newProfile->save();



		// Create a new account
		$newUser = ORM::for_table('account')->create();
		// Get the user's username
		$newUser->username = strip_tags($userName);
		// Get the user's password
		$newUser->password = hash('sha256',strip_tags($password));
		// Get the profile id that just created
		$newUser->profile_id = $newProfile->id;

		// Get the email
		$newUser->email_address = strip_tags($email);
		//initial system privilages
		$newUser->System_Privileges_id = $initialSystemPrivilegesid;
		
		$newUser->security_question = strip_tags($securityQuestion);
		$newUser->security_answer = strip_tags($securityAnswer);

		//set a locked ID so they have to confirm to unlock
		$newUser->locked_code = rand(500, 10000000);
		
		// Save created account
		$newUser->save();

		if(isset($_SESSION['user'] )){
			session_destroy();
		}

		try {
			//send out email
			$emailAddress = $email;
			$emailMessage = 'Here\'s your activation code: '.$newUser->locked_code.' <br><br> Copy this and paste it <a href="www.msoeslmt.com/#AccountUnlockView.php?accountid='.$newUser->id.'">here</a> to get started!';

			$email = new EmailModel(
				$emailAddress, 
				'SMT Notification: Thanks for signing up!  Here\'s your activation code!',
				$emailMessage, 
				'no-reply@slmt.com');
			$email->sendEmail();


			//redirect to account unlock view for now
			//session_start();
			//$_SESSION['user'] = new AccountModel($newUser->username, $password);
			header( 'Location: ../#AccountUnlockView.php?accountid='.$newUser->id.'') ;
			
			
		}catch (Exception $e){
			// Exception is thrown.
			sendErrorCode('100');
		}
		


	}

}

/**
 * 
 * This function will reload the Account Creation View with passing 
 * the error code. The error messages are created and displayed at the Account Creation View.
 * @param String $errorMessageNum the code of the error.
 */
function sendErrorCode($errorMessageNum){
	header( 'Location: ../#AccountCreationView.php?error='.$errorMessageNum.'') ;
}

/**
 *
 * This function is responsible for uploading the profile's image.
 * IF no image is chosen or not valid, the defualt will be added.
 *
 * @param the profile id.
 */
function uploadImage($id){

	$path = "profilefiles/". $id ."/profile-". $id;
	if (!file_exists($path)) {
		mkdir("../profilefiles/". $id);
	}

	global $defaulImagetPath;
	global $maxImageSize;

	$profile_pic_url = $defaulImagetPath;

	$allowedExts = array("gif", "jpeg", "jpg", "png");
	$temp = explode(".", $_FILES["file"]["name"]);
	$extension = end($temp);
	if ((($_FILES["file"]["type"] == "image/gif")
	|| ($_FILES["file"]["type"] == "image/jpeg")
	|| ($_FILES["file"]["type"] == "image/jpg")
	|| ($_FILES["file"]["type"] == "image/pjpeg")
	|| ($_FILES["file"]["type"] == "image/x-png")
	|| ($_FILES["file"]["type"] == "image/png"))
	&& ($_FILES["file"]["size"] < $maxImageSize)
	&& in_array($extension, $allowedExts))
	{
		if (!($_FILES ["file"] ["error"] > 0)) {
			$imagePath = "profilefiles/". $id ."/profile-". $id. ".";
			// Add the uploaded file to the user's profile files
			move_uploaded_file ( $_FILES["file"]["tmp_name"],"../profilefiles/{$id}/profile-{$id}." . $extension );
			$profile_pic_url = $imagePath . $extension;
			
			imageResize("../profilefiles/{$id}/profile-{$id}." . $extension);
		}
	} else {
		// If the uploaded Image is not valid, do nothing
	}
	return $profile_pic_url;
}

function imageResize($imageURL){
	
		
		list($width, $height, $type, $attr) = getimagesize($imageURL);

		
		if($width > 400 || $height>400){// if either of the image demensions are greater than 400, resize the image

			// *** 1) Initialise / load image
			$resizeObj = new resize($imageURL);

			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage(300, 300, 'crop');

			// *** 3) Save image
			$resizeObj -> saveImage($imageURL, 100);
		}
			
	
}

		