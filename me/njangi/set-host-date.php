<?php
require_once("sectionwide-page.inc.php");

Class SetHostDatePage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['create'] = array(
			"INSERT INTO njangi_sessions(roundId, player, host, meetingId, hosting_date) VALUES(?,?,?,?,?)",
			array($_POST['id'], $_POST['host'], $_POST['host'], $_POST['meetingId'], $_POST['hosting_date'])
		);
		$this->save_data['create'] = array();
		$this->save_stat['create'] = array();
		$this->dm->set_data( $this->save_stat['create'], $this->save_args['create'], $this->save_data['create']);
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
	$m = new SetHostDatePage();
	$m->get_content();
}