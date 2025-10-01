<?php
require_once __DIR__."/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }
      /**
     * get_all_transactions()
     * Purpose: Retrieve all transactions for the currently logged-in user.
     * Inputs: None explicitly via POST or GET; user ID is retrieved from session ($_SESSION['userId']).
     * Behavior:
     *  - Queries the transactions table to get all records for the user.
     *  - Orders the results by transaction_time in descending order.
     *  - Returns JSON response with status and data (list of transactions).
     */

    public function get_all_transactions() {
        $user_id = $_SESSION['userId'] ?? null;

        if ($user_id) {
            $this->query(
                "SELECT id, user_id, amount, currency, status, payment_type, transaction_type, transaction_time 
                 FROM transactions 
                 WHERE user_id = ? 
                 ORDER BY transaction_time DESC",
                [$user_id],
                "all_tx"
            );
        }

        $this->respond([
            "status" => "success",
            "data" => $this->query_data['all_tx']['data']
        ]);
    }

    public function get_content() {
        $this->get_all_transactions();
    }
}

