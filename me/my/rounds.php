<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MyRoundsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['rounds'] = array(
			"SELECT * FROM njangi_rounds WHERE meetingId = ? ORDER BY state DESC",
			array($_GET['meetingId'])
		);
		$this->load_data['rounds'] = array();
		$this->load_stat['rounds'] = array();
		$this->dm->get_data($this->load_args['rounds'], $this->load_data['rounds'], $this->load_stat['rounds']);
	}

	public function get_content() {
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'rounds' 	=> $this->load_data['rounds']
			));
		}
	}

}

if (API === TRUE) {
	$m = new MyRoundsPage();
	$m->get_content();
}