<?php
require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {
    private $gateway_reference;

    public function __construct() {
        parent::__construct();
    }

    private function save_transaction($status, $amount, $payment_type, $gateway_reference, $currency, $gateway_transaction_id) {
        $this->query(
            "INSERT INTO transactions (user_id, status, amount, payment_type, reference, gateway_reference, transaction_type, currency, gateway_transaction_id)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)",
            array(
                $_SESSION['userId'] ?? null, // ou NULL si pas connecté
                $status,
                $amount,
                $payment_type,
                $this->gateway_reference,
                $gateway_reference,
                "expense",
                $currency,
                $gateway_transaction_id
            ),
            "transaction"
        );
    }

    private function pay() {
        if (!isset($_POST['telephone']) || empty(trim($_POST['telephone']))) {
            $this->respond([
                "status" => "error",
                "message" => "Paramètre manquant : telephone requis."
            ]);
            return null;
        }

        $telephone = trim($_POST['telephone']);

        $email = "no-reply@example.com";
        if (isset($_SESSION['userId'])) {
            $user = $this->get_user($_SESSION['userId']);
            if (!empty($user['email'])) {
                $email = $user['email'];
            }
        }

        $country = "CM";
        $countryCode = $country === "CM" ? "237" : "221";
        $currency = $country === "CM" ? "XAF" : "XOF";
        $tx_ref = "TXREF_" . uniqid();

        $this->gateway_reference = $this->generate_random_string(16);

      $payload = [
			"type" => "mobile_money_franco",
			"phone_number" => $countryCode . $_POST['telephone'],
            "tx_ref" => $tx_ref,
	        // "amount" => $total,
			"amount" => 1,
			"currency" => $currency,
			"country" => $country,
			"email" => $user["email"],
			"gateway_reference" => $this->gateway_reference,
		];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.flutterwave.com/v3/charges?type=mobile_money_franco',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . FLUTTERWAVE_SECRET_KEY,
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        if ($response === false) {
            $error = curl_error($curl);
            curl_close($curl);
            $this->respond([
                "status" => "error",
                "message" => "Erreur cURL : " . $error
            ]);
            return null;
        }

        curl_close($curl);

        $result = json_decode($response);

        if (isset($result->status) && $result->status === "success") {
            $data = $result->data;

            $this->save_transaction(
                $data->status ?? "pending",
                $data->amount ?? 0,
                $data->payment_type ?? "mobile_money_franco",
                $data->flw_ref ?? "",
                $data->currency ?? $currency,
                $data->id ?? 0
            );

            $this->respond([
                "status" => "success",
                "message" => $result->message ?? "",
                "reference" => $this->gateway_reference,
                "gateway_transaction_id" => $data->id ?? null,
                "data" => $data
            ]);
        } else {
            $this->respond([
                "status" => "error",
                "message" => $result->message ?? "Erreur lors de la requête Flutterwave."
            ]);
        }
    }

    public function get_content() {
        $this->pay();
    }
}
