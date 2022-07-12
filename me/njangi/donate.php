<?php
require_once("sectionwide-page.inc.php");

Class DonatePage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['donation'] = array(
			"INSERT INTO user_donations(donor, donationId, amount, meetingId, description) VALUES(?,?,?,?,?)",
			array($_POST['donor'], $_POST['donationId'], $_POST['amount'], $_POST['meetingId'], $_POST['description'])
		);
		$this->save_data['donation'] = array();
		$this->save_stat['donation'] = array();
		$this->dm->set_data( $this->save_stat['donation'], $this->save_args['donation'], $this->save_data['donation']);
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
	$m = new DonatePage();
	$m->get_content();
}