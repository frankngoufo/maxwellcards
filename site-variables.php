<?php
/**
 * Global variables and functions. These would be inherited by all sub-classes, and further
 * re-assignment won't be necessary
 *
 * @author  Cedric Che-Azeh
 * @license Maxwell Technologies PLC
 */
date_default_timezone_set("Africa/Douala");

require_once __DIR__ . '/access-control.php';
require_once __DIR__ . '/live.php';
require_once __DIR__ . '/db.php';


class API{

	/**
	 * API Keys
	 * @var string
	 */
    public $keys;

	/**
	 * State of the app (live or local)
	 * @var string
	 */
    public $live;

	/**
	 * root resource of the app
	 * @var string
	 */
    public $root;

	/**
	 * Base URL of API endpoint
	 * @var string
	 */
	public $home;

	/**
	 * Database connection object
	 * @var object
	 */
	public $db;

	/**
	 * Array for saving all queries to the database.
	 * @var array
	 */
	public $query_data;

	public $jwtSecret;


	/**
	 * Constructor
	 * Connect to the database
	 * Set default values for "global" variables
	 * 
	 * @return void
	 */
	public function __construct() {
		$this->live = new Live();
		$this->db = new DB($this->live->getDBParams()); // Conenct to database
		$this->query_data = array();
		$this->keys = array();
		$this->jwtSecret = '38nsGHQuidlkfh18bzh4890UjwyHqhfg3sncbvsskjfghqopzxmq83jnbduduwej';

		$this->getHome();
		$this->getRoot();
		$this->getKeys();
		$this->verifyKey();
	}

	/**
	 * Check for any database errors in the queries we did in this session
	 * @return boolean, whether there are errors or no
	 * */
	public function db_errors_exist() {
		foreach ($this->query_data as $key => $value) {
			if(!empty($value["stat"])) {
				return true;
			}
		}
		return false;
	}
    
	/**
	 * Perform a query on the database. 
	 * @param string $query: the query string
	 * @param array|string $params: query parameters. Can be a string or number if it's a single parameter,
	 * should be an array if it's multiple parameters.
	 * @param string $array_key: array key to store array of query statement, result and error.
	 * If no key is provided, use "default". In such a case, no query will be cached for later re-use,
	 * which could have a hit on performance.
	 * !!! NOTE !!! 
	 * ALL QUERIES MUST HAVE BOUND PARAMETERS. FOR QUERIES WITHOUT ONE, USE A "WHERE" STATEMENT AND 
	 * BIND WITH THE BOOLEAN "TRUE"
	 * EXAMPLE: "SELECT * FROM users WHERE ?". Bind the "?" to TRUE.
	 * See an example implementation from the getKeys function below
	 * @return void
	 */
	public function query($query, $param, $array_key = 'default') {

		$this->query_data[$array_key] = array(
			'query' => array($query, $param),
			'data'  => array(),
			'stat'	=> array()
		);
		$this->db->query($this->query_data[$array_key]);
	}
    
	/**
	 * Get the API keys of app from database
	 * Database name: api_keys
	 * Database Columns: id(INT), api_key(TINYTEXT), date_created(DATETIME), last_modified(DATETIME), uses(BIGINT)
	 * @return void
	 */
	public function getKeys() {
		$this->query( "SELECT * FROM api_keys WHERE api_key = ?", $_REQUEST['key'], 'keys' );
		$this->keys = $this->query_data['keys']['data'];
	}

	/**
	 * Get random key from database
	 * @return random key from database
	 * */
	public function get_random_key() {
		$this->query("SELECT api_key FROM api_keys WHERE ? ORDER BY id ASC LIMIT 1", TRUE, 'random_key');
		return $this->query_data['random_key']['data'][0]['api_key'];
	}
    
	/**
	 * Check if API key supplied matches what was sent
	 * @return void
	 */
	public function verifyKey() {
		if(empty($_REQUEST['key'])) {
			$this->respond(array('error' => 'Invalid Key'));
		}

		// Check if key was returned from DB
		if(empty($this->keys)) {
			$this->respond(array('error' => 'Invalid Key'));
		}
	}
	
	/**
	 * Get home page URL
	 * @return mixed
	 */
	public function getHome() {
		$this->home = $this->live->getHome();
	}
	
	/**
	 * Get API root URL
	 * @return mixed
	 */
	public function getRoot() {
		$this->root = $this->live->getRoot();
	}



	/**
	 * echo response from API to front end
	 * @param array $response: response to send
	 */
	public function respond($data) {
		echo json_encode($data);
		exit();
	}

	/**
	 * Properly encode string in base 64 format
	 * Useful during signing of access tokens for authentication
	 * @param string $source: the string to be encoded
	 * @return string $encodedSource: the encoded string
	 */

    public function base64url($source) {
      // Encode in classical base64
      $encodedSource = base64_encode($source);

      // Remove padding equal characters
      $encodedSource = preg_replace('/=+$/', '', $encodedSource);

      // Replace characters according to base64url specifications
      $encodedSource = preg_replace('/\+/', '-', $encodedSource);
      $encodedSource = preg_replace('/\//', '_', $encodedSource);

      // Return the base64 encoded string
      return $encodedSource;
    }

    /**
     * Generate JWT
     * @param int $payl: part of the payload to use in generating the token
     * 
     * */

    public function generateJWTToken($payl = array()) {

        // Generate JWT access token
        // First define token header
        $header = array(
          'alg' => 'HS256',
          'typ' => 'JWT',
        );

        // Calculate the issued at and expiration dates
        $iat = time();
        $exp = $iat + 2592e+6; // Now plus 30 days

        // Define token payload
        $p = array(
          'iat' => $iat,
          'iss' => 'your_app_name',
          'exp' => $exp,
        );
        $payload = array_merge($p, $payl);

        // Stringify and encode the header
        $stringifiedHeader = json_encode($header);
        $encodedHeader = $this->base64url($stringifiedHeader);

        // Stringify and encode the payload
        $stringifiedPayload = json_encode($payload);
        $encodedPayload = $this->base64url($stringifiedPayload);

        // Sign the encoded header
        $signature = $encodedHeader.".".$encodedPayload;
        $signature = hash_hmac('sha256', $signature, $this->jwtSecret);
        $signature = $this->base64url($signature);

        // Build and return the token
        return $encodedHeader.".".$encodedPayload.".".$signature;

    }

    /**
     * Verify JWT
     * This function is called when an access token is sent from client
     * It helps us ascertain that the request is actually coming
     * from the client in question and they are authenticated to access resources
     * on this server
     * @param string $token: The token sent from client. Usually comes in Authorization: Bearer file
     * @param boolean $admin: True if this is an admin logging in, false if it's a regular user
     * @return boolean verifed: true if token verifies, otherwise false
     * */

    public function verifyJWTToken($token, $admin = false) {
      
      // Split the token into parts
      if(!$token) {
      	return;
      }
      $parts = explode(".", $token);
      $header = $parts[0];
      $payload = $parts[1];
      $signature = $parts[2];

      // Re-sign and encode the header and payload using the secret
      $signatureCheck = $this->base64url(hash_hmac('sha256', $header.".".$payload, $this->jwtSecret));

      // Assign userId to a session
      $user = (array)json_decode(base64_decode($payload)); // First decode payload
      $admin ? $_SESSION['adminId'] = $user['id'] : $_SESSION['userId'] = $user['id'];

      // Verify that the resulting signature is valid
      return $signature === $signatureCheck;

    }

    /** 
     * Get header Authorization
     * */
    public function getAuthorizationHeader(){
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /**
     * get access token from header
     * */
    public function getBearerToken() {
        $headers = $this->getAuthorizationHeader();
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * Generate random string
     * @param length: length of string
     * */
    public function generate_random_string($length) {
    	return bin2hex(random_bytes($length));
    }

    /**
     * Get user's details
     * @param $userId: user's ID
     * @return array of user's details
     * */
    protected function get_user($userId) {
        $this->query(
            "SELECT id, first_name, last_name, email, tel, otp, address, country, city, UNIX_TIMESTAMP(otp_time) AS otp_time FROM users WHERE id = ?",
            $userId,
            "user"
        );
        return $this->query_data["user"]["data"][0];
    }

    /**
     * Make a CURL request
     * @return the response from the request
     * */

    public function curl_request($url, $header, $postfields = array(), $customrequest = "POST") {
	    $curl = curl_init();
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $customrequest);
	    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
	    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
	    if(!empty($postfields)) {
	    	curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
	    }

	    $response = curl_exec($curl);
	    curl_close($curl);
	    return curl_error($curl) ? curl_error($curl) : $response;

    }

    /**
     * Save a notification to the database
     * @param $userId INT id of user for the notification
     * @param $description STRING description
     * @param icon the ionic icon to use
     * @return void
     * */
    public function notify($userId, $description, $icon = "notifications-outline") {
    	$this->query(
    		"INSERT INTO notifications(userId, description, icon) VALUES(?,?,?)",
  			array($userId, $description, $icon),
  			"notification"
    		);
    }

	/**
	 * Send an email using PHP's Mail() function
	 * @param string $content: The content of the email, can be HTML formatted
	 * @param string $subject: The subject of the email
	 * @param string $from: The sender email. Note that per security, this should be on the same domain as this application.
	 * You can use formatted froms like "first_name last_name <email@domain.com>"
	 * @param string $to: Email(s) to send to, separated by a comma
	 */
	public function send_email($content, $subject, $from, $to) {
		$msg = <<<EOL
		<html>
			<body>
				<div style="width:95%;max-width:400px;padding:2.5%;background:#ffffff;margin:auto;">
					<img src="https://zenitheinsurance.com/img/logo.png" alt="Your Company Name" style="width:90%; max-width: 200px; display:block;margin:10px auto;" />
					<h1 style="text-align:center; color:#212121;">$subject</h1>
					$content
					<p style="color:#0099ff;font-size:1.1em;color:#212121;clear:both;padding-top:20px;">
						Your Company Name<br />
						Boulevard de la République<br/>
						face Palais DIKA AKWA (1388 Rue Bebey Eyidi)<br/>
						(237) 233 43 41 32 / (237) 694 30 82 32
					</p>
				</div>
			</body>
		</html>
EOL;
		$headers = "From: $from"."\r\n".
		"Reply-to: $from" ."\r\n" .
		'MIME-Version: 1.0' ."\r\n" .
		'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
		if($this->live->live) {
			mail($to, $subject, $msg, $headers);
		} else {
			file_put_contents("email.txt", $msg);
		}

	}

}