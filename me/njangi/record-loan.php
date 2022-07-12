<?php
require_once("sectionwide-page.inc.php");

Class RecordLoanPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['loan'] = array(
			"INSERT INTO loans(user, meetingId, amount) VALUES(?,?,?)",
			array($_POST['member'], $_POST['meetingId'], $_POST['amount'])
		);
		$this->save_data['loan'] = array();
		$this->save_stat['loan'] = array();
		$this->dm->set_data( $this->save_stat['loan'], $this->save_args['loan'], $this->save_data['loan']);
	}

	public function get_content() {
		$this->save_data();

		if (empty($this->check_db_errors($this->save_stat)) && !empty($this->save_data["loan"])) {

			// Notify users
			$this->get_user_names($_POST['member']);
			$this->get_meeting_details($_POST['meetingId']);
			$device_ids = $this->get_user_meetings($_POST['meetingId'], ", device_id");

			$title = "New loan recorded for ".$this->load_data['meeting_details'][0]['name'];
			$msg = $this->load_data['user_names'][0]['names']." has been granted a loan worth XAF ".$_POST['amount'];
			$notif = $this->notify($device_ids, $msg, $title);

			echo json_encode(array(
				'success' 	=> $notif
			));
		}
	}

}

if (API === TRUE) {
	$m = new RecordLoanPage();
	$m->get_content();
}