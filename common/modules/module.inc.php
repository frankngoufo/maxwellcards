<?php
//Module Base class. 
//In sub-classes, you can nest modules within other modules. Do that by calling
//modules as you do within page classes, but remember to pass the page class
//as the argument, and not $this (rather, $this->page)
class Module {
	//reference to the page from where module will be called. 
	protected $page;

	public function __construct($page) {
		$this->page = $page;
	}

	//Assemble all the content for the module here
	public function create() {
		$this->page->add_to_css_linked($this->get_css_linked());
		$this->page->add_to_css_text($this->get_css_text());
		$this->page->add_to_js_linked($this->get_js_linked());
		$this->page->add_to_js_text($this->get_js_text());

		return $this->get_content();
	}

	// All product displays will have this format
	public function display_events($events) {
		$content = "";

		foreach ($events as $event) {
			$dt_mktime = strtotime($event["event_date"]);
			$event_day = date("d", $dt_mktime);
			$event_month = date("F", $dt_mktime);
			$url = PATH_HOME."single/?event_id=".$event["id"];

			$content .= <<<EOL
						<div class='col-12'>
							<a href="$url#tickets" class="d-block event">
								<div class="event-img d-flex justify-content-center align-items-center" style="background-image: url($event[cover_image])">
									<!--
									<div class='pricing'>
										<i class="fas fa-ticket-alt"></i>
										<h3>XAF<br />$event[pricing]</h3>
									</div>
									-->
								</div>
								<div class="event-header text-center d-flex justify-content-between">
									<div class="event-date">
										<h3>$event_day</h3>
										<p>$event_month</p>
									</div>
									<div class="event-date">
										<h3><i class="fas fa-map-marker-alt"></i></h3>
										<p>$event[city]</p>
									</div>
								</div>
								<!--<div class="event-content text-justify">
									<h1 class="text-center">$event[name]</h1>
									<p>$event[description]</p>
								</div>-->
								<div class="event-footer">
									<buttn class="btn btn-primary btn-lg"><i class="fas fa-ticket-alt"></i>Buy Tickets</button>
								</div>
							</a>
						</div>
EOL;
		}
		return $content;
	}

	//Abstract interface. Override in sub-classes as needed. See page.php for a documentation
	//of these functions. Must register files with register_links() in page class
	public function get_css_linked() {}
	public function get_css_text() {}
	public function get_js_linked() {}
	public function get_js_text() {}

	public function get_content() {}
}