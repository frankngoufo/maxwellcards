<?php
require_once("sectionwide-page.inc.php");

Class CreateRoundsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['edit'] = array(
			"UPDATE njangi_rounds SET state = ? WHERE id = ?",
			array($_POST['state'], $_POST['roundId'])
		);
		$this->save_data['edit'] = array();
		$this->save_stat['edit'] = array();
		$this->dm->set_data( $this->save_stat['edit'], $this->save_args['edit'], $this->save_data['edit']);
		
	}

	public function get_content() {
		$this->save_data(); // Save round details
		if (empty($this->check_db_errors($this->save_stat))) {
			echo json_encode(array( 'success' => 1 ));
		}
	}

}

if (API === TRUE) {
	$m = new CreateRoundsPage();
	$m->get_content();
}