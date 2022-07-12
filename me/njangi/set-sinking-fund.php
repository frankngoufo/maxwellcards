<?php
require_once("sectionwide-page.inc.php");

Class SetSFPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['sunking_fund'] = array(
			"INSERT INTO sinking_fund(meetingId, name, description, amount) VALUES(?,?,?,?)",
			array($_POST['meetingId'], $_POST['name'], $_POST['description'], $_POST['amount'])
		);
		$this->save_data['sunking_fund'] = array();
		$this->save_stat['sunking_fund'] = array();
		$this->dm->set_data( $this->save_stat['sunking_fund'], $this->save_args['sunking_fund'], $this->save_data['sunking_fund']);
	}

	public function get_content() {
		$this->save_data();
		if (empty($this->check_db_errors($this->save_stat))) {
			echo json_encode(array(
				'success' => 1
			));
		} else
		print_r($this->save_stat);
	}

}

if (API === TRUE) {
	$m = new SetSFPage();
	$m->get_content();
}