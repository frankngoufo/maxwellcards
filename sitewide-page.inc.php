<?php
/*Sitewide page: A page with implemetations liable to affect the entire site,
like common css and js inclusion, "global" variables which the whole site will use, etc.
Define these globals as constants */

//Define all global variables here


/*
//change depending whether you're testing or developing
define("PATH_ROOT", $_SERVER['DOCUMENT_ROOT']."/phpvisa/" );
define("PATH_HOME", "http://localhost/phpvisa/");

*/
define("PATH_ROOT", $_SERVER['DOCUMENT_ROOT']."/" );
define("PATH_HOME", 'https://'.$_SERVER['HTTP_HOST']."/");


define("PATH_ME", PATH_HOME."me/");
define("PATH_COMMON", PATH_ROOT."common/");
define("PATH_COMMON_MODULE", PATH_COMMON."modules/");
define("PATH_COMMON_DATA_MANAGER", PATH_COMMON."data-managers/");
define("PATH_RESSOURCE_IMG", PATH_ROOT."/base/img/");// Passed as resource for uploads
define("PATH_IMG", PATH_HOME."/base/img");
define("API_KEY", "YOUR_API_KEY");


require_once("page.inc.php");
require_once(PATH_COMMON_DATA_MANAGER.'data-manager.inc.php');


class SitePage extends Page {
	public function __construct() {
		parent::__construct();
		$this->dm = new DataManager();
	}
	
	// Data access function. It's inherited in child classes
	public function load_data() {
	}


	//Check any sql errors and halt execution if found
	public function check_db_errors($stat) {
		$content = "";
		foreach ($stat as $k) {
			if (!empty($k)) {
				$content .= "Error fetching / saving data";
				return $content;
			}
		}
		return $content;
	}

	/*
	Every access to the API for a logged in user must contain a reset token.
	The reset token resets upon each request the user makes to the application.
	This protects against session hijacking. What this function does is simply check if the reset token matches what is saved
	in the database
	*/
	public function verify_user() {
		$this->load_args["verify_user"] = array(
			"SELECT id FROM users WHERE id = ? AND reset_token = ?",
			array($_REQUEST["userId"], $_REQUEST["reset_token"])
		);
		$this->load_data["verify_user"] = array();
		$this->load_stat["verify_user"] = array();
		$this->dm->get_data( $this->load_args["verify_user"], $this->load_data["verify_user"], $this->load_stat["verify_user"]);
		return empty($this->load_data["verify_user"]) ? false : true;
	}
	
	// Format Time
	public function format_time($dt_time) {
		$nowTime = new DateTime();
		$savedTime = new DateTime($dt_time);
		$interval = $savedTime->diff($nowTime);
		//Let us avoid integer overflow
		$days = $interval->format('%d');
		//format mysql datetime to unix timestamp
		$mysqldate = strtotime( $dt_time );
		if($days > 2) {
			$phpdate = date( 'F j Y', $mysqldate );
			$phptime = date( 'g:i a', $mysqldate );
			$finTime = $phpdate." at ".$phptime;
		} else if ($days > 0) {
			$phptime = date( 'g:i a', $mysqldate );
			$finTime = "Yesterday at ".$phptime;
		} else {
			//days is less than 1. Get hours
			$hours = $interval->format('%h');
			if($hours > 1) {
				$finTime = $hours." hours ago";
			} else {
				//hours is less than 1. Get minutes
				$mins = $interval->format('%i');
				if($mins < 2) {
					$finTime = "Just now";
				} else {
					$finTime = $mins." minutes ago";
				}
			}
		}
		return $finTime;
	}

	// Append query string to URL of current page
	public function query_to_url($url, $query, $val) {
		$url_parts = parse_url($url);
		if (!empty($url_parts["query"])) {
			parse_str($url_parts['query'], $params);

			$params[$query] = $val;     // Overwrite if exists

			// Note that this will url_encode all values
			$url_parts['query'] = http_build_query($params);

			// If not
			return '//' . $url_parts['host'] . $url_parts['path'] . '?' . $url_parts['query'];
		} else {
			return '//'. $url_parts['host'] . $url_parts['path'] . "?$query=$val";
		}
	}

	public function send_email($content, $subject, $from, $to) {
		$img = PATH_IMG;
		$msg = <<<EOL
		<html>
			<body>
				<div style="width:95%;max-width:400px;padding:2.5%;background:#ffffff;margin:auto;">
					<img src="$img/logo.png" alt="E-tickets" style="width:150px;display:block;margin:10px auto;" />
					<h1 style="text-align:center; color:#212121;">$subject</h1>
					<p>
						$content
					</p>
					<p style="font-weight:bold;font-size:1.1em;color:#212121;clear:both;padding-top:20px;">
						E-tickets<br />
						An Eternal Company Limited Brand
					</p>
				</div>
			</body>
		</html>
EOL;
		$headers = "From: $from"."\r\n".
		"Reply-to: $from" ."\r\n" .
		'MIME-Version: 1.0' ."\r\n" .
		'Content-Type: text/html; charset=ISO-8859-1' . "\r\n";
		if($this->live) {
			mail($to, $subject, $msg, $headers);
		} else{
			$fh = fopen(PATH_ROOT."email.htm", "w"); fwrite($fh, "To: $to. Subject: $subject. Message: $msg"); fclose($fh);
		}

	}

	public function generate_random_string(
	    int $length = 64,
	    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
	): string {
	    if ($length < 1) {
	        throw new \RangeException("Length must be a positive integer");
	    }
	    $pieces = [];
	    $max = mb_strlen($keyspace, '8bit') - 1;
	    for ($i = 0; $i < $length; ++$i) {
	        $pieces []= $keyspace[random_int(0, $max)];
	    }
	    return implode('', $pieces);
	}
}