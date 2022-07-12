<?php
require_once("sectionwide-page.inc.php");

Class MarkPlayedPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['played'] = array(
			"INSERT INTO njangi_sessions(meetingId, player, roundId, host) VALUES(?,?,?,?)",
			array($_POST['meetingId'], $_POST['player'], $_POST['roundId'], $_POST['host'])
		);
		$this->save_data['played'] = array();
		$this->save_stat['played'] = array();
		$this->dm->set_data( $this->save_stat['played'], $this->save_args['played'], $this->save_data['played']);
	}

	public function get_content() {
		$this->save_data();
		if (empty($this->check_db_errors($this->save_stat))) {
			echo json_encode(array(
				'success' => 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new MarkPlayedPage();
	$m->get_content();
}