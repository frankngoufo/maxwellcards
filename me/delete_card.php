<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }

      
    public function toggle_card_status() {
        $user_id = $_SESSION['userId'] ?? null;
        $card_id = $_POST['card_id'] ?? null;
        $action  = $_POST['action'] ?? null; // 'freeze' ou 'unfreeze'

        if (!$user_id || !$card_id || !$action) {
            $this->respond([
                "status" => "error",
                "message" => "Champs manquants"
            ]);
            return;
        }

        if (!in_array($action, ['freeze', 'unfreeze'])) {
            $this->respond([
                "status" => "error",
                "message" => "Action invalide"
            ]);
            return;
        }

        // Vérifier que la carte appartient à l'utilisateur
        $this->query(
            "SELECT card_status FROM cards WHERE id = ? AND user_id = ?",
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

        $current_status = $this->query_data['check_card']['data'][0]['card_status'];

        // Valider la transition selon le statut actuel et l'action demandée
        if ($action === 'freeze' && $current_status !== 'active') {
            $this->respond([
                "status" => "error",
                "message" => "Cannot freeze a card that is not active"
            ]);
            return;
        }

        if ($action === 'unfreeze' && $current_status !== 'frozen') {
            $this->respond([
                "status" => "error",
                "message" => "Cannot unfreeze a card that is not frozen"
            ]);
            return;
        }

        $new_status = $action === 'freeze' ? 'frozen' : 'active';

        // Mettre à jour le statut de la carte
        $this->query(
            "UPDATE cards SET card_status = ?, updated_at = NOW() WHERE id = ?",
            [$new_status, $card_id]
        );

        // Créer le message de notification
        $message = $action === 'freeze' 
            ? "Your card has been frozen." 
            : "Your card has been unfrozen.";

        // Ajouter la notification
        $this->query(
            "INSERT INTO notifications (user_id, category, message)
             VALUES (?, ?, ?)",
            [$user_id, $action === 'freeze' ? 'card frozen' : 'card unfrozen', $message]
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

        // Répondre avec message + notifications
        $this->respond([
            "status" => "success",
            "message" => $message,
            "new_status" => $new_status,
            "notifications" => $this->query_data['recent_notifs']['data'] ?? []
        ]);
    }

    // Supprimer une carte et notifier
    public function delete_card() {
        $user_id = $_SESSION['userId'] ?? null;
        $card_id = $_POST['card_id'] ?? null;

        if (!$user_id || !$card_id) {
            $this->respond([
                "status" => "error",
                "message" => "Champs manquants"
            ]);
            return;
        }

        // Vérifier que la carte appartient bien à l'utilisateur
        $this->query(
            "SELECT id FROM cards WHERE id = ? AND user_id = ?",
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

        // Supprimer la carte
        $this->query(
            "DELETE FROM cards WHERE id = ?",
            [$card_id]
        );

        // Ajouter la notification
        $message = "Your card has been deleted.";

        $this->query(
            "INSERT INTO notifications (user_id, category, message)
             VALUES (?, 'card deleted', ?)",
            [$user_id, $message]
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

        // Répondre avec message + notifications
        $this->respond([
            "status" => "success",
            "message" => $message,
            "notifications" => $this->query_data['recent_notifs']['data'] ?? []
        ]);
    }

    // Dispatcher selon action
    public function get_content() {
        $action = $_POST['action'] ?? null;

        if ($action === 'delete') {
            $this->delete_card();
        } else if (in_array($action, ['freeze', 'unfreeze'])) {
            $this->toggle_card_status();
        } else {
            $this->respond([
                "status" => "error",
                "message" => "Action unknown"
            ]);
        }
    }
}

