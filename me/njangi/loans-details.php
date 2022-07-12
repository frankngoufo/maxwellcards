<?php
require_once("sectionwide-page.inc.php");

Class LoansDetails extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['loans'] = array(
			"SELECT *,
				(SELECT SUM(amount) FROM loans WHERE user = :member AND meetingId = :meetingId) AS total,
				(SELECT interest FROM savings_interest WHERE meetingId = :meetingId AND userId = :member) AS interest
				FROM loans WHERE user = :member AND meetingId = :meetingId",
			array(':member' => $_GET['member'], ':meetingId' => $_GET['meetingId'])
		);
		$this->load_data['loans'] = array();
		$this->load_stat['loans'] = array();
		$this->dm->get_data($this->load_args['loans'], $this->load_data['loans'], $this->load_stat['loans']);
	}

	public function get_content() {
		$this->get_user_names($_GET['member']); // Get names of member whose loans we're viewing
		$this->load_data();
		$this->get_interest($_GET['meetingId']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'interest' 	=> $this->load_data['interest'][0],
				'loans' => $this->load_data['loans'],
				'member_names' => $this->load_data['user_names'][0]['names']
			));
		}
	}

}

if (API === TRUE) {
	$m = new LoansDetails();
	$m->get_content();
}