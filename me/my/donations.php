<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MyDonationsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['donations'] = array(
			"SELECT * FROM donations WHERE meetingId = ?",
			array($_GET['meetingId'])
		);
		$this->load_data['donations'] = array();
		$this->load_stat['donations'] = array();
		$this->dm->get_data($this->load_args['donations'], $this->load_data['donations'], $this->load_stat['donations']);

		$this->load_args['user_donations'] = array(
			"SELECT * FROM user_donations WHERE meetingId = ? AND donor = ?",
			array($_GET['meetingId'], $_SESSION['userId'])
		);
		$this->load_data['user_donations'] = array();
		$this->load_stat['user_donations'] = array();
		$this->dm->get_data($this->load_args['user_donations'], $this->load_data['user_donations'], $this->load_stat['user_donations']);
	}

	public function get_content() {
		$this->load_data();

		// Normalize
		// First, set all my donations in an array with keys being id of donation
		$my_donations = [];
		foreach ($this->load_data['user_donations'] as $ud) {
			$my_donations[$ud['donationId']] = $ud;
		}

		// Then for all donations, if current user has donated, populate it with donation date and amount
		$donations = array(); $num = 0;
		foreach ($this->load_data['donations'] as $donation) {
			$donations[$num] = $donation;
			if (array_key_exists($donation['id'], $my_donations)) {
				$donations[$num]['donation_date'] = $my_donations[$donation['id']]['donation_date'];
				$donations[$num]['amount'] = $my_donations[$donation['id']]['amount'];
			}
			$num++;
		}

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'donations' 	=> $donations
			));
		}
	}

}

if (API === TRUE) {
	$m = new MyDonationsPage();
	$m->get_content();
}