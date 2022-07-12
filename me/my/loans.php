<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MyLoansPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['loans'] = array(
			"SELECT 
				(SELECT SUM(amount) FROM loans WHERE date_paid IS NULL AND `user` = :user AND meetingId = :meetingId) AS total, 
			amount, date_saved, date_paid, id FROM loans WHERE user = :user AND meetingId = :meetingId",
			array(':user' => $_SESSION['userId'], ':meetingId' => $_GET['meetingId'])
		);
		$this->load_data['loans'] = array();
		$this->load_stat['loans'] = array();
		$this->dm->get_data($this->load_args['loans'], $this->load_data['loans'], $this->load_stat['loans']);
	}

	public function get_content() {
		$this->get_interest($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'interest' 	=> $this->load_data['interest'][0],
				'loans' 	=> $this->load_data['loans']
			));
		}
	}

}

if (API === TRUE) {
	$m = new MyLoansPage();
	$m->get_content();
}