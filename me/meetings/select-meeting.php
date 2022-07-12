<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-login.php");

Class SelectMeetingPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function get_content() {

		// Get updated list of all meetings
		$l = new DoLoginPage($this);
		$l->get_cur_meeting($_SESSION['userId'], (int)$_GET['id']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'curMeeting' => $this->load_data['cr_meeting'][0]
			));
		}
	}

}

if (API === TRUE) {
	$m = new SelectMeetingPage();
	$m->get_content();
}