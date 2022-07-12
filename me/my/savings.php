<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MySavingsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['savings'] = array(
			"SELECT 
				(SELECT SUM(amount) FROM savings WHERE `user` = :user AND meetingId = :meetingId) AS total, 
			amount, date_saved FROM savings WHERE user = :user AND meetingId = :meetingId",
			array(':user' => $_SESSION['userId'], ':meetingId' => $_GET['meetingId'])
		);
		$this->load_data['savings'] = array();
		$this->load_stat['savings'] = array();
		$this->dm->get_data($this->load_args['savings'], $this->load_data['savings'], $this->load_stat['savings']);
	}

	public function get_content() {
		$this->get_interest($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'interest' 	=> $this->load_data['interest'][0],
				'savings' 	=> $this->load_data['savings']
			));
		}
	}

}

if (API === TRUE) {
	$m = new MySavingsPage();
	$m->get_content();
}