<?php
require_once("sectionwide-page.inc.php");

Class SFDetailsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['sinking_funds'] = array(
			"SELECT * FROM user_sinking_fund WHERE sfId = ?",
			array($_GET['sfId'])
		);
		$this->load_data['sinking_funds'] = array();
		$this->load_stat['sinking_funds'] = array();
		$this->dm->get_data($this->load_args['sinking_funds'], $this->load_data['sinking_funds'], $this->load_stat['sinking_funds']);
	}

	public function get_content() {
		$this->get_user_meetings($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {

			// Normalize

			// First put all sinking fund details in arrays with keys being the contributor
			$sfs = array();
			foreach ($this->load_data['sinking_funds'] as $sf) {
				$sfs[$sf['contributor']] = $sf;
			}

			// Then for each user, check if a donor exists from previous array, and if it does,
			// assign their donations to them
			$users = array(); $num = 0;
			foreach ($this->load_data['user_meetings'] as $user) {
				$users[$num] = $user;
				if (array_key_exists($user['id'], $sfs)) {
					$users[$num]['contr_date'] = $sfs[$user['id']]['contr_date'];
				}
				$num++;
			}
			echo json_encode(array(
				'users' 	=> $users
			));
		}
	}

}

if (API === TRUE) {
	$m = new SFDetailsPage();
	$m->get_content();
}