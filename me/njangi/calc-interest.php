<?php
require_once("sectionwide-page.inc.php");

Class CalcInterestPage extends NjangiSectionPage {
	private $interest;
	private $interest_rate;

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {

		// Get total savings and loans of current user. Used to calculate interest for 
		// Savings or loans, depending on the case
		$this->load_data['total'] = array();
		$this->load_stat['total'] = array();

		// Only query arguments change depening on mode (savings or loan)
		if ($_POST['mode'] == 'savings') {
			$this->load_args['total'] = array(
				"SELECT SUM(amount) AS totals FROM savings WHERE user = ? AND meetingId = ?",
				array($_POST['userId'], $_POST['meetingId'])
			);
		} else if ($_POST['mode'] == 'loans') {
			$this->load_args['total'] = array(
				"SELECT SUM(amount) AS totals FROM loans WHERE user = ? AND meetingId = ?",
				array($_POST['userId'], $_POST['meetingId'])
			);
		}
		
		$this->dm->get_data($this->load_args['total'], $this->load_data['total'], $this->load_stat['total']);


	}

	public function save_data() {
		$table = $_POST['mode'] == 'savings' ? 'savings' : 'loans'; // Don't pass submitted data as name of table, even though got same name

		$this->save_args['interest'] = array(
			"INSERT INTO $table(user, meetingId, amount, saving_type) VALUES(?,?,?,?)",
			array($_POST['userId'], $_POST['meetingId'], (int)$this->interest, 'interest') // (int) in case it's null
		);
		$this->save_data['interest'] = array();
		$this->save_stat['interest'] = array();
		$this->dm->set_data( $this->save_stat['interest'], $this->save_args['interest'], $this->save_data['interest']);
	}

	public function get_content() {
		$this->get_interest($_POST['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			$i = $_POST['mode'] == 'savings' ? 'savings_interest' : 'loans_interest';
			$interest_rate = $this->load_data['interest'][0][$i]; // First get interest rate
			$total = $this->load_data['total'][0]['totals']; // Now total savings/loans

			// Calculate interest rate
			$this->interest = ($interest_rate / 100) * $total;
			
			$this->save_data();

			if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["interest"])) {
				echo json_encode(array(
					'success' 	=> 1
				));
			}
		}
			
	}

}

if (API === TRUE) {
	$m = new CalcInterestPage();
	$m->get_content();
}