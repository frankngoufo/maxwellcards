<?php 
if (API === TRUE && !empty($_REQUEST['userId'])) {
	$_SESSION["userId"] = $_REQUEST["userId"];
}

if (empty($_SESSION["userId"])) {
	header("Location: ".PATH_HOME."signup-login.php?redirect_to=".urlencode(strstr("$_SERVER[REQUEST_URI]", "me/")));
}

Class SectionPage extends SitePage {
    public $dm;
	public function __construct() {
		parent::__construct();
        $this->dm = new DataManager();

        // Verify user. 
        if ($this->verify_user() === false) {
        	exit();
        }
	}

    protected function get_interest($meetingId) {
        $this->load_args['interest'] = array(
            "SELECT * FROM meeting_defaults WHERE meetingId = ?",
            array($meetingId)
        );
        $this->load_data['interest'] = array();
        $this->load_stat['interest'] = array();
        $this->dm->get_data($this->load_args['interest'], $this->load_data['interest'], $this->load_stat['interest']);
    }


    protected function get_user_names($userId) {
        $this->load_args['user_names'] = array(
            "SELECT names FROM users WHERE id = ?",
            array($userId)
        );
        $this->load_data['user_names'] = array();
        $this->load_stat['user_names'] = array();
        $this->dm->get_data($this->load_args['user_names'], $this->load_data['user_names'], $this->load_stat['user_names']);
    }


    protected function get_meeting_details($meetingId) {
        $this->load_args['meeting_details'] = array(
            "SELECT name FROM meetings WHERE id = ?",
            array($meetingId)
        );
        $this->load_data['meeting_details'] = array();
        $this->load_stat['meeting_details'] = array();
        $this->dm->get_data($this->load_args['meeting_details'], $this->load_data['meeting_details'], $this->load_stat['meeting_details']);
    }

    protected function notify($device_ids, $notif_msg, $notif_title) {

        //API URL of FCM
        $url = 'https://fcm.googleapis.com/fcm/send';

        /*api_key available in:
        Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/    
        $api_key = 'AAAAp4YgAq4:APA91bHD29wQetbUrlfd-AiG0Op7PbDNH0ah18y4kaJ5vnUv4snhAk2w6MKAoYJwTcFVwBil1_SnyZDne43itsY7-2a8mTaCIv-zjMiHreRTfeR-tCKOeJkgbJRbkj3PP3gyj4Ncul1a';

        // Compose message
        $msg = array (
            'body'   => $notif_msg,
            'title'     => $notif_title,
            'vibrate'   => 1,
            'sound'     => 1,
            'priority'  => 'high'
        );

        $fields = array (
            'registration_ids'  => $device_ids,
            'notification'      => $msg
        );

        //header includes Content type and api key
        $headers = array(
            'Authorization: key=AAAAp4YgAq4:APA91bHD29wQetbUrlfd-AiG0Op7PbDNH0ah18y4kaJ5vnUv4snhAk2w6MKAoYJwTcFVwBil1_SnyZDne43itsY7-2a8mTaCIv-zjMiHreRTfeR-tCKOeJkgbJRbkj3PP3gyj4Ncul1a',
            'Content-Type: application/json'
        );
                    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    

}