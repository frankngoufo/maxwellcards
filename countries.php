<?php

require_once __DIR__ . "/site-variables.php";

class Endpoint extends API {

  public function __construct() {
    parent::__construct();
  }

  private function insert_countries() {
  $this->query(
    "INSERT INTO countries(`name`, `country_code`) VALUES (?,?)",
    array("Cameroon", "CM"),
    "insert_countries"
  );
}

 public function get_content() {
  $this->insert_countries();

  if(!$this->db_errors_exist()) {
    $this->respond(
      array("message" => "Country successfully added!")
    );
  }
}
}