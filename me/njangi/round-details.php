<?php
require_once("sectionwide-page.inc.php");

Class GetRoundsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['members'] = array(
			"SELECT users.id AS userId, names, roundId AS played, session_date FROM users
			INNER JOIN user_meetings ON users.id = user_meetings.userId
			INNER JOIN njangi_rounds ON user_meetings.meetingId = njangi_rounds.meetingId
			LEFT JOIN round_plays ON users.id = player
            WHERE njangi_rounds.id = ?",
			array($_GET['id'])
		);
		$this->load_data['members'] = array();
		$this->load_stat['members'] = array();
		$this->dm->get_data($this->load_args['members'], $this->load_data['members'], $this->load_stat['members']);

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
				'members' => $this->load_data['members'],
				'round'	=> $this->load_data['round'][0]
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetRoundsPage();
	$m->get_content();
}