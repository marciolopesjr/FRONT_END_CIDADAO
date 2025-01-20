<?php
function connect_db() {
    $host = 'localhost'; // Ou o endereço do seu servidor MySQL
    $dbname = 'u271084294_cidadao';
    $user = 'u271084294_teste';
    $pass = 'E$JfNQUy9md^zr4';

    try {
        $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Erro ao conectar com o banco de dados: ' . $e->getMessage()]);
        exit;
    }
}
?>