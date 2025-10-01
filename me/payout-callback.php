<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {

    public function __construct() {
        parent::__construct();
    }

    private function update_payout_transaction($gateway_reference, $status, $amount) {
        $this->query(
            "UPDATE transactions SET status = ?, amount = ? WHERE gateway_reference = ?",
            array($status, $amount, $gateway_reference),
            "update"
        );
    }

    public function get_content() {
        // Flutterwave peut envoyer les données en JSON brut
        $rawData = file_get_contents("php://input");
        $json = json_decode($rawData, true);

        if (!$json || !isset($json['data'])) {
            $this->respond([
                "status" => "error",
                "message" => "Données invalides ou manquantes."
            ]);
            return;
        }

        $data = $json['data'];
        $gateway_reference = $data['gateway_reference'] ?? null;
        $status = $data['status'] ?? null;
        $amount = $data['amount'] ?? 0;

        if (!$gateway_reference || !$status) {
            $this->respond([
                "status" => "error",
                "message" => "Paramètres de confirmation manquants."
            ]);
            return;
        }

        // Mettre à jour la transaction dans ta base
        $this->update_payout_transaction($gateway_reference, $status, $amount);

        // Retour succès
        $this->respond([
            "status" => "success",
            "message" => "Transaction mise à jour avec succès.",
            "reference" => $gateway_reference
        ]);
    }
}
