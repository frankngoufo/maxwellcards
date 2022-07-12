<?php
require_once("sectionwide-page.inc.php");

Class ModifyTopPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['top'] = array(
			array(
				"UPDATE meeting_defaults SET sinking_fund_top = ? WHERE meetingId = ?",
				array($_POST['top'], $_POST['meetingId'])
			)
		);
		$this->save_data['top'] = array();
		$this->save_stat['top'] = array();
		$this->dm->set_data( $this->save_stat['top'], $this->save_args['top'], $this->save_data['top']);

	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'success' => 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new ModifyTopPage();
	$m->get_content();
}