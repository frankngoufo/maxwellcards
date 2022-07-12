<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-register-meeting.php");
require_once(PATH_ROOT."includes/do-login.php");

Class CreateMeetingPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function get_content() {

		// Register meeting
		$r = new DoRegisterMeetingPage($this);
		$r->register_meeting($_SESSION['userId']);

		// Get updated list of all meetings
		$l = new DoLoginPage($this);
		$l->get_meetings($_SESSION['userId']);
		$l->get_cur_meeting($_SESSION['userId'], $_SESSION['curMeeting']);

		if (empty($this->check_db_errors($this->save_stat)) && empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'meetings' => $this->load_data['meetings'],
				'curMeeting' => $this->load_data['cr_meeting'][0]
			));
		}
	}

}

if (API === TRUE) {
	$m = new CreateMeetingPage();
	$m->get_content();
}