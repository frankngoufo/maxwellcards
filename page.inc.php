<?php
/* 
A PHP Modular Framework coded by Cedric Che-Azeh
@che_azeh
www.blogvisa.com
*/

ini_set('session.use_only_cookies', TRUE); // Prevent transparent session ids
ini_set('session.use_trans_sid', FALSE); // Prevent leaking session ids
session_start();
date_default_timezone_set("Africa/Douala");
/* Page base class. All pages and modules would inherit from this class */

class Page {
	protected $css_linked;
	protected $css_text;
	protected $css_been_linked;//stores keys to all files already linked
	protected $css_linked_info;//see register_links()
	protected $is_css_local;

	private $css_common;
	private $css_page;
	private $css_module;

	protected $css_id;

	protected $js_linked;
	protected $js_text;
	protected $js_been_linked;//stores keys to all files already linked
	protected $js_linked_info;//see register_links()
	protected $is_js_local;
	protected $js_is_top;

	private $js_common;
	private $js_page;
	private $js_module;

	protected $viewport;
	protected $author;
	protected $desc;
	protected $lang;
	protected $title;
	protected $charset;
	protected $other_meta;

	public $load_args;
	public $load_data;
	public $load_stat;
	public $save_args;
	public $save_data;
	public $save_stat;
	public $save_data_flag;

	protected $body;
	protected $escaped_post;
	protected $escaped_get;
	public $formatter;

	public $site_name;
	public $dm;
	public $live;

	public function __construct() {
		$this->css_linked = "";
		$this->css_text = "";
		$this->css_been_linked = array();//stores keys to all files already linked
		$this->css_linked_info = array();//see register_links()
		$this->is_css_local = true;

		$this->css_common = "";
		$this->css_page = "";
		$this->css_module = "";

		$this->css_id = "";

		$this->js_linked = "";
		$this->js_text = "";
		$this->js_been_linked = array();//stores keys to all files already linked
		$this->js_linked_info = array();//see register_links()
		$this->is_js_local = true;
		$this->js_is_top = false;

		$this->js_common = "";
		$this->js_page = "";
		$this->js_module = "";

		$this->viewport = "";
		$this->author = "";
		$this->desc = "";
		$this->lang = "";
		$this->title = "";
		$this->charset = "";
		$this->other_meta = "";

		$this->load_args = array();
		$this->load_data = array();
		$this->load_stat = array();
		$this->save_args = array();
		$this->save_data = array();
		$this->save_stat = array();
		$this->save_data_flag = false;

		$this->body = "";
		$this->escaped_post = array();
		$this->escaped_get = array();
		$this->formatter = new STDClass();

		$this->site_name = "ECECP";
		$this->live = true;
	}

	//Assemble page for display in get_page()
	public function create() {
		// Obscure all errors;
		if ($this->live) {
			error_reporting(0);
			$this->is_js_local = false;
			$this->is_css_local = false;
		}
		//Assign all css and js links respectively to $css_linked_info and $js_linked_info
		$this->register_links();

		if ($this->save_data_flag) {
			$this->save_data();
		}

		//If this was called in page, it will set js to the top
		$this->set_js_top();

		//remember, $this->load_data will store data retrieved from backend
		$this->load_data();

		//call these methods so variables are set, ready to be retrieved
		$this->set_title();
		$this->set_meta();
		$this->set_css_id();

		//create() is called from a page, so do this before modules add their own css and js
		//files from modules have to be added last
		$this->set_css_common();
		$this->set_css_page();
		$this->set_js_common();
		$this->set_js_page();

		//header here has nothing to do with the <head> section
		$header = $this->get_header();
		$content = $this->get_content();
		$footer = $this->get_footer();

		//We should first have body wrapped in a div which holds all modules
		//This is useful for stylistic hooks
		$this->body = <<<EOD
<div id='siteBody'>
	$header
	$content
	$footer
</div><!--sitebody-->
EOD;

	//return so that any page wanting to override get_page() can have these contents
	return $this->body;
	}

	//Finally, return html for the entire page
	//Not returned in create() because pages might want do implement their own versions of get_page()
	public function get_page() {

		//configure id of body element for current page
		if (empty($this->css_id)){
			$css_id = "";
		} else {
			$css_id = "id = \"".$this->css_id."\"";
		}

		//this had a default implementation
		$doctype = $this->get_doctype();

		//these had already been configured in create() above
		$meta = $this->get_meta();
		$title = $this->get_title();

		//this was set in create() via set_meta()
		$lang = $this->lang;

		//We would have defined if we wanted js to be at the top or bottom on our page
		if ($this->js_is_top) {
			$top_js = $this->get_all_js();
			$bot_js = "";
		} else {
			$top_js = "";
			$bot_js = $this->get_all_js();
		}

		//css
		$css = $this->get_all_css();

		//Page all configured, return entire page
		return <<<EOD
$doctype

<!-- @che_azeh -->     

<html lang="$lang">
	<head>
		$meta
		$title
		$css
		$top_js
	</head>
	<body $css_id>
		{$this->body}
		$bot_js
	</body>
</html>
EOD;
		
	}


	/* _________PUBLIC INTERFACE - DEFAULT IMPLEMENTATION____________
				OVERRIDING COULD BE DANGEROUS!!! BE WARNED! */
	
	//gets doctype info of the page
	public function get_doctype($doctype = "") {
		if (empty($doctype)) {
			return <<<EOD
<!DOCTYPE html>
EOD;
		} else {
			return $doctype;
		}
	}

	//gets meta info of the page
	public function get_meta() {
		$meta = "";
		
		if (!empty($this->viewport)) {
			$meta .= <<<EOD
<meta name="viewport" content="{$this->viewport}" />
EOD;
		}
		
		if (!empty($this->desc)) {
			$meta .= <<<EOD
<meta name="description" content="{$this->desc}" />
EOD;
		}

		if (!empty($this->author)) {
			$meta .= <<<EOD
<meta name="author" content="{$this->author}" />
EOD;
		}

		if (!empty($this->charset)) {
			$meta .= <<<EOD
<meta charset="{$this->charset}" />
EOD;
		}

		if (!empty($this->other_meta)) {
			$meta .= <<<EOD
{$this->other_meta}
EOD;
		}
		return $meta;
	}

	//prints title of the page
	public function get_title() {
		return <<<EOD
<title>{$this->title}</title>
EOD;
	}

/*_________________________________________________________________________/
	THE FOUR FOLLOWING METHODS SHOULD BE CALLED ONLY FROM WITHIN MODULES
	THESE ARE GETTER METHODS FOR ***ADDING*** CSS AND JS SPECIFIC TO CERTAIN MODULES TO THE LIST
	OF CSS ADDED ALREADY BY PAGES TO WHICH THEY PERTAIN */

	//This is called from modules. Adds specified css key from a module to overall
	//Key must have been registered with register_links()
	//see get_css_linked() for performing same task within classes
	public function add_to_css_linked($k) {
		$this->css_linked .= $this->manage_css_linked($k);
	}

	//To be called from modules. Adds any raw css text to overall raw css text
	//see get_css_text() for performing same task within pages
	public function add_to_css_text($css) {
		$this->css_text .= $css;
	}

	//Called from within modules. Adds js keys to be transformed as embeddable code and placed on page
	//see get_js_linked() for a similar function for pages
	public function add_to_js_linked($keys) {
		$this->js_linked .= $this->manage_js_linked($keys);
	}

	//Called from within a module. Adds raw js code for inclusion at head section
	//se get_js_text() for a similar implementation for classes
	public function add_to_js_text($js) {
		$this->js_text .= $js;
	}
/*__________________________________________________________________________*/


	//Ensures each key has been registered with register_links(), else logs an error
	//Makes sure there are no duplicates in array of css files to be added
	private function manage_css_linked($keys) {
		$css = "";
		if (empty($keys)) {
			return "";
		}
		foreach ($keys as $k) {
			if (!array_key_exists($k, $this->css_linked_info)) {
				error_log("Page::manage_css_linked: Key \"".$k."\" missing");
				continue;
			}
			if (array_search($k, $this->css_been_linked) === false) {
				$this->css_been_linked[] = $k;
				$css .= $this->create_css_linked($k);
			}
		}
		return $css;
	}
	//Properly formats css keys sent from manage_css_linked() into embeddable code. 
	private function create_css_linked($k) {
		$path = $this->is_css_local ? $this->css_linked_info[$k]["loc_path"] : $this->css_linked_info[$k]["rem_path"];
		$rel = $this->css_linked_info[$k]["rel"];
		$sMedia = empty($this->css_linked_info[$k]["media"]) ? "all" : $this->css_linked_info[$k]["media"];
		$integrity = empty($this->css_linked_info[$k]["integrity"]) ? "" : "integrity='".$this->css_linked_info[$k]["integrity"]."' crossorigin='anonymous'";
		return <<<EOD
<link rel="$rel" href="$path" media="$sMedia" $integrity />
EOD;
	}

	//Properly set up raw css included from a module (add_to_css_text())
	//or from a page (get_css_text())
	private function create_css_text($css) {
		if (!empty($css)) {
			return <<<EOD
<style type="text/css"> $css </style>
EOD;
		}
	}

	//Sets all css files which will be common to all pages and modules (resets, fonts etc.)
	private function set_css_common() {
		$this->css_common .= $this->manage_css_linked($this->get_css_common());
	}

	//Sets css files and raw code gotten from all pages
	private function set_css_page() {
		$this->css_page .= $this->manage_css_linked($this->get_css_linked());
		$this->css_page .= $this->create_css_text($this->get_css_text());
	}

	//Gets entire css for the page. Start with global, then styles appended by pages
	//then styles appended by modules
	private function get_all_css() {
		//First combine all css from modules
		$this->css_module = $this->css_linked;
		$this->css_module .= $this->create_css_text($this->css_text);
		return <<<EOD
<!--COMMON CSS-->
$this->css_common
<!-- PAGE CSS -->
$this->css_page
<!-- MODULE CSS -->
$this->css_module
EOD;
	}


	

	//Makes sure all js keys passed to create_js_linked havee been registered
	//Assures no duplicates get added to the page
	private function manage_js_linked($keys) {
		$js = "";
		if (empty($keys)) {
			return "";
		}
		foreach ($keys as $k) {
			if (!array_key_exists($k, $this->js_linked_info)) {
				error_log("Page::manage_js_linked: Key \"".$k."\" missing");
				continue;
			}
			if (array_search($k, $this->js_been_linked) === false) {
				$this->js_been_linked[] = $k;
				$js .= $this->create_js_linked($k);
			}
		}
		return $js;
	}

	//Receive a filtered keys from manage_js_linked to properly format embeddable js code
	private function create_js_linked($k) {
		$path = $this->is_js_local ? $this->js_linked_info[$k]["loc_path"] : $this->js_linked_info[$k]["rem_path"];
		$integrity = empty($this->js_linked_info[$k]["integrity"]) ? "" : "integrity='".$this->js_linked_info[$k]["integrity"]."' crossorigin='anonymous'";

		return <<<EOD
<script type="text/javascript" $integrity src="$path"></script>
EOD;
	}

	//properly format raw js text for inlining at top of page
	private function create_js_text($js) {
		if (!empty($js)) {
			return <<<EOD
<script type='text/javascript'>$js</script>
EOD;
		}
	}

	//Sets up javascript common for all pages, like libraries 
	private function set_js_common() {
		$this->js_common = $this->manage_js_linked($this->get_js_common());
	}

	//Sets up javascript included at page level through get_js_linked() and get_js_text()
	private function set_js_page() {
		$this->js_page = $this->manage_js_linked($this->get_js_linked());
		$this->js_page .= $this->create_js_text($this->get_js_text());
	}

	//Gets entire js for the page. First gets common js (js for all files), then
	//js from pages and then js added by modules
	private function get_all_js() {
		//first compile js included by modules
		$this->js_module = $this->js_linked;
		$this->js_module .= $this->create_js_text($this->js_text);

		return <<<EOD
<!-- COMMON JS -->
{$this->js_common}
<!-- PAGE JS -->
{$this->js_page}
<!-- MODULE JS -->
{$this->js_module}
EOD;
	}





		/*________ABSTRACT INTERFACE - EMPTY IMPLEMENTATIONS______*/
		/*________YOU MUST OVERRIDE WHENEVER YOU WANNA USE.______*/

/*_______________________________________________________________________/
	  THE FOLLOWING SIX METHODS SHOULD BE OVERRIDDEN ONLY FROM WITHIN PAGES
	  THESE ARE GETTER METHODS FOR ASSIGNING PAGE CSS AND JS*/
	
	//return keys to css files common to all pages and included in register_links
	//call from sitewide page
	public function get_css_common() {}

	//return an array of css keys each page decides to add.
	//keys must have first been registered with register_links
	//must be called only from pages and not modules. see $add_to_css_linked() for modules
	public function get_css_linked() {}

	//Return raw css text to embed on the page. Called from specific pages
	//see add_to_css_text() for similar implementation for modules
	public function get_css_text() {}

	// For the three following functions, see comments for their css equivalents
	public function get_js_common() {}
	public function get_js_linked() {}
	public function get_js_text() {}

	//set a flag to indicate that JavaScript should be placed at the top
	public function set_js_top() {}
/*_________________________________________________________________________*/



	//Instatiate data managers to load data from the backend
	public function load_data() {}

	//Instatiate data managers to save data to the backend
	public function save_data() {}

	//returns html for the header of the page. Implement in sitewide page class
	public function get_header() {
	}

	//returns the html markup for the footer. Implement in sitewide page class
	public function get_footer() {
	}

	//returns the html markup for the content of a page. Implement in specific pages
	public function get_content() {}

	//set the page title
	public function set_title() {}

	//set the meta tags of the page
	public function set_meta() {}

	public function set_css_id() {}

	/* This is the function which assures no duplicates occur for css and js files.
	Define it in sitewide page and AUGMENT it in modules and sub-pages by calling
	the parent first */
	public function register_links() {}

	/*___ABSTRACT INTERFACE METHODS NEEDING INITIATION___*/
}