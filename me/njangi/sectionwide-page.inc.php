<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class NjangiSectionPage extends SectionPage {

	public function __construct() {
		parent:: __construct();
	}

	protected function get_rounds($meetingId) {
		$this->load_args['rounds'] = array(
			"SELECT * FROM njangi_rounds WHERE meetingId = ? ORDER BY state DESC",
			array($meetingId)
		);
		$this->load_data['rounds'] = array();
		$this->load_stat['rounds'] = array();
		$this->dm->get_data($this->load_args['rounds'], $this->load_data['rounds'], $this->load_stat['rounds']);
	}

	protected function get_user_meetings($meetingId, $device_id="") {
		$this->load_args['user_meetings'] = array(
			"SELECT id, names $device_id FROM users
			INNER JOIN user_meetings ON users.id = userId
			WHERE meetingId = ?",
			array($meetingId)
		);
		$this->load_data['user_meetings'] = array();
		$this->load_stat['user_meetings'] = array();
		$this->dm->get_data($this->load_args['user_meetings'], $this->load_data['user_meetings'], $this->load_stat['user_meetings']);

		if (!empty($device_id)) {
			// Return device IDs in case we're running a notification
			$device_ids = array();
			foreach ($this->load_data['user_meetings'] as $value) {
				if(!empty($value['device_id']))
					$device_ids[] = $value['device_id'];
			}
			return $device_ids;
		}
	}
}