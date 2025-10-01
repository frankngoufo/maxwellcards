<?php
require_once __DIR__."/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }
 
   public function get_recent_notifications() {
    $user_id = $_SESSION['userId'] ?? null;

    if (!$user_id) {
        $this->respond([
            "status" => "error",
            "message" => "Utilisateur non connecté"
        ]);
        return;
    }

    $this->query(
        "SELECT id, category, amount, notification_time, message, is_read 
         FROM notifications 
         WHERE user_id = ?
         ORDER BY notification_time DESC
         LIMIT 5",
        [$user_id],
        "notifications"
    );

    $this->respond([
        "status" => "success",
        "notifications" => $this->query_data['notifications']['data'] ?? []
    ]);
}


    public function get_content() {
        $this->get_recent_notifications();
    }
}

