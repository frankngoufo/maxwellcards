<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-login.php");

Class CreateMSPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['code_exists'] = array(
			"SELECT id FROM meetings WHERE meeting_code = ?",
			array($_POST['meeting_code'])
		);
		$this->load_data['code_exists'] = array();
		$this->load_stat['code_exists'] = array();
		$this->dm->get_data($this->load_args['code_exists'], $this->load_data['code_exists'], $this->load_stat['code_exists']);
	}

	public function save_data() {
		$this->save_args['code'] = array(
			"UPDATE meetings AS m 
			INNER JOIN user_meetings AS um ON m.id = um.meetingId
			SET meeting_code = ? WHERE id = ? AND userId = ?",
			array($_POST['meeting_code'], $_POST['id'], $_SESSION['userId'])
		);
		$this->save_data['code'] = array();
		$this->save_stat['code'] = array();
		$this->dm->set_data( $this->save_stat['code'], $this->save_args['code'], $this->save_data['code']);
	}

	public function get_content() {
		$this->load_data(); // Check if this code is already taken
		$this->save_data(); 
		$l = new DoLoginPage($this);
		$l->get_cur_meeting($_SESSION['userId'], (int)$_POST['id']);
		$l->get_meetings($_SESSION['userId']);

		if (empty($this->check_db_errors($this->save_stat)) && empty($this->check_db_errors($this->load_stat))) {
			if (!empty($this->load_data['code_exists'])) {
				echo json_encode(array('code_exists' => 1));
			} else {
				echo json_encode(array( 
					'curMeeting' => $this->load_data['cr_meeting'][0],
					'meetings' => $this->load_data['meetings']
				));
			}
		}
	}

}

if (API === TRUE) {
	$m = new CreateMSPage();
	$m->get_content();
}