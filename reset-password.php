<?php
require_once("sitewide-page.inc.php");


Class ResetPassPage extends SitePage {

	public function __construct() {
		parent::__construct();
	}

	private function reset_pw_code($code) {
		$this->save_args['code'] = array(
			"UPDATE users SET pw_reset_code = ? WHERE tel = ?",
			array($code, $_POST['tel'])
		);
		$this->save_data['code'] = array();
		$this->save_stat['code'] = array();
		$this->dm->set_data($this->save_stat['code'], $this->save_args['code'], $this->save_data['code']);

	}

	public function save_data() {
		$this->save_args['password'] = array(
			"UPDATE users SET pw = ?, pw_reset_code = NULL WHERE tel = ?",
			array(
				password_hash($_POST['pw'], PASSWORD_DEFAULT), 
				$_POST['tel']
			)
		);
		$this->save_data['password'] = array();
		$this->save_stat['password'] = array();
		$this->dm->set_data($this->save_stat['password'], $this->save_args['password'], $this->save_data['password']);

	}

	private function does_user_exist() {
		$this->load_args['user'] = array(
			"SELECT id, pw_reset_code FROM users WHERE tel = ?",
			array($_POST['tel'])
		);
		$this->load_data['user'] = array();
		$this->load_stat['user'] = array();
		$this->dm->get_data( $this->load_args['user'], $this->load_data['user'], $this->load_stat['user'] );
	}

	public function get_content() {
		if (!empty($_POST['pw']) && !empty($_POST['reset_code'])) {

			// User has received their reset code, second time coming to page
			$this->does_user_exist(); // Get password reset code

			if ($this->load_data['user'][0]['pw_reset_code'] == $_POST['reset_code']) {
				$this->save_data(); // Change password
				exit(json_encode(array('success' => 1)));
			} else {
				exit(json_encode(array('wrong_reset' => $_SESSION['reset_code'])));
			}
		} else {
			$this->does_user_exist(); // Get details about user to add
			if (empty($this->check_db_errors($this->load_stat))) {
				if (empty($this->load_data['user'][0]['id'])) { // User doesn't exist
					exit(json_encode(array('no-user' => 1)));
				} else { // User exists
					$code = rand(10000,99999);
					$this->reset_pw_code($code);
					if(empty($this->save_stat['code']))
						exit(json_encode(array('code' => $code)));
				}
			}

		}
	}

}

if (API === TRUE) {
	$m = new ResetPassPage();
	$m->get_content();
}