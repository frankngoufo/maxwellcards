<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }
 
    public function add_notifications() {
        // Récupérer les données depuis POST ou JSON
        $data = $_POST;
        // Si tu utilises du JSON dans Postman, fais :
        // $data = json_decode(file_get_contents("php://input"), true);

        $user_id = $data['user_id'] ?? null;
        $category = $data['category'] ?? null;
        $amount = $data['amount'] ?? null;
        $message = $data['message'] ?? null;

        if (!$user_id || !$category || !$message) {
            $this->respond([
                "status" => "error",
                "message" => "Champs manquants"
            ]);
            return;
        }

        // Appel de la méthode d’insertion
        $this->query(
    "INSERT INTO notifications (user_id, category, amount, message) VALUES (?, ?, ?, ?)",
    [$user_id, $category, $amount, $message]
);


        $this->respond([
            "status" => "success",
            "message" => "Notification added successfully"
        ]);
    }

    public function get_content() {
        $this->add_notifications();
    }
}


