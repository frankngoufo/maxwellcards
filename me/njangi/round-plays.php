<?php
require_once("sectionwide-page.inc.php");

Class RoundPlaysPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['hosts'] = array(
			"SELECT users.id AS userId, names, session_date, ? AS roundId FROM users
			INNER JOIN user_meetings ON users.id = user_meetings.userId
			INNER JOIN njangi_rounds ON user_meetings.meetingId = njangi_rounds.meetingId
			LEFT JOIN round_plays ON users.id = host
            WHERE njangi_rounds.id = ?",
			array($_GET['id'], (int)$_GET['id'])
		);
		$this->load_data['hosts'] = array();
		$this->load_stat['hosts'] = array();
		$this->dm->get_data($this->load_args['hosts'], $this->load_data['hosts'], $this->load_stat['hosts']);

		$this->load_args['round'] = array(
			"SELECT * FROM njangi_rounds WHERE id = ?",
			array($_GET['id'])
		);
		$this->load_data['round'] = array();
		$this->load_stat['round'] = array();
		$this->dm->get_data($this->load_args['round'], $this->load_data['round'], $this->load_stat['round']);
	}

	public function get_content() {
		$this->load_data();
		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'hosts' => $this->load_data['hosts'],
				'round'	=> $this->load_data['round'][0]
			));
		}
	}

}

if (API === TRUE) {
	$m = new RoundPlaysPage();
	$m->get_content();
}