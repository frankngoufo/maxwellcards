<?php

require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {

  public function __construct() {
    parent::__construct();
  }



  private function pin() {
    // Parse JSON input
    $input = json_decode(file_get_contents("php://input"), true);
    $pin = $input["pin"] ?? null;

    // Validate PIN: must be string of exactly 4 digits
    if (!is_string($pin) || !preg_match('/^\d{4}$/', $pin)) {
      $this->respond(["error" => "PIN must be a 4-digit number."]);
    }

    // Check if user already has a PIN set
    $this->query(
      "SELECT pin_code FROM users WHERE id = ?",
      [$_SESSION["userId"]],
      "user_pin"
    );

    $existingPin = $this->query_data["user_pin"]["data"][0]["pin_code"] ?? null;

    if ($existingPin !== null && $existingPin !== "") {
      $this->respond(["error" => "Transaction PIN already set. Use update endpoint to modify it."]);
    }

    // Save new PIN
    $this->query(
      "UPDATE users SET pin_code = ? WHERE id = ?",
      [$pin, $_SESSION["user_id"]],
      "set_pin"
    );

    $this->respond([
      "success" => true,
      "message" => "Transaction PIN created successfully."
    ]);
  }
    public function get_content() {
    $this->pin(); // Appelle de la méthode pin
  }
}
