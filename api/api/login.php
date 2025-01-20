<?php
require_once '/config/database.php';
require_once '/helpers/functions.php';

session_start(); // Inicia a sessão para o login

$db = connect_db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = validate_input($_POST['cpf'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($cpf) || empty($password)) {
        send_json_response(['error' => 'CPF e senha são obrigatórios.'], 400);
    }

    try {
        $stmt = $db->prepare("SELECT id, password FROM users WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Login bem-sucedido
            $_SESSION['user_id'] = $user['id']; // Define a sessão
            send_json_response(['message' => 'Login realizado com sucesso!', 'user_id' => $user['id']], 200);
        } else {
            send_json_response(['error' => 'Credenciais inválidas.'], 401);
        }
    } catch (PDOException $e) {
        send_json_response(['error' => 'Erro ao fazer login: ' . $e->getMessage()], 500);
    }
} else {
    send_json_response(['error' => 'Método não permitido.'], 405);
}
?>