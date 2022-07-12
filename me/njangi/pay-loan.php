<?php
require_once("sectionwide-page.inc.php");

Class PayLoanPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['repay'] = array(
			"INSERT INTO loans(user, meetingId, amount, saving_type) VALUES(?,?,?,'payment')",
			array($_POST['userId'], $_POST['meetingId'], -$_POST['amount'])
		);
		$this->save_data['repay'] = array();
		$this->save_stat['repay'] = array();
		$this->dm->set_data( $this->save_stat['repay'], $this->save_args['repay'], $this->save_data['repay']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["repay"])) {
			echo json_encode(array(
				'success' 	=> 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new PayLoanPage();
	$m->get_content();
}