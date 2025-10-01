<?php
// Affiche les erreurs PHP (utile pour le debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . "/site-variables.php";

class Endpoint extends API {

    public function __construct() {
        parent::__construct();
    }

    private function load_data($telephone, $email) {
        $this->query(
            "SELECT * FROM users WHERE telephone = ? OR email = ?",
            array($telephone, $email),
            "user"
        );
    }

    private function save_otp($otp, $id) {
        $this->query(
            "UPDATE users SET otp = ?, otp_time = NOW() WHERE id = ?",
            array($otp, $id),
            "otp"
        );
    }

    private function save_new_user($telephone, $email, $otp) {
        $this->query(
            "INSERT INTO users (telephone, email, first_name, otp, otp_time, last_name)
             VALUES (?, ?, 'No Name', ?, NOW(), '')",
            array($telephone, $email, $otp),
            "new_user"
        );
    }

    public function get_content() {
        // Lire les données envoyées depuis Postman (form-data)
         $telephone = isset($_POST["telephone"]) ? trim($_POST["telephone"]) : null;
         $email = isset($_POST["email"]) ? trim($_POST["email"]) : null;
         $ismobileLogin = isset($_POST["ismobileLogin"]) ? $_POST["ismobileLogin"] : false;


        // Vérification minimale
        if (empty($telephone) && empty($email)) {
        $this->respond([
            "status" => "error",
            "message" => "Téléphone ou email obligatoire"
        ]);
        return;
    }

        // Vérifier si l'utilisateur existe
        $this->load_data($telephone, $email);

        if (!empty($this->query_data["user"]["data"])) {
            $this->respond([
                "user_id" => $this->query_data["user"]["data"][0]["id"],
                "user" => $this->query_data["user"]["data"][0],
            ]);
            return;
        }

        // Générer un OTP à 6 chiffres
        $otp = random_int(100000, 999999);
        $response = '[]';

        // Envoi OTP selon le type de login
        if ($ismobileLogin === true || $ismobileLogin == 1) {
            // Envoi par SMS
            $smsMessage = $otp . ' Maxwellcards';
            $url = "https://smsvas.com/bulk/public/index.php/api/v1/sendsms?user=info@phpvisa.com&password=biometrie2023&senderid=PHPVISA&mobiles={$telephone}&sms={$smsMessage}";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            // Envoi par email
            $this->send_email(
                "Your login code is $otp",
                "Your maxwellcards Login Code",
                "maxwell cards <phpvisa@maxwellsecure.net>",
                $email
            );
        }

        // Créer le nouvel utilisateur
        $this->save_new_user($telephone, $email, $otp);

        $this->respond([
            "status" => "success",
            "message" => "Utilisateur créé avec succès. OTP envoyé.",
            "user" => $_SESSION['lastInsertId'] ?? null,
            "otp" => $otp,
            "response" => json_decode($response)
        ]);
    }
}
