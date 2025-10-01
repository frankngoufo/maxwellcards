<?php
require_once __DIR__."/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }


    public function get_recent_transactions() {
         $user_id = $_SESSION['userId'] ?? null;

        if ($user_id) {
            $this->query(
                "SELECT id, user_id, amount, currency, status, payment_type, transaction_type, transaction_time 
                 FROM transactions 
                 WHERE user_id = ? 
                 ORDER BY transaction_time DESC 
                 LIMIT 10",
                [$user_id],
                "recent_tx"
            );
        }
        

        $this->respond([
            "status" => "success",
            "data" => $this->query_data['recent_tx']['data']
        ]);
    }

    public function get_content() {
        $this->get_recent_transactions();
    }
}

