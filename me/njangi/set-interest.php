<?php
require_once("sectionwide-page.inc.php");

Class SetInterestPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$row = $_POST['mode'] == 'savings' ? 'savings_interest' : 'loans_interest';

		$this->save_args['interest'] = array(
			"UPDATE meeting_defaults SET $row = ? WHERE meetingId = ?",
			array($_POST['interst_rate'], $_POST['meetingId'])
		);
		$this->save_data['interest'] = array();
		$this->save_stat['interest'] = array();
		$this->dm->set_data( $this->save_stat['interest'], $this->save_args['interest'], $this->save_data['interest']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["interest"])) {
			echo json_encode(array(
				'success' 	=> 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new SetInterestPage();
	$m->get_content();
}