<?php
require_once("sectionwide-page.inc.php");

Class RoundPlaysPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['hosts'] = array(
			"SELECT names, users.id, round_name, state, host_date FROM users
				INNER JOIN njangi_round_hosts ON users.id = host
				INNER JOIN njangi_rounds ON roundId = njangi_rounds.id
				WHERE roundId = ?",
			array($_GET['roundId'])
		);
		$this->load_data['hosts'] = array();
		$this->load_stat['hosts'] = array();
		$this->dm->get_data($this->load_args['hosts'], $this->load_data['hosts'], $this->load_stat['hosts']);
	}

	public function get_content() {
		$this->load_data();
		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'hosts' => $this->load_data['hosts']
			));
		}
	}

}

if (API === TRUE) {
	$m = new RoundPlaysPage();
	$m->get_content();
}