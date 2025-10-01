<?php

require_once __DIR__."/section-variables.php";

Class Endpoint extends SectionVariables {

  public function __construct() {
    parent::__construct();
  }

  public function get_content() {
    $user = $this->get_user($_SESSION['userId']);

  $this->respond(
    array(
      "user" => $user
    )
  );
  }
}