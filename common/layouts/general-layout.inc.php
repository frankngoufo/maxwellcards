<?php
require_once("layout.inc.php");
require_once(PATH_COMMON_MODULE."header-module.inc.php");
require_once(PATH_COMMON_MODULE."footer-module.inc.php");
require_once(PATH_COMMON_MODULE."alerts-module.inc.php");

class GeneralLayout extends Layout {
	protected $content;
	protected $header;
	protected $footer;
	protected $alerts;

	public function __construct($page, $content, $header="", $footer="") {
		parent::__construct($page);
		$this->content = $content;

		$m = new AlertsModule($page);
		$this->alerts = $m->create();

		// Include header and footer to avoid doing it in pages
		if (empty($header)) {
			$m = new HeaderModule($page);
			$this->header = $m->create();
		} else {
			$this->header = $header;
		}
		
		if (empty($footer)) {
			$m = new FooterModule($page);
			$this->footer = $m->create();
		} else {
			$this->footer = $footer;
		}
	}

	public function get_content() {
		$m0 = $this->get_section("alerts", $this->alerts);
		$m1 = $this->get_section("topmost", $this->header, "header");
		$m2 = $this->get_section("wrapper", $this->content);
		$m3 = $this->get_section("endmost", $this->footer, "footer");

		return <<<EOD
		$m0
		$m1
		$m2
		$m3
EOD;
	}
}