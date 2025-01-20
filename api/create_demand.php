<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

session_start();

try {
    $db = connect_db();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_SESSION['user_id'])) {
            send_json_response(['error' => 'Você precisa estar logado para criar uma demanda.'], 401);
        }

        $category = validate_input($_POST['category'] ?? '');
        $description = validate_input($_POST['description'] ?? '');
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;

        if (empty($category) || empty($description) || $latitude === null || $longitude === null) {
            send_json_response(['error' => 'Por favor, forneça categoria, descrição e localização da demanda.'], 400);
        }

        $stmt = $db->prepare("INSERT INTO demands (user_id, category, description, latitude, longitude) VALUES (:user_id, :category, :description, :latitude, :longitude)");
        $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);
        $stmt->bindParam(':description', $description, PDO::PARAM_STR);
        $stmt->bindParam(':latitude', $latitude); // PDO::PARAM_STR ou PDO::PARAM_FLOAT dependendo do tipo da coluna
        $stmt->bindParam(':longitude', $longitude); // PDO::PARAM_STR ou PDO::PARAM_FLOAT dependendo do tipo da coluna
        $stmt->execute();

        send_json_response(['message' => 'Demanda criada com sucesso!'], 201);
    } else {
        send_json_response(['error' => 'Método não permitido.'], 405);
    }

} catch (PDOException $e) {
    $errorMessage = 'Erro de banco de dados ao criar demanda: ' . $e->getMessage();
    log_error($errorMessage);
    send_json_response(['error' => 'Erro interno do servidor ao criar demanda.'], 500);
} catch (Throwable $e) {
    $errorMessage = 'Erro inesperado ao criar demanda: ' . $e->getMessage();
    log_error($errorMessage);
    send_json_response(['error' => 'Erro interno do servidor.'], 500);
}
?>