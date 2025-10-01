<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }

     /**
     * fund_card()
     * Purpose: Credit a card with a certain amount and notify the user.
     * Inputs (POST):
     *  - card_id: ID of the card to credit (required)
     *  - amount: amount to credit (required)
     *  - currency: currency code (optional, default 'XAF')
     * 
     * Steps:
     *  1. Validate inputs and ownership.
     *  2. Insert a 'deposit' transaction into card_transactions.
     *  3. Add a notification about the deposit.
     *  4. Return the last 5 notifications in the response.
     */

    public function fund_card() {
        
        $user_id = $_SESSION['userId'] ?? null;
        $card_id  = $_POST['card_id'] ?? null;
        $amount   = $_POST['amount'] ?? null;
        $currency = $_POST['currency'] ?? 'XAF';

        if (!$user_id || !$card_id || !$amount) {
            $this->respond([
                "status" => "error",
                "message" => "Champs manquants"
            ]);
            return;
        }

        // 1. Vérifier que la carte appartient à l'utilisateur
        $this->query(
            "SELECT * FROM cards WHERE id = ? AND user_id = ?",
            [$card_id, $user_id],
            "check_card"
        );

        if (empty($this->query_data['check_card']['data'])) {
            $this->respond([
                "status" => "error",
                "message" => "Card not found or not authorized"
            ]);
            return;
        }

        // 2. Enregistrer la transaction
        $this->query(
            "INSERT INTO card_transactions (amount, currency, card_id, type_transaction)
             VALUES (?, ?, ?, 'deposit')",
            [$amount, $currency, $card_id]
        );

        // 3. Ajouter la notification
        $message = "Your card has been credited with $amount $currency";
      // 3. Ajouter la notification
$this->query(
    "INSERT INTO notifications (user_id, category, amount, message)
     VALUES (?, 'deposit', ?, ?)",
    [$user_id, $amount, "Your card has been credited with $amount $currency"]
);

// 4. Récupérer les 10 dernières notifications
$this->query(
    "SELECT id, category, amount, message, notification_time, is_read
     FROM notifications
     WHERE user_id = ?
     ORDER BY notification_time DESC
     LIMIT 5",
    [$user_id],
    "recent_notifs"
);

// 5. Répondre avec notifications incluses
$this->respond([
    "status" => "success",
    "message" => "Credit card and notification added",
    "notifications" => $this->query_data['recent_notifs']['data'] ?? []
]);


        // 4. Répondre
        $this->respond([
            "status" => "success",
            "message" => "Credit card and notification added"
        ]);
    }

    public function get_content() {
        $this->fund_card();
    }
}

