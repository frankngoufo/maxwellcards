<?php
require_once("sitewide-page.inc.php");
require_once(PATH_COMMON_DATA_MANAGER."data-manager.inc.php");
require_once('includes/do-login.php');

Class LoginPage extends SitePage {
	public $dm;

	public function __construct() {
		$this->dm = new DataManager();
	}

	public function get_content() {

		$l = new DoLoginPage($this);
		$l->load_data();
		$l->do_login();

	}
}

if (API === TRUE) {
	$m = new LoginPage();
	$m->get_content();
}
