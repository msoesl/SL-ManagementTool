<?php
require_once('../views/import/headimportviews.php');
include("resize-class.php");
session_start();
$user = $_SESSION ['user'];

$allowedExts = array (
		"gif",
		"jpeg",
		"jpg",
		"png",
		"JPG",
		"JPEG",
		"PNG",
		"GIF"
);
$temp = explode ( ".", $_FILES ["file"] ["name"] );
$extension = end ( $temp );
$extension = strtolower ($extension);
$errorMessage = '';
// maximum file size of 5 Mb
if ((($_FILES ["file"] ["type"] == "image/gif") 
		|| ($_FILES ["file"] ["type"] == "image/jpeg") 
		|| ($_FILES ["file"] ["type"] == "image/jpg") 
		|| ($_FILES ["file"] ["type"] == "image/pjpeg") 
		|| ($_FILES ["file"] ["type"] == "image/x-png") 
		|| ($_FILES ["file"] ["type"] == "image/png")) 
		&& ($_FILES ["file"] ["size"] < 5000000) && in_array ( $extension, $allowedExts )) {
	if ($_FILES ["file"] ["error"] > 0) {
		$errorMessage = $_FILES ["file"] ["error"];
	} else {
		if(file_exists("../".$user->getProfilePicUrl())){
			unlink("../".$user->getProfilePicUrl());
		}

		if(!file_exists("../profilefiles/{$user->getProfile()->id}")){
			mkdir("../profilefiles/{$user->getProfile()->id}", 0777, true);
		}

		move_uploaded_file ( $_FILES ["file"] ["tmp_name"], "../profilefiles/{$user->getProfile()->id}/profile-{$user->getProfile()->id}.". $extension );
		$profile = $user->refreshProfile();
		$profile->profile_pic_url = "profilefiles/{$user->getProfile()->id}/profile-{$user->getProfile()->id}.". $extension;
		$profile->save();
		imageResize("../". $profile->profile_pic_url);

	}
}
header( 'Location: ../#PersonalProfileView.php?id='.$user->getAccountId());


function imageResize($imageURL){
	
		list($width, $height, $type, $attr) = getimagesize($imageURL);

		
		if($width > 400 || $height>400){// if either of the image demensions are greater than 400, resize the image
		
			// *** 1) Initialise / load image
			$resizeObj = new resize($imageURL);

			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage(400, 400, 'crop');

			// *** 3) Save image
			$resizeObj -> saveImage($imageURL, 100);
		}
			
	
}
?>

