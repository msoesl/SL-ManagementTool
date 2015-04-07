<?php
/**
 * Picture model allows pictures to be retrieved from the database
 * @author stewarte
 * @depreciated!
 */
class PictureModel{

	/**
	 * The ORM picture model object
	 */
	private $pictureModel;

	/**
	 * Constructs a picture model.
	 *
	 * @param int $pictureId
	 */
	private function __construct(ORM $model){
		$this->pictureModel = $model;
	}

	/**
	 * Returns the relative url (relative to index.php) of the picture model
	 */
	public function getProfilePictureRelUrl() {
		return $this->pictureModel->url;
	}

	/**
	 * Get's the ID of the picture model.
	 *
	 * @return int Picture model id
	 */
	public function getId(){
		return $this->pictureModel->id;
	}

	/**
	 * Factory method that gets all of PictureModels for a given project, where the profile picture is always the first item in the array.
	 * @param int $projectId
	 * @return array of PictureModels
	 */
	public static function getAllPicturesForProject($projectId) {
		$banners = ORM::for_table('project_banner')
		->where('project_id', $projectId)
		->where('enabled',1)
		->order_by_asc('sort_order')
		->find_many();
		return $banners;
	}
}