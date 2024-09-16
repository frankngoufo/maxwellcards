<?php
require_once __DIR__."/site-variables.php";
require __DIR__ . '/vendor/autoload.php';
use Twilio\Rest\Client;

Class Endpoint extends API {
    
    public function __construct() {
        parent::__construct();
    }

    public function load_data() {
        $this->query(
            "SELECT id FROM users WHERE tel = ? AND stat > ?",
            array($_POST["tel"], 0),
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

    private function save_new_user($tel,$otp) {
      $this->query(
        "INSERT INTO users (tel,first_name,last_name,otp,country,otp_time) VALUES(?,?,?,?,?, NOW())",
        array($tel, "No Name", "", $otp, $_POST['country']),
        "new_user"
      );
    }

    public function get_content() {
        $this->load_data();
        if (empty($this->query_data["user"]["stat"])) {

            // Generate OTP
            $otp = random_int(1000, 9999);

            // Send Emailemail
            // $this->send_email(
            //     "Your login code is $otp",
            //     "Your Court Scheduler Login Code",
            //     "Court Scheduler PLC <admin@scrm-cloud.net>",
            //     $_POST['email']
            // );

            // Send SMS
            // A Twilio number you own with SMS capabilities
            $twilio_number = "+19049002981";

            $client = new Client(SMS_ID, SMS_TOKEN);
            $to = "+" . ltrim($_POST['tel'], '+');

            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $to,
                array(
                    'from' => $twilio_number,
                    'body' => "Your Waste2Rewards login code is $otp"
                )
            );

            if ( !empty($this->query_data["user"]["data"]) ) {

              // Save OTP
              $this->save_otp($otp, $this->query_data["user"]["data"][0]["id"]);
              $this->respond(array("user" => $this->query_data["user"]["data"][0]["id"]));
            } else {
              
              // User doesn't exist, create them and send OTP
              $this->save_new_user($_POST['tel'], $otp);
              $this->respond(array("user" => $_SESSION['lastInsertId']));
            }
        }
    }
}