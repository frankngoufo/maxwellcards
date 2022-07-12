<?php
require_once("sectionwide-page.inc.php");

Class SessionDetailsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['members'] = array(
			"SELECT users.id AS userId, names, player, host FROM users
			INNER JOIN user_meetings ON users.id = user_meetings.userId
			INNER JOIN njangi_rounds ON user_meetings.meetingId = njangi_rounds.meetingId
			LEFT JOIN njangi_sessions ON users.id = player
            WHERE njangi_rounds.id = ?",
			array($_GET['id'])
		);
		$this->load_data['members'] = array();
		$this->load_stat['members'] = array();
		$this->dm->get_data($this->load_args['members'], $this->load_data['members'], $this->load_stat['members']);

		$this->load_args['host'] = array(
			"SELECT names, hosting_date FROM users
			LEFT JOIN njangi_sessions ON users.id = host 
			WHERE users.id = ?",
			array($_GET['host'])
		);
		$this->load_data['host'] = array();
		$this->load_stat['host'] = array();
		$this->dm->get_data($this->load_args['host'], $this->load_data['host'], $this->load_stat['host']);
	}

	public function get_content() {
		$this->load_data();
		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'members' => $this->load_data['members'],
				'host'	=> $this->load_data['host'][0]
			));
		}
	}

}

if (API === TRUE) {
	$m = new SessionDetailsPage();
	$m->get_content();
}