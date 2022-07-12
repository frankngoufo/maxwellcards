<?php
require_once("sectionwide-page.inc.php");

Class PlaySessionPage extends NjangiSectionPage {
	private $roundId;

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['play'] = array(
			"UPDATE njangi_sessions SET played = 1 WHERE playerId = ? AND hostId = ?",
			array($_POST['playerId'], $_POST['hostId'])
		);
		$this->save_data['play'] = array();
		$this->save_stat['play'] = array();
		$this->dm->set_data( $this->save_stat['play'], $this->save_args['play'], $this->save_data['play']);
	}

	public function get_content() {
		$this->save_data(); // Save round details
		if (empty($this->check_db_errors($this->save_stat))) {
			echo json_encode(array("success" => 1));
		}
	}

}

if (API === TRUE) {
	$m = new PlaySessionPage();
	$m->get_content();
}