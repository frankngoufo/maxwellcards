<?php



require_once __DIR__ . "/section-variables.php";

class Endpoint extends SectionVariables {

  public function __construct() {
    parent::__construct();
  }

  public function get_user_profile() {
    $id = $_SESSION['userId'] ?? null;

    if (!$id) {
        $this->respond([
            "status" => "error",
            "message" => "ID utilisateur manquant"
        ]);
        return;
    }

    // Requête pour récupérer les infos utilisateur
    $this->query(
        "SELECT * FROM users WHERE id = ?",
        [$id],
        "user_info"
    );

    $user = $this->query_data['user_info']['data'][0] ?? null;

    if (!$user) {
        $this->respond([
            "status" => "error",
            "message" => "Utilisateur introuvable"
        ]);
        return;
    }

    // Requête pour récupérer la liste des pays
    $this->query(
    "SELECT id_country, name FROM countries",
    [],
    "countries"
);


    $this->respond([
        "status" => "success",
        "user" => $user,
        "countries" => $this->query_data['countries']['data']
    ]);
  }

  public function get_content() {
    $this->get_user_profile();
  }

} // ← NE PAS enlever cette accolade, elle ferme la classe Endpoint

