<?php
require_once("sectionwide-page.inc.php");

Class ProjectContPage extends NjangiSectionPage {

	public function __construct() {
		parent::__construct();
	}

	public function load_data() {		
		$this->load_args['contributions'] = array(
				"SELECT 
			(SELECT COUNT(*) FROM project_contributions WHERE projectId = :projectId) AS total,
			(SELECT amount FROM projects WHERE id = :projectId) AS amount,
				userId, contribution_date FROM project_contributions WHERE projectId = :projectId",
				array(':projectId' => $_GET['projectId'])
			);
		$this->load_data['contributions'] = array();
		$this->load_stat['contributions'] = array();
		$this->dm->get_data($this->load_args['contributions'], $this->load_data['contributions'], $this->load_stat['contributions']);
	}

	public function get_content() {
		$this->get_user_meetings($_GET['meetingId']);
		$this->load_data();

		if (empty($this->check_db_errors($this->load_stat))) {
			$users = array();

			// Normalize 
			// First, set all contributions into an array with keys as user IDs, then iterate over all users
			// and check if keys in that array correspond to them (meaning they've contributed)
			$cont_array = array();
			foreach ($this->load_data['contributions'] as $cont) {
				$cont_array[$cont['userId']] = $cont; // Index of userId equals contribution array
			}
			foreach ($this->load_data['user_meetings'] as $key => $user) {
				$users[$key] = $user;
				$users[$key]['total'] = $this->load_data['contributions'][0]['total'];
				$users[$key]['amount'] = $this->load_data['contributions'][0]['amount'];

				if (array_key_exists($user['id'], $cont_array)) {
					$users[$key]['contribution'] = $cont_array[$user['id']];
				}
			}

			echo json_encode(array(
				'users' 	=> $users
			));
		}
	}

}

if (API === TRUE) {
	$m = new ProjectContPage();
	$m->get_content();
}