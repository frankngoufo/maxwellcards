<?php
require_once("sectionwide-page.inc.php");

Class RecordSavingPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['saving'] = array(
			"INSERT INTO savings(user, meetingId, amount) VALUES(?,?,?)",
			array($_POST['member'], $_POST['meetingId'], $_POST['amount'])
		);
		$this->save_data['saving'] = array();
		$this->save_stat['saving'] = array();
		$this->dm->set_data( $this->save_stat['saving'], $this->save_args['saving'], $this->save_data['saving']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["saving"])) {

			// Notify users
			$this->get_user_names($_POST['member']);
			$this->get_meeting_details($_POST['meetingId']);
			$device_ids = $this->get_user_meetings($_POST['meetingId'], ", device_id");

			$title = "New saving recorded for ".$this->load_data['meeting_details'][0]['name'];
			$msg = $this->load_data['user_names'][0]['names']." added XAF ".$_POST['amount']." into their savings account.";
			$notif = $this->notify($device_ids, $msg, $title);

			echo json_encode(array(
				'success' 	=> 1
			));
		}
	}

}

if (API === TRUE) {
	$m = new RecordSavingPage();
	$m->get_content();
}