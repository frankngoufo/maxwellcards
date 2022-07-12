<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT."includes/do-login.php");

Class SaveMeetingSettingsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['meeting'] = array(
			"UPDATE meetings SET name = ?, description = ?, state = ? WHERE id = ?",
			array($_POST['name'], $_POST['description'], (int)$_POST['state'], $_POST['id'])
		);
		$this->save_data['meeting'] = array();
		$this->save_stat['meeting'] = array();
		$this->dm->set_data( $this->save_stat['meeting'], $this->save_args['meeting'], $this->save_data['meeting']);
	}

	public function get_content() {
		$this->save_data();
		$l = new DoLoginPage($this);
		$l->get_cur_meeting($_SESSION['userId'], (int)$_POST['id']);
		$l->get_meetings($_SESSION['userId']);

		if (empty($this->check_db_errors($this->save_stat)) && empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array( 
				'curMeeting' => $this->load_data['cr_meeting'][0],
				'meetings' => $this->load_data['meetings']
			));
		}
	}

}

if (API === TRUE) {
	$m = new SaveMeetingSettingsPage();
	$m->get_content();
}