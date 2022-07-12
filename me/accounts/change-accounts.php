<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class ChangeAccountsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['accounts'] = array(
			"UPDATE meeting_functions SET 
			njangi = ?, savings = ?, loans = ?, donations=?, projects=?, fines=?, sinking_fund=?
			WHERE meetingId = ?",
			array($_POST['njangi'], $_POST['savings'], $_POST['loans'], $_POST['donations'], $_POST['projects'], $_POST['fines'], $_POST['sinking_fund'], $_POST['id'])
		);
		$this->save_data['accounts'] = array();
		$this->save_stat['accounts'] = array();
		$this->dm->set_data($this->save_stat['accounts'], $this->save_args['accounts'], $this->save_data['accounts']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat))) {
			require_once(PATH_ROOT.'includes/do-login.php'); // To get current meeting
			$m = new DoLoginPage($this);
			$m->get_cur_meeting($_SESSION['userId'], $_POST['id']);

			if (empty($this->check_db_errors($this->load_stat))) {
				echo json_encode(array('curMeeting' => $this->load_data['cr_meeting'][0]));
			}
		}
	}

}

if (API === TRUE) {
	$m = new ChangeAccountsPage();
	$m->get_content();
}