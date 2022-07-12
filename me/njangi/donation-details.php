<?php
require_once("sectionwide-page.inc.php");

Class DonationDetailsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['donations'] = array(
			"SELECT names, donation_date, amount, description FROM user_donations 
			INNER JOIN users ON donor = users.id
			WHERE donationId = ?",
			array($_GET['donationId'])
		);
		$this->load_data['donations'] = array();
		$this->load_stat['donations'] = array();
		$this->dm->get_data($this->load_args['donations'], $this->load_data['donations'], $this->load_stat['donations']);
	}

	public function get_content() {
		$this->get_user_meetings($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			/*
			// Normalize

			// First put all donation details in arrays with keys being the donor
			$donations = array();
			foreach ($this->load_data['donations'] as $donation) {
				$donations[$donation['donor']] = $donation;
			}

			// Then for each user, check if a donor exists from previous array, and if it does,
			// assign their donations to them
			
			$users = array(); $num = 0;
			foreach ($this->load_data['user_meetings'] as $user) {
				$users[$num] = $user;
				if (array_key_exists($user['id'], $donations)) {
					$users[$num]['donation_date'] = $donations[$user['id']]['donation_date'];
					$users[$num]['amount'] = $donations[$user['id']]['amount'];
				}
				$num++;
			}
			*/

			echo json_encode(array(
				'donations' 	=> $this->load_data['donations'],
				'users'			=> $this->load_data['user_meetings']
			));
		}
	}

}

if (API === TRUE) {
	$m = new DonationDetailsPage();
	$m->get_content();
}