<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MySFPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['sinking_fund'] = array(
			"SELECT * FROM sinking_fund WHERE meetingId = ?",
			array($_GET['meetingId'])
		);
		$this->load_data['sinking_fund'] = array();
		$this->load_stat['sinking_fund'] = array();
		$this->dm->get_data($this->load_args['sinking_fund'], $this->load_data['sinking_fund'], $this->load_stat['sinking_fund']);

		$this->load_args['user_sinking_fund'] = array(
			"SELECT * FROM user_sinking_fund WHERE meetingId = ? AND contributor = ?",
			array($_GET['meetingId'], $_SESSION['userId'])
		);
		$this->load_data['user_sinking_fund'] = array();
		$this->load_stat['user_sinking_fund'] = array();
		$this->dm->get_data($this->load_args['user_sinking_fund'], $this->load_data['user_sinking_fund'], $this->load_stat['user_sinking_fund']);
	}

	public function get_content() {
		$this->load_data();

		// Normalize
		// First, set all my sinking funds in an array with keys being id of sinking fund
		$my_sinking_fund = [];
		foreach ($this->load_data['user_sinking_fund'] as $usf) {
			$my_sinking_fund[$usf['sfId']] = $usf;
		}

		// Then for all sinking funds, if current user has contributed, populate it with contribution date and amount
		$sinking_funds = array(); $num = 0;
		foreach ($this->load_data['sinking_fund'] as $sf) {
			$sinking_funds[$num] = $sf;
			if (array_key_exists($sf['id'], $my_sinking_fund)) {
				$sinking_funds[$num]['contr_date'] = $my_sinking_fund[$sf['id']]['contr_date'];
			}
			$num++;
		}

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'sinking_funds' 	=> $sinking_funds
			));
		}
	}

}

if (API === TRUE) {
	$m = new MySFPage();
	$m->get_content();
}