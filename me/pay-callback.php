<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {

    public function __construct() {
        parent::__construct();
    }

    private function get_transaction($transactionId, $gatewayReference) {
        $this->query(
            "SELECT id, currency, amount FROM transactions WHERE gateway_transaction_id = ? AND gateway_reference = ?",
            array($transactionId, $gatewayReference),
            "transaction"
        );
    }

    private function update_transaction($id, $amount) {
        // ✅ Corrigé : on met aussi à jour amount
        $this->query(
            "UPDATE transactions SET `status` = ?, `amount` = ? WHERE id = ?",
            array("successful", $amount, $id),
            "update"
        );
    }

    public function get_content() {
        if (!isset($_POST['gateway_transaction_id']) || !isset($_POST['gateway_reference'])) {
            $this->respond([
                "status" => "error",
                "message" => "Paramètres manquants : gateway_transaction_id et gateway_reference requis."
            ]);
            return;
        }

        $transactionId = $_POST['gateway_transaction_id'];
        $gatewayReference = $_POST['gateway_reference'];
        $secretKey = FLUTTERWAVE_SECRET_KEY;

        $url = "https://api.flutterwave.com/v3/transactions/{$transactionId}/verify";
        $headers = [
            'Authorization: Bearer ' . $secretKey,
            'Content-Type: application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            $this->respond([
                "status" => "error",
                "message" => "Erreur cURL : " . curl_error($ch)
            ]);
            curl_close($ch);
            return;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode != 200) {
            $this->respond([
                "status" => "error",
                "message" => "Erreur HTTP lors de la vérification. Code: " . $httpCode
            ]);
            return;
        }

        $details = json_decode($response);
        if (!$details || !isset($details->data)) {
            $this->respond([
                "status" => "error",
                "message" => "Réponse Flutterwave invalide"
            ]);
            return;
        }

        $data = $details->data;

        if ($data->status === 'successful') {
            $this->get_transaction($transactionId, $gatewayReference);

            if (!empty($this->query_data["transaction"]["data"])) {
                $tx = $this->query_data["transaction"]["data"][0];

                if ($data->currency === $tx["currency"] && $data->amount >= $tx["amount"]) {
                    $this->update_transaction($tx["id"], $data->amount);
                    $this->respond([
                        "status" => "success",
                        "message" => "Transaction confirmée et mise à jour.",
                        "transaction_id" => $tx["id"]
                    ]);
                } else {
                    $this->respond([
                        "status" => "error",
                        "message" => "Montant ou devise incorrect(e)."
                    ]);
                }
            } else {
                $this->respond([
                    "status" => "error",
                    "message" => "Transaction introuvable dans la base de données."
                ]);
            }

        } elseif ($data->status === 'pending') {
            $this->respond(["pending" => 1]);
        } else {
            $this->respond([
                "status" => "error",
                "message" => "Statut de la transaction : " . $data->status
            ]);
        }
    }
}
