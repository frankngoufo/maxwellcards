<?php
require_once("sectionwide-page.inc.php");

Class WithdrawPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['withdraw'] = array(
			"INSERT INTO savings(user, meetingId, amount, saving_type) VALUES(?,?,?,'withdrawal')",
			array($_POST['userId'], $_POST['meetingId'], -$_POST['amount'])
		);
		$this->save_data['withdraw'] = array();
		$this->save_stat['withdraw'] = array();
		$this->dm->set_data( $this->save_stat['withdraw'], $this->save_args['withdraw'], $this->save_data['withdraw']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["withdraw"])) {
			echo json_encode(array(
				'success' 	=> 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new WithdrawPage();
	$m->get_content();
}