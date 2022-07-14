<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT.'includes/do-login.php');


Class CreateMSPage extends SectionPage {
	private $userId;

	public function __construct() {
		parent::__construct();
	}

	private function does_user_exist() {
		$this->load_args['user'] = array(
			"SELECT id FROM users WHERE tel = ?",
			array($_POST['tel'])
		);
		$this->load_data['user'] = array();
		$this->load_stat['user'] = array();
		$this->dm->get_data( $this->load_args['user'], $this->load_data['user'], $this->load_stat['user'] );
	}

	public function save_data() {
		$this->save_args['user_meeting'] = array(
			array(
				"INSERT INTO user_meetings(meetingId, userId, user_role) VALUES(?,?,?)",
				array( (int)$_POST['meetingId'], $this->userId, $_POST['user_role'])
			)
			/*
			,
			array(
				"INSERT INTO project_contributions(userId, meetingId) VALUES(?,?)",
				array($this->load_data['user'][0]['id'], $_POST['meetingId'])
			)
			*/
		);
		$this->save_data['user_meeting'] = array();
		$this->save_stat['user_meeting'] = array();
		$this->dm->set_data( $this->save_stat['user_meeting'], $this->save_args['user_meeting'], $this->save_data['user_meeting']);
	}

	public function get_content() {
		$this->does_user_exist(); // Get details about user to add

		if (empty($this->check_db_errors($this->load_stat))) {
			if (empty($this->load_data['user'][0]['id'])) { // User doesn't exist
				$l = new DoLoginPage($this);
				$l->create_user(); // User doesn't exist. Register user
				$this->userId = $_SESSION['lastInsertId'];

			} else { // User exists
				$this->userId = $this->load_data['user'][0]['id']; // To be used later
			}
			echo json_encode(array('success' => 1));
		}
	}

}

if (API === TRUE) {
	$m = new CreateMSPage();
	$m->get_content();
}