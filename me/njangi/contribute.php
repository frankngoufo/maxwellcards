<?php
require_once("sectionwide-page.inc.php");

Class ContributePage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['contribute'] = array(
			"INSERT INTO project_contributions (projectId, meetingId, userId) VALUES(?,?,?)",
			array($_POST['projectId'], $_POST['meetingId'], $_POST['contributor'])
		);
		$this->save_data['contribute'] = array();
		$this->save_stat['contribute'] = array();
		$this->dm->set_data( $this->save_stat['contribute'], $this->save_args['contribute'], $this->save_data['contribute']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["contribute"])) {
			echo json_encode(array(
				'success' 	=> 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new ContributePage();
	$m->get_content();
}