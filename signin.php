<?php
require_once __DIR__ . "/site-variables.php";
// require __DIR__ . '/vendor/autoload.php';

// use Twilio\Rest\Client;

class Endpoint extends API {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->query(
			"SELECT id FROM users WHERE (tel = ? OR email = ?) AND stat > ?",
			array($_POST["tel"], $_POST['email'], 0),
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

	private function save_new_user($tel, $email, $otp) {
		$this->query(
			"INSERT INTO users (stat,tel,email,first_name,otp,otp_time,last_name) VALUES(?,?,?,?, NOW(),?)",
			array(0, $tel, $email, "No Name", $otp, ""),
			"new_user"
		);
	}

	public function get_content() {
		$this->load_data();
		if (empty($this->query_data["user"]["stat"])) {

      		// Generate OTP
			$otp = random_int(1000, 9999);
			$response = '[]';

			if ($_POST['ismobileLogin'] === true) {
        		
        		// Logging in with phone
				$phoneNumber = $_POST["tel"];
				$smsMessage = $otp . '-PHPVISA';
				$url = "https://smsvas.com/bulk/public/index.php/api/v1/sendsms?user=info@phpvisa.com&password=biometrie2023&senderid=PHPVISA&mobiles={$phoneNumber}&sms={$smsMessage}";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        		// Execute the cURL request
				$response = curl_exec($ch);
        		// Close cURL session to free up resources
				curl_close($ch);
			} else {
				// Logging in with email
				$this->send_email(
				    "Your login code is $otp",
				    "Your PHPVisa Login Code",
				    "PHPVisa <phpvisa@maxwellsecure.net>",
				    $_POST['email']
				);

			}


			if (!empty($this->query_data["user"]["data"])) {

        		// Save OTP
				$this->save_otp($otp, $this->query_data["user"]["data"][0]["id"]);
				$this->respond(array("user" => $this->query_data["user"]["data"][0]["id"], 'response' => json_decode($response)));
			} else {

        		// User doesn't exist, create them and send OTP
				$this->save_new_user($_POST["tel"], $otp);
				$this->respond(array("user" => $_SESSION['lastInsertId'], 'response' => json_decode($response)));
			}
		}
	}
}
