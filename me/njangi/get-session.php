<?php
require_once("sectionwide-page.inc.php");

Class GetSessionPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['session'] = array(
			"SELECT 
				(SELECT names FROM  users WHERE id = :hostId) AS host_name,
				(SELECT amount FROM njangi_rounds WHERE id = :roundId) AS amount,
				(SELECT COUNT(*) FROM njangi_sessions WHERE hostId = :hostId AND roundId = :roundId AND played > 0) AS count_played,
				names, users.id, roundId, played, date_played FROM users
				INNER JOIN njangi_sessions ON id = playerId
				WHERE hostId = :hostId AND roundId = :roundId",
			array(':hostId' => $_GET['hostId'], ':roundId' => $_GET['roundId'])
		);
		$this->load_data['session'] = array();
		$this->load_stat['session'] = array();
		$this->dm->get_data($this->load_args['session'], $this->load_data['session'], $this->load_stat['session']);
	}

	public function get_content() {
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'session' 	=> $this->load_data['session']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetSessionPage();
	$m->get_content();
}