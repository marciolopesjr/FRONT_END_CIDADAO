<?php
require_once '../config/database.php';
require_once '../helpers/functions.php';

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

    try {
        $stmt = $db->prepare("INSERT INTO users (name, email, cpf, phone, address, password) VALUES (:name, :email, :cpf, :phone, :address, :password)");
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->execute();

        send_json_response(['message' => 'Registro realizado com sucesso!'], 201);
    } catch (PDOException $e) {
        if ($e->getCode() == '23000') { // Código de erro para violação de constraint (e.g., UNIQUE)
            if (strpos($e->getMessage(), 'users.email') !== false) {
                send_json_response(['error' => 'Este email já está cadastrado.'], 400);
            } elseif (strpos($e->getMessage(), 'users.cpf') !== false) {
                send_json_response(['error' => 'Este CPF já está cadastrado.'], 400);
            } else {
                send_json_response(['error' => 'Erro ao registrar usuário (violação de dados): ' . $e->getMessage()], 400);
            }
        } else {
            send_json_response(['error' => 'Erro ao registrar usuário: ' . $e->getMessage()], 500);
        }
    }
} else {
    send_json_response(['error' => 'Método não permitido.'], 405);
}
?>