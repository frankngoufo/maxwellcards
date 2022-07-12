<?php 
// This page class overrides methods of the sitewide page class for section-specific methods

require_once("sitewide-page.inc.php");


Class SectionPage extends SitePage {
	public function __construct() {
		parent::__construct();
	}
}