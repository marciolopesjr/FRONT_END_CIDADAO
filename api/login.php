<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

session_start();

try {
    $db = connect_db();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cpf = validate_input($_POST['cpf'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($cpf) || empty($password)) {
            send_json_response(['error' => 'CPF e senha são obrigatórios.'], 400);
        }

        $stmt = $db->prepare("SELECT id, password FROM users WHERE cpf = :cpf");
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR); // Adicionado PDO::PARAM_STR
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            send_json_response(['message' => 'Login realizado com sucesso!', 'user_id' => $user['id']], 200);
        } else {
            log_error("Falha de login para o CPF: " . $cpf);
            send_json_response(['error' => 'Credenciais inválidas.'], 401);
        }
    } else {
        send_json_response(['error' => 'Método não permitido.'], 405);
    }

} catch (PDOException $e) {
    $errorMessage = 'Erro de banco de dados ao fazer login: ' . $e->getMessage();
    log_error($errorMessage);
    send_json_response(['error' => 'Erro interno do servidor ao fazer login.'], 500);
} catch (Throwable $e) {
    $errorMessage = 'Erro inesperado ao fazer login: ' . $e->getMessage();
    log_error($errorMessage);
    send_json_response(['error' => 'Erro interno do servidor.'], 500);
}
?>