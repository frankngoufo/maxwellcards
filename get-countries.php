<?php

require_once __DIR__ . "/site-variables.php";

class Endpoint extends API {

  public function __construct() {
    parent::__construct();
  }

private function get_countries() {
  $this->query(
    "SELECT * FROM countries WHERE ?",
    array(TRUE),
    "get_countries"
  );
}

 public function get_content() {
     $this->get_countries();

  if(!$this->db_errors_exist()) {
    $this->respond(
      array("countries" => $this->query_data["get_countries"]["data"])
    );
}

}
}