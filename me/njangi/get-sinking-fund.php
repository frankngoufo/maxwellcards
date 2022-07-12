<?php
require_once("sectionwide-page.inc.php");

Class GetSinkingFundPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {

		// Get user's random code to join meeting
		$this->load_args['sinking_funds'] = array(
			"SELECT *, 
			(SELECT SUM(amount) FROM sinking_fund WHERE meetingId = :mI AND member = :m) AS balance 
			FROM sinking_fund WHERE meetingId = :mI AND member = :m",
			array(':mI' => $_GET['meetingId'], ':m' => $_GET['member'])
		);
		$this->load_data['sinking_funds'] = array();
		$this->load_stat['sinking_funds'] = array();
		$this->dm->get_data($this->load_args['sinking_funds'], $this->load_data['sinking_funds'], $this->load_stat['sinking_funds']);

	}

	public function get_content() {
		$this->get_interest($_GET['meetingId']); // This also gets the sinking fund top amount
		$this->load_data();
		$this->get_user_names($_GET['member']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'top_amount' 		=> $this->load_data['interest'][0]['sinking_fund_top'],
				'sinking_funds'		=> $this->load_data['sinking_funds'],
				'user_names'		=> $this->load_data['user_names'][0]['names']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetSinkingFundPage();
	$m->get_content();
}