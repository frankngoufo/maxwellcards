<?php
require_once(PATH_ROOT."me/sectionwide-page.inc.php");

Class MyProjectsPage extends SectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {

		// First get all projects
		$this->load_args['projects'] = array(
			"SELECT 
				(SELECT SUM(amount) 
					FROM projects INNER JOIN project_contributions ON projects.id = projectId 
					WHERE userId = :user AND projects.meetingId = :meetingId)
				AS total, 
			name, amount, id FROM projects WHERE meetingId = :meetingId",
			array(':user' => $_SESSION['userId'], ':meetingId' => $_GET['meetingId'])
		);
		$this->load_data['projects'] = array();
		$this->load_stat['projects'] = array();
		$this->dm->get_data($this->load_args['projects'], $this->load_data['projects'], $this->load_stat['projects']);


		// Then get all projects I've contributed to
		$this->load_args['contributions'] = array(
			"SELECT * FROM project_contributions WHERE userId = ? AND meetingId = ?",
			array($_SESSION['userId'], $_GET['meetingId'])
		);
		$this->load_data['contributions'] = array();
		$this->load_stat['contributions'] = array();
		$this->dm->get_data($this->load_args['contributions'], $this->load_data['contributions'], $this->load_stat['contributions']);

	}

	public function get_content() {
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {

			// First get all contributions into an array, and then match all projects with
			// contributions in that array, to find out all projects user has contributed to
			$projects = $contrs = array();
			foreach ($this->load_data['contributions'] as $contr) {
				$contrs[$contr['projectId']] = $contr;
			}

			foreach ($this->load_data['projects'] as $key => $project) {
				$projects[$key] = $project;
				if (array_key_exists($project['id'], $contrs)) {
					$projects[$key]['contribution_date'] = $contrs[$project['id']]['contribution_date'];
				}
			}

			echo json_encode(array(
				'projects' 	=> $projects
			));
		} else 
			print_r($this->load_stat);
	}

}

if (API === TRUE) {
	$m = new MyProjectsPage();
	$m->get_content();
}