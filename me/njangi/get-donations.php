<?php
require_once("sectionwide-page.inc.php");

Class GetDonationsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['donations'] = array(
			"SELECT * FROM donations WHERE meetingId = ?",
			array($_GET['meetingId'])
		);
		$this->load_data['donations'] = array();
		$this->load_stat['donations'] = array();
		$this->dm->get_data($this->load_args['donations'], $this->load_data['donations'], $this->load_stat['donations']);
	}

	public function get_content() {
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'donations' 	=> $this->load_data['donations']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetDonationsPage();
	$m->get_content();
}