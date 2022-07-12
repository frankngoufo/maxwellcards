<?php
require_once("sectionwide-page.inc.php");

Class CreateProjectPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['project'] = array(
			"INSERT INTO projects(name, description, amount, meetingId, created_by) VALUES(?,?,?,?,?)",
			array($_POST['name'], $_POST['description'], $_POST['amount'], $_POST['meetingId'], $_SESSION['userId'])
		);
		$this->save_data['project'] = array();
		$this->save_stat['project'] = array();
		$this->dm->set_data( $this->save_stat['project'], $this->save_args['project'], $this->save_data['project']);
	}

	public function get_content() {
		$this->save_data();
		if (empty($this->check_db_errors($this->save_stat))) {
			echo json_encode(array(
				'success' => 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new CreateProjectPage();
	$m->get_content();
}