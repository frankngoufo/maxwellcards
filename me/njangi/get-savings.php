<?php
require_once("sectionwide-page.inc.php");

Class GetSavingsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function get_content() {
		$this->get_user_meetings($_GET['meetingId']);

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'user_meetings' 	=> $this->load_data['user_meetings']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetSavingsPage();
	$m->get_content();
}