<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/functions.php';

try {
    $db = connect_db();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = validate_input($_POST['name'] ?? '');
        $email = validate_input($_POST['email'] ?? '');
        $cpf = validate_input($_POST['cpf'] ?? '');
        $phone = validate_input($_POST['phone'] ?? '');
        $address = validate_input($_POST['address'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($name) || empty($email) || empty($cpf) || empty($password)) {
            send_json_response(['error' => 'Por favor, preencha todos os campos obrigatórios.'], 400);
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $db->prepare("INSERT INTO users (name, email, cpf, phone, address, password) VALUES (:name, :email, :cpf, :phone, :address, :password)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmt->bindParam(':address', $address, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->execute();

        send_json_response(['message' => 'Registro realizado com sucesso!'], 201);
    } else {
        send_json_response(['error' => 'Método não permitido.'], 405);
    }

} catch (PDOException $e) {
    if ($e->getCode() == '23000' && strpos($e->getMessage(), 'Duplicate entry') !== false) {
        if (strpos($e->getMessage(), 'users.email_UNIQUE') !== false) {
            send_json_response(['error' => 'Este email já está cadastrado.'], 400);
        } elseif (strpos($e->getMessage(), 'users.cpf_UNIQUE') !== false) {
            send_json_response(['error' => 'Este CPF já está cadastrado.'], 400);
        } else {
            $errorMessage = 'Erro de constraint ao registrar usuário: ' . $e->getMessage();
            log_error($errorMessage);
            send_json_response(['error' => 'Erro interno do servidor ao registrar usuário.'], 500);
        }
    } else {
        $errorMessage = 'Erro de banco de dados ao registrar usuário: ' . $e->getMessage();
        log_error($errorMessage);
        send_json_response(['error' => 'Erro interno do servidor ao registrar usuário.'], 500);
    }
} catch (Throwable $e) {
    $errorMessage = 'Erro inesperado ao registrar usuário: ' . $e->getMessage();
    log_error($errorMessage);
    send_json_response(['error' => 'Erro interno do servidor.'], 500);
}
?>