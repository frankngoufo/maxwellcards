<?php




require_once __DIR__ . "/section-variables.php";

class Endpoint extends API {

  public function __construct() {
    parent::__construct();
  }

public function update_user_profile() {
    $id = $_POST['id'] ?? null;

    if (!$id) {
        $this->respond([
            "status" => "error",
            "message" => "ID requis"
        ]);
        return;
    }

    $first_name = $_POST['first_name'] ?? '';
    $last_name = $_POST['last_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['telephone'] ?? '';
    $city = $_POST['city'] ?? '';
    $country_id = $_POST['country_id'] ?? null;

    if (!$first_name || !$last_name || !$email || !$telephone ) {
        $this->respond([
            "status" => "error",
            "message" => "Tous les champs obligatoires ne sont pas remplis"
        ]);
        return;
    }

    // Vérifie que le pays existe
    $this->query(
        "SELECT * FROM countries WHERE id_country = ?",
        [$country_id],
        "country_check"
    );

    if (empty($this->query_data['country_check']['data'])) {
        $this->respond([
            "status" => "error",
            "message" => "Le pays sélectionné est invalide"
        ]);
        return;
    }

    $this->query(
        "UPDATE users SET 
            first_name = ?, 
            last_name = ?, 
            email = ?, 
            telephone = ?, 
            city = ?, 
            country_id = ? 
         WHERE id = ?",
        [$first_name, $last_name, $email, $telephone, $city, $country_id, $id],
        "update_user"
    );

    if (!empty($this->query_data['update_user']['stat'])) {
        $this->respond([
            "status" => "error",
            "message" => "Échec de la mise à jour",
            "error" => $this->query_data['update_user']['stat']
        ]);
        return;
    }

    $this->respond([
        "status" => "success",
        "message" => "Profil mis à jour avec succès"
    ]);
}



  public function get_content() {
    $this->update_user_profile();
  }
}
