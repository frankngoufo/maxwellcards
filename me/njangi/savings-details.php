<?php
require_once("sectionwide-page.inc.php");

Class SavingsDetailsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['savings'] = array(
			"SELECT *,
				(SELECT SUM(amount) FROM savings WHERE user = :member AND meetingId = :meetingId) AS total,
				(SELECT interest FROM savings_interest WHERE meetingId = :meetingId AND userId = :member) AS interest
				FROM savings WHERE user = :member AND meetingId = :meetingId",
			array(':member' => $_GET['member'], ':meetingId' => $_GET['meetingId'])
		);
		$this->load_data['savings'] = array();
		$this->load_stat['savings'] = array();
		$this->dm->get_data($this->load_args['savings'], $this->load_data['savings'], $this->load_stat['savings']);
	}

	public function get_content() {
		$this->get_user_names($_GET['member']); // Get names of member whose savings we're viewing
		$this->load_data();
		$this->get_interest($_GET['meetingId']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'interest' 	=> $this->load_data['interest'][0],
				'savings' => $this->load_data['savings'],
				'member_names' => $this->load_data['user_names'][0]['names']
			));
		}
	}

}

if (API === TRUE) {
	$m = new SavingsDetailsPage();
	$m->get_content();
}