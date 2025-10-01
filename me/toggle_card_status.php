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
                "message" => "Carte introuvable ou non autorisée"
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

    public function get_content() {
        $this->toggle_card_status();
    }
}

