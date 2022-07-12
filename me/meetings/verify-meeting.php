<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-login.php");

Class VerifyMeetingPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {

		// Get user's random code to join meeting
		$this->load_args['code'] = array(
			"SELECT tel_code, meeting_for_tel_code FROM users WHERE id = ?",
			array($_SESSION['userId'])
		);
		$this->load_data['code'] = array();
		$this->load_stat['code'] = array();
		$this->dm->get_data($this->load_args['code'], $this->load_data['code'], $this->load_stat['code']);
	}

	public function save_data() {
		$this->save_args['join'] = array(
			array(
				"INSERT INTO user_meetings(meetingId, userId, user_role) VALUES(?,?,?)",
				array($this->load_data['code'][0]['meeting_for_tel_code'], $_SESSION['userId'], 'basic')
			)
		);
		$this->save_data['join'] = array();
		$this->save_stat['join'] = array();
		$this->dm->set_data( $this->save_stat['join'], $this->save_args['join'], $this->save_data['join']);
	}

	public function reset_code() {
		$this->save_args['reset'] = array(
			array ( // Erase code from database to avoid brute force
				"UPDATE users SET tel_code = ?, meeting_for_tel_code = ? WHERE id = ?",
				array(NULL, NULL, $_SESSION['userId'])
			)
		);
		$this->save_data['reset'] = array();
		$this->save_stat['reset'] = array();
		$this->dm->set_data( $this->save_stat['reset'], $this->save_args['reset'], $this->save_data['reset']);
	}

	public function get_content() {
		$this->load_data(); 

		if (empty($this->check_db_errors($this->load_stat))) {

			$this->reset_code(); // First reset code

			if ($this->load_data['code'][0]['tel_code'] != $_GET['tel_code']) {
				
				// Code is wrong
				echo json_encode(array('wrong_code' => 1));
			} else {
				$this->save_data(); // Code is correct. Now add user to meeting and return new meeting details
				$l = new DoLoginPage($this);
				$l->get_cur_meeting($_SESSION['userId'], $this->load_data['code'][0]['meeting_for_tel_code']);
				$l->get_meetings($_SESSION['userId']);
				if (empty($this->check_db_errors($this->load_stat))) {
					echo json_encode(array( 
						'curMeeting' => $this->load_data['cr_meeting'][0],
						'meetings' => $this->load_data['meetings']
					));
				}
			}
		}
	}

}

if (API === TRUE) {
	$m = new VerifyMeetingPage();
	$m->get_content();
}