<?php
require_once('../views/import/headimportviews.php');
session_start();
$user = $_SESSION ['user'];
$project = new ProjectModel($_GET['id']);

$deleted = false;
$added = false;
//make sure that only the owner of this project is editing it, protects
//against html injection
if($project->getProjectOwner()->getAccountId() === $user->getAccountId()){
	if(isset($_FILES['problem_image_uploader'])){
		$len = count($_FILES) - 1;


		//delete all files and start anew
		$files = glob("../projectfiles/{$project->getProjectId()}/*");
		foreach($files as $file){
				
			try {
				if(is_file($file)){
						
					$shouldDelete = true;
					foreach($_POST as $url){
						if($file == "../".$url){
							$shouldDelete = false;
						}
					}
					if($shouldDelete){
							unlink($file);

							$id = ORM::for_table('picture')->select('id')->where('url', substr($file, 3))->find_one();
							if(isset($id))
								ORM::for_table('picture')->find_one($id->id)->delete();
								ORM::for_table('project_has_picture')->where('picture_id', $id->id)->where('project_id', $project->getProjectId())->find_one()->delete();
								$deleted = TRUE;

						}
				}
			}catch (Warning $w) {
					
			}
		}


		//start from 1 for new images because first index is blank input
		foreach($_FILES as $file){

			if($file['name'] != ''){
				$allowedExts = array (
						"gif",
						"jpeg",
						"jpg",
						"png" 
						);



					 $temp = explode ( ".", $file["name"] );
					 $extension = end ( $temp );
					 $extension = strtolower($extension);
					 $errorMessage = '';
					 // maximum file size of 5 Mb
					 if ((($file["type"] == "image/gif")
					 || ($file["type"] == "image/jpeg")
					 || ($file["type"] == "image/jpg")
					 || ($file["type"] == "image/pjpeg")
					 || ($file["type"] == "image/x-png")
					 || ($file["type"] == "image/png"))
					 && ($file["size"] < 5000000) && in_array ( $extension, $allowedExts )) {
					 	if ($file["error"] > 0) {
					 		$errorMessage = $file["error"];
					 		$added = FALSE;
					 	} else {
					 		$newPicture = ORM::for_table('picture')->create();
					 		$newPicture->save();

					 		$newImageUrl = "projectfiles/{$project->getProjectId()}/project-{$project->getProjectId()}-image-{$newPicture->id}.".$extension;

					 		move_uploaded_file ( $file["tmp_name"], "../".$newImageUrl);

					 		$newPicture->url = $newImageUrl;
					 		$newPicture->save();

					 		$newRelation = ORM::for_table('project_has_picture')->create();
					 		$newRelation->is_profile_pic = 0;
					 		$newRelation->picture_id = $newPicture->id;
					 		$newRelation->project_id = $project->getProjectId();
					 		$newRelation->save();
					 		$added = TRUE;


					 	}
					 } else {
					 	$added = FALSE;
					 }
			}
		}

		if(!$deleted && !$added){
			header("Location: ../#ProjectView.php?id=".$project->getProjectId() ."&deleted=0&added=0");
		}else if($deleted && $added){// if added an image and delete at the same time
			header("Location: ../#ProjectView.php?id=".$project->getProjectId() ."&deleted=1&added=1");
		}else if($added){// if only added
			header("Location: ../#ProjectView.php?id=".$project->getProjectId() ."&added=1");
		}else if($deleted){// if only deleted
			header("Location: ../#ProjectView.php?id=".$project->getProjectId() ."&deleted=1");
		}
	}


	if (isset($_POST['project_description'])) {
		$project->setProjectDescription($_POST['project_description']);
	}

	if (isset($_POST['project_name'])) {
		$project->setProjectTitle($_POST['project_name']);
	}

	if(isset($_POST['project_progress'])) {
		$project->setStage1Progress($_POST['project_progress']);
	}
}
//$project->save();
header( 'Location: ../#ProjectView.php?id='.$_GET['id']);
