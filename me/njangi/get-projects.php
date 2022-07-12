<?php
require_once("sectionwide-page.inc.php");

Class GetProjectsPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {
		$this->load_args['projects'] = array(
			"SELECT *, COUNT(project_contributions.contribution_date) AS total 
				FROM projects LEFT JOIN project_contributions 
				ON projects.id = project_contributions.projectId 
				WHERE projects.meetingId = ?
				GROUP BY projects.id",
			array($_GET['meetingId'])
		);
		$this->load_data['projects'] = array();
		$this->load_stat['projects'] = array();
		$this->dm->get_data($this->load_args['projects'], $this->load_data['projects'], $this->load_stat['projects']);
	}

	public function get_content() {
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			echo json_encode(array(
				'projects' 	=> $this->load_data['projects']
			));
		}
	}

}

if (API === TRUE) {
	$m = new GetProjectsPage();
	$m->get_content();
}