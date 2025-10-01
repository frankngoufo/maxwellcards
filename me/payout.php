<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    public function __construct() {
        parent::__construct();
    }

    public function get_content() {
        if (!isset($_POST['telephone']) || !isset($_POST['amount'])) {
            $this->respond([
                "status" => "error",
                "message" => "Paramètres manquants : telephone et amount requis."
            ]);
            return;
        }

        $telephone = trim($_POST['telephone']);
        $amount = (float) $_POST['amount'];

        $country = "CM";
        $currency = "XAF";
        $account_bank = "GHS"; // MPS pour MTN, GHS pour Orange selon Flutterwave doc
        $account_number = "237" . $telephone;

        $gateway_reference = "payout_" . $this->generate_random_string(12);

        $payload = [
            "account_bank" => $account_bank,
            "account_number" => $account_number,
            
            "amount" => $amount,
            "narration" => "Retrait via MaxwellCards demo",
            "currency" => $currency,
            "gateway_reference" => $gateway_reference,
            "callback_url" => "https://webhook.site/5f9a659a-11a2-4925-89cf-8a59ea6a019a",
            "debit_currency" => $currency
        ];
 
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.flutterwave.com/v3/transfers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer " . FLUTTERWAVE_SECRET_KEY,
                "Content-Type: application/json"
            ]
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $this->respond([
                "status" => "error",
                "message" => "Erreur cURL : " . curl_error($curl)
            ]);
            return;
        }

        curl_close($curl);

        $result = json_decode($response);

        if (isset($result->status) && $result->status === "success") {
            // Enregistrer dans la base si besoin...
            $this->respond([
                "status" => "success",
                "message" => $result->message,
                "reference" => $gateway_reference,
                "data" => $result->data
            ]);
        } else {
            $this->respond([
                "status" => "error",
                "message" => $result->message ?? "Erreur inconnue"
            ]);
        }
    }
}
