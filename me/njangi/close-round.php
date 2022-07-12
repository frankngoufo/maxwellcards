<?php
require_once("sectionwide-page.inc.php");

Class CloseRoundPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['close'] = array(
			"UPDATE njangi_rounds SET state = ? WHERE id = ?",
			array(0, $_POST['id'])
		);
		$this->save_data['close'] = array();
		$this->save_stat['close'] = array();
		$this->dm->set_data( $this->save_stat['close'], $this->save_args['close'], $this->save_data['close']);
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
	$m = new CloseRoundPage();
	$m->get_content();
}