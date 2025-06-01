<?php
header('Content-Type: application/json');
session_start();

include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $email = $data['email'];
    $novaSenha = $data['novaSenha'];

    // Verificar se o email existe
    $sql = "SELECT id FROM funcionarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // Atualizar a senha
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $sql = "UPDATE funcionarios SET senha = :senha WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':senha', $senhaHash, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Senha atualizada com sucesso']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar senha']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Email não encontrado']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
}
?>