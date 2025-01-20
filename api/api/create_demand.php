<?php
require_once '../config/database.php';
require_once '../helpers/functions.php';

session_start();

$db = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar se o usuário está logado (MVP simples)
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

    try {
        $stmt = $db->prepare("INSERT INTO demands (user_id, category, description, latitude, longitude) VALUES (:user_id, :category, :description, :latitude, :longitude)");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':latitude', $latitude);
        $stmt->bindParam(':longitude', $longitude);
        $stmt->execute();

        send_json_response(['message' => 'Demanda criada com sucesso!'], 201);
    } catch (PDOException $e) {
        send_json_response(['error' => 'Erro ao criar demanda: ' . $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => 'Método não permitido.'], 405);
}
?>