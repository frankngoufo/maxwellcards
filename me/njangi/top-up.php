<?php
require_once("sectionwide-page.inc.php");

Class TopUpPage extends NjangiSectionPage {
	private $amount;

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->amount = $_POST['mode'] == 'deposit' ? (int)$_POST['amount'] : (-1 * abs($_POST['amount']));

		$this->load_args['total'] = array(
			"SELECT SUM(amount) AS total FROM sinking_fund WHERE meetingId = :mI AND member = :m",
			array(':mI' => $_POST['meetingId'], ':m' => $_POST['member'])
		);
		$this->load_data['total'] = array();
		$this->load_stat['total'] = array();
		$this->dm->get_data($this->load_args['total'], $this->load_data['total'], $this->load_stat['total']);

	}

	public function save_data() {

		$this->save_args['top'] = array(
			array(
				"INSERT INTO sinking_fund SET member = ?, meetingId = ?, amount = ?, transaction_type = ?, details = ?",
				array($_POST['member'], $_POST['meetingId'], $this->amount, $_POST['mode'], $_POST['details'])
			)
		);
		$this->save_data['top'] = array();
		$this->save_stat['top'] = array();
		$this->dm->set_data( $this->save_stat['top'], $this->save_args['top'], $this->save_data['top']);

	}

	public function get_content() {
		$this->load_data();
		$this->get_interest($_POST['meetingId']); // Get top amount
		if (empty($this->check_db_errors($this->load_stat))) {

			// Esure what we're submitting doesn't surpass it or go below 0
			if ($this->amount + $this->load_data['total'][0]['total'] > $this->load_data['interest'][0]['sinking_fund_top']) {
				echo json_encode(array('above' => 1));
			} else if ($this->load_data['total'][0]['total'] + $this->amount < 0) {
				echo json_encode(array('below' => 1));
			} else {
				$this->save_data();

				if (empty($this->check_db_errors($this->load_stat))) {
					echo json_encode(array(
						'success' => 1
					));
				}

			}
		}
	}

}

if (API === TRUE) {
	$m = new TopUpPage();
	$m->get_content();
}