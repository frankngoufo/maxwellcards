<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");
require_once(PATH_ROOT.'includes/do-login.php');


Class RegisterPushPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function save_data() {
		$this->save_args['device_id'] = array(
			array(
				"UPDATE users SET device_id = ? WHERE id = ?",
				array( $_POST['device_id'], $_SESSION['userId'])
			)
		);
		$this->save_data['device_id'] = array();
		$this->save_stat['device_id'] = array();
		$this->dm->set_data( $this->save_stat['device_id'], $this->save_args['device_id'], $this->save_data['device_id']);
	}

	public function get_content() {
		$this->save_data();
		if (empty($this->check_db_errors($this->load_stat)) && !empty($this->save_data['device_id'])) {
			exit(json_encode(array('success' => 1)));
		}
	}

}

if (API === TRUE) {
	$m = new RegisterPushPage();
	$m->get_content();
}