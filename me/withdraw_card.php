<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }

   
    public function get_card_balance($card_id) {
        $this->query(
            "SELECT 
              COALESCE(SUM(CASE WHEN type_transaction = 'deposit' THEN amount ELSE 0 END), 0)
              -
              COALESCE(SUM(CASE WHEN type_transaction IN ('withdrawal', 'purchase') THEN amount ELSE 0 END), 0)
              AS balance
             FROM card_transactions
             WHERE card_id = ?",
            [$card_id],
            "balance_result"
        );

        if (!empty($this->query_data['balance_result']['data'])) {
            return (float) $this->query_data['balance_result']['data'][0]['balance'];
        }
        return 0.0;
    }

    public function withdraw_card() {
       
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

        // Vérifier que la carte appartient à l'utilisateur
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

        // Récupérer le solde avant retrait
        $balance = $this->get_card_balance($card_id);

        if ($balance < $amount) {
            $this->respond([
                "status" => "error",
                "message" => "Insufficient balance. Your current balance is $balance $currency."
            ]);
            return;
        }

        // Enregistrer la transaction de retrait
        $this->query(
            "INSERT INTO card_transactions (amount, currency, card_id, type_transaction)
             VALUES (?, ?, ?, 'withdrawal')",
            [$amount, $currency, $card_id]
        );

        // Calculer le solde restant après retrait
        $new_balance = $balance - $amount;

        // Ajouter la notification
        $message = "A withdrawal of $amount $currency was made from your card.";

        $this->query(
            "INSERT INTO notifications (user_id, category, amount, message)
             VALUES (?, 'withdrawal', ?, ?)",
            [$user_id, $amount, $message]
        );

        // Récupérer les 10 dernières notifications
        $this->query(
            "SELECT id, category, amount, message, notification_time, is_read
             FROM notifications
             WHERE user_id = ?
             ORDER BY notification_time DESC
             LIMIT 10",
            [$user_id],
            "recent_notifs"
        );

        // Répondre avec message + notifications + solde restant
        $this->respond([
            "status" => "success",
            "message" => "Withdrawal successful. Remaining balance : $new_balance $currency.",
            "balance" => $new_balance,
            "notifications" => $this->query_data['recent_notifs']['data'] ?? []
        ]);
    }

    public function get_content() {
        $this->withdraw_card();
    }
}

