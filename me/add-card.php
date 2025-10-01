<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {

  public function __construct() {
    parent::__construct();
  }

  // Fonction pour générer un numéro de carte unique
  private function generateUniqueCardNumber() {
    do {
      $cardNumber = '4' . str_pad(mt_rand(0, 999999999999999), 15, '0', STR_PAD_LEFT); // 16 chiffres, commence par 4
      $exists = $this->query("SELECT 1 FROM cards WHERE card_number = ? LIMIT 1", [$cardNumber], "check_card_number", true);
    } while (!empty($exists));
    return $cardNumber;
  }

  // Fonction pour générer un CVV aléatoire
  private function generateCVV() {
    return str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
  }

  // Fonction pour générer une date d’expiration dans 3 ans
  private function generateExpiryDate() {
    return date('Y-m-d', strtotime('+3 years'));
  }

  public function get_content() {
    // Récupérer l'ID utilisateur et le nom sur la carte (envoyés par le frontend)
    $userId   = $_POST["user_id"]    ?? null;
    $nameCard = $_POST["name_card"]  ?? null;

    if (!$userId || !$nameCard) {
      $this->respond(["error" => "user_id and name_card are required."]);
    }

    // Générer automatiquement les données
    $cardNumber = $this->generateUniqueCardNumber();
    $cvv        = $this->generateCVV();
    $expiryDate = $this->generateExpiryDate();
    $last4      = substr($cardNumber, -4);

    // Insertion dans la base de données
    $this->query(
      "INSERT INTO cards (card_number, user_id, name_card, expiry_date, CVV_number, last_4digits, card_status, created_at, updated_at) 
       VALUES (?, ?, ?, ?, ?, ?, 'active', NOW(), NOW())",
      [$cardNumber, $userId, $nameCard, $expiryDate, $cvv, $last4],
      "add_card"
    );

    // Réponse
    $this->respond([
      "message" => "Card successfully generated and added.",
      "card_number" => $cardNumber,
      "cvv" => $cvv,
      "expiry_date" => $expiryDate,
      "last4digits" => $last4
    ]);
  }
}
