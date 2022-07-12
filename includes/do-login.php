<?php
Class DoLoginPage {
	private $page;
	private $reset;
	private $userId;
	private $curMeeting;

	public function __construct($page) {
		$this->page = $page;
		$this->reset = bin2hex(random_bytes(16));
	}

	private function set_reset_token() {
		$this->page->save_args['reset'] = array(
			"UPDATE users SET reset_token = ? WHERE id = ?",
			array( $this->reset, $this->userId)
		);
		$this->page->save_data['reset'] = array();
		$this->page->save_stat['reset'] = array();
		$this->page->dm->set_data( $this->page->save_stat['reset'], $this->page->save_args['reset'], $this->page->save_data['reset']);
	}

	public function load_data() {
		$this->page->load_args["user"] = array(
			"SELECT names, id, email, reset_token, pw FROM users WHERE tel = ?",
			array($_POST['tel'])
		);
		$this->page->load_data["user"] = array();
		$this->page->load_stat["user"] = array();
		$this->page->dm->get_data( $this->page->load_args["user"], $this->page->load_data["user"], $this->page->load_stat["user"]);
	}

	public function get_meetings($userId) {
		$this->page->load_args["meetings"] = array(
			"SELECT meetingId, name, user_role FROM user_meetings INNER JOIN meetings 
				ON meetingId = meetings.id WHERE userId = ?",
			array($userId)
		);
		$this->page->load_data["meetings"] = array();
		$this->page->load_stat["meetings"] = array();
		$this->page->dm->get_data( $this->page->load_args["meetings"], $this->page->load_data["meetings"], $this->page->load_stat["meetings"]);
	}

	public function create_user() {
		$this->page->save_args['user'] = array(
			"INSERT INTO users(names, tel, email, pw) VALUES (?,?,?,?)",
			array(
				$_POST['names'], 
				$_POST['tel'],
				empty($_POST['email']) ? "" : $_POST['email'], // Because this function is called from 2 files with different logics
				empty($_POST['pw']) ? "" : password_hash($_POST['pw'], PASSWORD_DEFAULT)
			)
		);
		$this->page->save_data['user'] = array();
		$this->page->save_stat['user'] = array();
		$this->page->dm->set_data($this->page->save_stat['user'], $this->page->save_args['user'], $this->page->save_data['user']);
	}

	public function do_login() {
		if (empty($this->page->check_db_errors($this->page->load_stat))) {
			if (password_verify($_POST['pw'], $this->page->load_data['user'][0]['pw'])) {

				unset($this->page->load_data['user'][0]['pw']); // Strip out password
				$this->page->load_data['user'][0]['reset_token'] = $this->reset; // set reset token
				$this->userId = $this->page->load_data['user'][0]['id'];

				$this->set_reset_token();


				if(empty($this->page->check_db_errors($this->page->load_stat)) && empty($this->page->check_db_errors($this->page->save_stat))) {
					echo json_encode(array(
						'loggedIn' => 1, 
						'user' => $this->page->load_data['user'][0],
					));
				}
			} else {
				echo json_encode(array('notlogged' => 1));
			}
		}
	}
}