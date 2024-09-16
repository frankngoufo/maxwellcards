<?php
require_once __DIR__ . "/../site-variables.php";

Class SectionVariables extends API {
    
    public function __construct() {
        parent::__construct();
        $this->check_login();
    }

    /**
     * Check if the user is logged in
     * @param integer $userId: The ID of the user logged in
     * @return void: disconnects if the user isn't logged in, else proceeds with request
     * 
     **/
    private function check_login() {
        $access_token = $this->getBearerToken();
        if(!$this->verifyJWTToken($access_token)) {
            $this->respond(array(
              "error" => array(
                  "type" => "general",
                  "message" => "You've been logged out of the server. Please log in to continue"
              )
            ));
        }
    }
}