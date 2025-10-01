<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
      
    }

    public function get_beneficiaries() {
        $user_id = $_SESSION['userId'] ?? null;

        if (!$user_id) {
            $this->respond([
                "status" => "error",
                "message" => "Utilisateur non authentifié."
            ]);
            return;
        }

        // Récupérer les bénéficiaires
        $this->query(
            "SELECT beneficiary_id, beneficiary_name, beneficiary_mobile_number, created_at, updated_at
             FROM beneficiaries
             WHERE benefactor_user_id = ?",
            [$user_id],
            "beneficiaries_list"
        );

        $beneficiaries = $this->query_data['beneficiaries_list']['data'] ?? [];

        $this->respond([
            "status" => "success",
            "beneficiaries" => $beneficiaries
        ]);
    }

    // Dispatcher selon l'action
    public function get_content() {
        

            $this->get_beneficiaries();
        
            $this->respond([
                "status" => "error",
                "message" => "Action inconnue."
            ]);
   
    }
}

