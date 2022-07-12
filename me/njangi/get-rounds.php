<?php
require_once("sectionwide-page.inc.php");

Class GetRoundsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function get_content() {
		$this->get_rounds($_GET['meetingId']);
		$this->get_user_meetings($_GET['meetingId']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'rounds' => $this->load_data['rounds'],
				'user_meetings' => $this->load_data['user_meetings']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetRoundsPage();
	$m->get_content();
}