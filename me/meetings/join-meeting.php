<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-login.php");

Class CreateMSPage extends SectionPage {
	private $tel_code;

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {

		// Check if such a meeting exists
		$this->load_args['meeting'] = array(
			"SELECT id FROM meetings WHERE meeting_code = ?",
			array($_POST['meeting_code'])
		);
		$this->load_data['meeting'] = array();
		$this->load_stat['meeting'] = array();
		$this->dm->get_data($this->load_args['meeting'], $this->load_data['meeting'], $this->load_stat['meeting']);
	}

	private function belongs() {

		// Check if user already belongs to this meeting
		$this->load_args['belongs'] = array(
			"SELECT * FROM user_meetings WHERE meetingId = ? AND userId = ?",
			array($this->load_data['meeting'][0]['id'], $_SESSION['userId'])
		);
		$this->load_data['belongs'] = array();
		$this->load_stat['belongs'] = array();
		$this->dm->get_data($this->load_args['belongs'], $this->load_data['belongs'], $this->load_stat['belongs']);
	}

	private function generate_tel_code() {
		$this->tel_code = random_int(1000, 9999);
	}

	public function save_data() {
		$this->save_args['join'] = array(
			array(
				"UPDATE users SET tel_code = ?, meeting_for_tel_code = ? WHERE id = ?",
				array($this->tel_code, $this->load_data['meeting'][0]['id'], $_SESSION['userId'])
			)
		);
		$this->save_data['join'] = array();
		$this->save_stat['join'] = array();
		$this->dm->set_data( $this->save_stat['join'], $this->save_args['join'], $this->save_data['join']);
	}

	public function get_content() {
		$this->load_data(); // Check if a meeting actually exists with this code

		if (empty($this->check_db_errors($this->load_stat))) {
			if (empty($this->load_data['meeting'])) {
				echo json_encode(array('no_code' => 1));
			} else {
				$this->belongs(); // Check if user belongs to this meeting we're about adding them to
				if (empty($this->check_db_errors($this->load_stat))) {
					if (!empty($this->load_data['belongs'])) {
						echo json_encode(array('belongs' => 1));
					} else {
						$this->generate_tel_code(); // Generate random code and send to user's phone
						$this->save_data(); // Add user to meeting
						if (empty($this->check_db_errors($this->save_stat))) {

							// Code has been saved and sent. Return
							echo json_encode(array('success' => 1));
						}
					}
				}
			}
		}
	}

}

if (API === TRUE) {
	$m = new CreateMSPage();
	$m->get_content();
}