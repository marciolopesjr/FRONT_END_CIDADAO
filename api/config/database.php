<?php
function connect_db() {
    try {
        $db = new PDO('sqlite:' . __DIR__ . '/../database.sqlite');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao conectar com o banco de dados: ' . $e->getMessage()]);
        exit;
    }
}
?>