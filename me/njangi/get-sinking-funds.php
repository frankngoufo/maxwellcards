<?php
require_once("sectionwide-page.inc.php");

Class GetSinkingFundsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	protected function get_sinking_totals() {

		// Get user's random code to join meeting
		$this->load_args['total'] = array(
			"SELECT SUM(amount) AS totals FROM sinking_fund WHERE meetingId = ?",
			array($_GET['meetingId'])
		);
		$this->load_data['total'] = array();
		$this->load_stat['total'] = array();
		$this->dm->get_data($this->load_args['total'], $this->load_data['total'], $this->load_stat['total']);

	}

	public function get_content() {
		$this->get_sinking_totals();
		$this->get_user_meetings($_GET['meetingId']);
		$this->get_interest($_GET['meetingId']); // This also gets the sinking fund top amount

		if (empty($this->check_db_errors($this->load_stat))) {

			// top_amount = top per user times number of users
			echo json_encode(array(
				'user_meetings' 	=> $this->load_data['user_meetings'],
				'top_amount' 		=> $this->load_data['interest'][0]['sinking_fund_top'] * count($this->load_data['user_meetings']),
				'totals' 			=> $this->load_data['total'][0]['totals']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetSinkingFundsPage();
	$m->get_content();
}