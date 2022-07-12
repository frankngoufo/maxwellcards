<?php
require_once("sectionwide-page.inc.php");

Class ContributeSFPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['contribute'] = array(
			"INSERT INTO user_sinking_fund(contributor, sfId, meetingId) VALUES(?,?,?)",
			array($_POST['contributor'], $_POST['sfId'], $_POST['meetingId'])
		);
		$this->save_data['contribute'] = array();
		$this->save_stat['contribute'] = array();
		$this->dm->set_data( $this->save_stat['contribute'], $this->save_args['contribute'], $this->save_data['contribute']);
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
	$m = new ContributeSFPage();
	$m->get_content();
}