<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class GetUsers extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['users'] = array(
			"SELECT id, names, tel, stat, user_role FROM users 
			INNER JOIN user_meetings ON id = userId
			WHERE meetingId = ?",
			array($_GET['meetingId'])
		);
		$this->load_data['users'] = array();
		$this->load_stat['users'] = array();
		$this->dm->get_data( $this->load_args['users'], $this->load_data['users'], $this->load_stat['users'] );
	}

	public function get_content() {
		$this->load_data();
		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array('users' => $this->load_data['users']));
		}
	}

}

if (API === TRUE) {
	$m = new GetUsers();
	$m->get_content();
}