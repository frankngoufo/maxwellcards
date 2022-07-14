<?php 
if (API === TRUE && !empty($_REQUEST['userId'])) {
	$_SESSION["userId"] = $_REQUEST["userId"];
}

if (empty($_SESSION["userId"])) {
	header("Location: ".PATH_HOME."signup-login.php?redirect_to=".urlencode(strstr("$_SERVER[REQUEST_URI]", "me/")));
}

Class SectionPage extends SitePage {
    public $dm;
	public function __construct() {
		parent::__construct();
        $this->dm = new DataManager();

        // Verify user. 
        if ($this->verify_user() === false) {
        	exit();
        }
	}


    protected function get_user_names($userId) {
        $this->load_args['user_names'] = array(
            "SELECT names FROM users WHERE id = ?",
            array($userId)
        );
        $this->load_data['user_names'] = array();
        $this->load_stat['user_names'] = array();
        $this->dm->get_data($this->load_args['user_names'], $this->load_data['user_names'], $this->load_stat['user_names']);
    }

    

}