<?php
require_once("sectionwide-page.inc.php");

Class GetInterestPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}


	public function load_data() {
		$this->load_args['last_interest'] = array(
			"SELECT date_saved FROM savings WHERE meetingId = ? AND saving_type = 'interest'
			ORDER BY id DESC LIMIT 1",
			array($_GET['meetingId'])
		);
		$this->load_data['last_interest'] = array();
		$this->load_stat['last_interest'] = array();
		$this->dm->get_data($this->load_args['last_interest'], $this->load_data['last_interest'], $this->load_stat['last_interest']);
	}

	public function get_content() {
		$this->get_interest($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'interest' => $this->load_data['interest'][0],
				'last_interest'	=> empty($this->load_data['last_interest'][0]) ? NULL : $this->load_data['last_interest'][0]['date_saved']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetInterestPage();
	$m->get_content();
}