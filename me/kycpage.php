<?php



require_once __DIR__ . '/section-variables.php';

class Endpoint extends API
{
    public function __construct()
    {
        parent::__construct();           // suppose que API définit $this->pdo
    }

    /**
     * Met à jour le KYC d’un utilisateur existant
     * @param array $data Données JSON décodées
     * @return array Réponse standardisée
     */
    public function update_user_kyc() {
        
   $id = $_POST['id'] ?? null;

    if (!$id) {
        $this->respond([
            "status" => "error",
            "message" => "ID utilisateur requis"
        ]);
        return;
    }

    // Récupération des champs KYC (adaptés à ta table)
    $address = $_POST['address'] ?? '';
    $profession = $_POST['profession'] ?? '';
    $activity_sector = $_POST['activity_sector'] ?? '';
    $city = $_POST['city'] ?? '';
    $country_id = $_POST['country_id'] ?? null;
    $id_type = $_POST['id_type'] ?? null;
    $id_number = $_POST['id_number'] ?? '';
    $display_photo = $display_photo_path ?? '';
    $id_card_image = $id_card_image_path?? '';
    $photo_with_id_card = $photo_with_id_card_path?? '';

    // Statut à mettre à jour si nécessaire (ex: passer à 'pending')
    $status = 'pending';

    //traitement de la photo
    $display_photo_base64 = $_POST['display_photo'] ?? '';
    $id_card_image_base64 = $_POST['id_card_image'] ?? '';
    $photo_with_id_card_base64 = $_POST['photo_with_id_card'] ?? '';

$display_photo_path = null;
$id_card_image_path = null;
$photo_with_id_card_path = null;

if ($display_photo_base64) {
    // Extraire le type MIME et les données
    if (preg_match('/^data:image\/(\w+);base64,/', $display_photo_base64, $type)) {
        $extension = strtolower($type[1]); // jpg, png, gif, etc.
        $display_photo_base64 = substr($display_photo_base64, strpos($display_photo_base64, ',') + 1);
        $display_photo_base64 = base64_decode($display_photo_base64);

        if ($display_photo_base64 === false) {
            $this->respond([
                "status" => "error",
                "message" => "Failed to decode profile picture"
            ]);
            return;
        }

        // Générer un nom de fichier unique
        $file_name = uniqid('photo_') . '.' . $extension;
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_path = $upload_dir . $file_name;
        file_put_contents($file_path, $display_photo_base64);

        // Sauvegarder le chemin relatif (ex: pour affichage dans l’app)
        $display_photo_path = 'uploads/' . $file_name;
    } else {
        $this->respond([
            "status" => "error",
            "message" => "Invalid image format"
        ]);
        return;
    }
} 
 if ($id_card_image_base64) {
    // Extraire le type MIME et les données
    if (preg_match('/^data:image\/(\w+);base64,/', $id_card_image_base64, $type)) {
        $extension = strtolower($type[1]); // jpg, png, gif, etc.
        $id_card_image_base64 = substr($id_card_image_base64, strpos($id_card_image_base64, ',') + 1);
        $id_card_image_base64 = base64_decode($id_card_image_base64);

        if ($id_card_image_base64 === false) {
            $this->respond([
                "status" => "error",
                "message" => "Failed to decode id card picture"
            ]);
            return;
        }

        // Générer un nom de fichier unique
        $file_name = uniqid('photo_') . '.' . $extension;
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_path = $upload_dir . $file_name;
        file_put_contents($file_path, $id_card_image_base64);

        // Sauvegarder le chemin relatif (ex: pour affichage dans l’app)
        $id_card_image_path = 'uploads/' . $file_name;
    } else {
        $this->respond([
            "status" => "error",
            "message" => "Invalid image format"
        ]);
        return;
    }
}


if ($photo_with_id_card_base64) {
    // Extraire le type MIME et les données
    if (preg_match('/^data:image\/(\w+);base64,/', $photo_with_id_card_base64, $type)) {
        $extension = strtolower($type[1]); // jpg, png, gif, etc.
        $photo_with_id_card_base64 = substr($photo_with_id_card_base64, strpos($photo_with_id_card_base64, ',') + 1);
        $photo_with_id_card_base64 = base64_decode($photo_with_id_card_base64);

        if ($photo_with_id_card_base64 === false) {
            $this->respond([
                "status" => "error",
                "message" => "Failed to decode profile picture"
            ]);
            return;
        }

        // Générer un nom de fichier unique
        $file_name = uniqid('photo_') . '.' . $extension;
        $upload_dir = __DIR__ . '/uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_path = $upload_dir . $file_name;
        file_put_contents($file_path, $photo_with_id_card_base64);

        // Sauvegarder le chemin relatif (ex: pour affichage dans l’app)
        $photo_with_id_card_path = 'uploads/' . $file_name;
    } else {
        $this->respond([
            "status" => "error",
            "message" => "Invalid image format"
        ]);
        return;
    }
} 

    // Requête de mise à jour
    $this->query(
        "UPDATE users SET 
            address = ?, 
            profession = ?, 
            activity_sector = ?, 
            city = ?, 
            country_id = ?, 
            id_type = ?, 
            id_number = ?, 
            display_photo = ?, 
            id_card_image = ?, 
            photo_with_id_card = ?, 
            status = ?
         WHERE id = ?",
        [
            $address, $profession, $activity_sector, $city, $country_id,
            $id_type, $id_number, $display_photo_path, $id_card_image_path,
            $photo_with_id_card_path, $status, $id
        ],
        "update_kyc"
    );

    // Vérifier s’il y a une erreur SQL
    if (!empty($this->query_data['update_kyc']['stat'])) {
        $this->respond([
            "status" => "error",
            "message" => "Échec de la mise à jour KYC",
            "error" => $this->query_data['update_kyc']['stat']
        ]);
        return;
    }

    $this->respond([
        "status" => "success",
        "message" => "KYC information successfully updated"
    ]);
}

    /**
     * Point d’entrée appelé par le routeur
     */
   public function get_content() {
        $this->update_user_kyc();
    }
}

