<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    
    }
    
    public function add_beneficiary($data = null) {
    $benefactor_user_id = $_SESSION['userId'] ?? null;

    if ($data !== null) {
        $beneficiary_name = trim($data['beneficiary_name'] ?? '');
        $beneficiary_mobile_number = trim($data['beneficiary_mobile_number'] ?? '');
    } else {
        $beneficiary_name = trim($_POST['beneficiary_name'] ?? '');
        $beneficiary_mobile_number = trim($_POST['beneficiary_mobile_number'] ?? '');
    }

    if (!$benefactor_user_id || empty($beneficiary_name) || empty($beneficiary_mobile_number)) {
        $this->respond([
            "status" => "error",
            "message" => "Missing required fields."
        ]);
        return;
    }

    try {
        $result = $this->query(
    "INSERT INTO beneficiaries (benefactor_user_id, beneficiary_name, beneficiary_mobile_number) VALUES (?, ?, ?)",
    [$benefactor_user_id, $beneficiary_name, $beneficiary_mobile_number]
);

// Tester si $result est différent de false (l'insertion a été tentée)
if ($result !== false) {
    $this->respond([
        "status" => "success",
        "message" => "Beneficiary successfully registered."
    ]);
} else {
    $this->respond([
        "status" => "error",
        "message" => "Error while saving beneficiary."
    ]);
}
    } catch (PDOException $e) {
        $this->respond([
            "status" => "error",
            "message" => "Erreur SQL : " . $e->getMessage()
        ]);
    }
}


    // Dispatcher des actions
    public function get_content() {
        

            $this->add_beneficiary();
        
            $this->respond([
                "status" => "error",
                "message" => "Action inconnue."
            ]);
      
    }
    
}

