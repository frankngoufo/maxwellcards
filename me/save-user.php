<?php
require_once __DIR__."/section-variables.php";

Class Endpoint extends SectionVariables {
    
  public function __construct() {
      parent::__construct();
  }

  public function save_data() {
    $this->query(
      "UPDATE users SET first_name = ?, last_name = ?, email = ? WHERE id = ?",
      array($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_SESSION['userId']),
      "user"
    );
  }

  public function get_content() {
    $this->save_data();

    $user = $this->get_user($_SESSION['userId']);
    unset($user["otp"]);
    unset($user["otp_time"]);
    
    if(empty($this->query_data["user"]["stat"])) {
      $this->respond(array("token" => $this->generateJWTToken($user)));
    }
  }
}