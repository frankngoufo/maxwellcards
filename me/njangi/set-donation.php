<?php
require_once("sectionwide-page.inc.php");

Class SetDonationPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['donations'] = array(
			"INSERT INTO donations(meetingId, name, description, created_by) VALUES(?,?,?,?)",
			array($_POST['meetingId'], $_POST['name'], $_POST['description'], $_SESSION['userId'])
		);
		$this->save_data['donations'] = array();
		$this->save_stat['donations'] = array();
		$this->dm->set_data( $this->save_stat['donations'], $this->save_args['donations'], $this->save_data['donations']);
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
	$m = new SetDonationPage();
	$m->get_content();
}