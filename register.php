<?php
/*
Register a meeting
This class is called only for users who don't have an account.
For users with accounts, check me/.../register.php.
*/

require_once("sitewide-page.inc.php");
require_once('includes/do-login.php');
require_once('includes/do-register-meeting.php');

Class RegisterPage extends SitePage {
	private $userId;

	public function __construct() {
		parent::__construct();
	}

	public function get_content() {
		$l = new DoLoginPage($this);
		$l->load_data(); // If data is returned, user exists

		if (empty($this->load_stat['user']) && !empty($this->load_data['user'])) {
			echo json_encode(array('exists' => 1));
		} else {
			$l->create_user(); // User doesn't exist. Register user
			$this->userId = $_SESSION['lastInsertId'];
		}
	}
}

if (API === TRUE) {
	$m = new RegisterPage();
	$m->get_content();
}
