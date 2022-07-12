<?php
require_once("sectionwide-page.inc.php");

Class CreateRoundsPage extends NjangiSectionPage {
	private $roundId;

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['create'] = array(
			"INSERT INTO njangi_rounds(round_name, meetingId, amount, fine) VALUES(?,?,?,?)",
			array($_POST['round_name'], $_POST['meetingId'], $_POST['amount'], $_POST['fine'])
		);
		$this->save_data['create'] = array();
		$this->save_stat['create'] = array();
		$this->dm->set_data( $this->save_stat['create'], $this->save_args['create'], $this->save_data['create']);
		
	}

	private function set_hosts() {

		// Normalize hosts and dates
		$query = array();
		foreach (json_decode($_POST['dates']) as $key => $date) {

			// Create hosts for current round
			$query[] = array(
				"INSERT INTO njangi_round_hosts(host, host_date, roundId) VALUES(?,?,?)",
				array($key, $date, $this->roundId)
			);

			// Create sessions for each host
			foreach (json_decode($_POST['dates']) as $player => $nnnn) {
				$query[] = array(
					"INSERT INTO njangi_sessions(playerId, roundId, hostId) VALUES(?,?,?)",
					array($player, $this->roundId, $key)
				);
			}
		}
		$this->save_args['hosts'] = $query;
		$this->save_data['hosts'] = array();
		$this->save_stat['hosts'] = array();
		$this->dm->set_data( $this->save_stat['hosts'], $this->save_args['hosts'], $this->save_data['hosts']);
	}

	public function get_content() {
		$this->save_data(); // Save round details
		if (empty($this->check_db_errors($this->save_stat))) {
			$this->roundId = $_SESSION['lastInsertId']; // Get lastInsertId
			$this->set_hosts(); // Save all hosts and their dates

			if (empty($this->check_db_errors($this->save_stat))) {
				$this->get_rounds($_POST['meetingId']);

				if (empty($this->check_db_errors($this->load_stat))) {
					echo json_encode(array(
						'rounds' => $this->load_data['rounds']
					));
				} else 
				print_r($this->save_stat);
			} else 
				print_r($this->save_stat);
		}
	}

}

if (API === TRUE) {
	$m = new CreateRoundsPage();
	$m->get_content();
}