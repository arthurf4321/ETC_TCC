<?php
header('Content-Type: application/json'); 
session_start();

include('../config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM funcionarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($senha, $user['senha'])) {

            $_SESSION['id'] = $user['id']; 
            $_SESSION['funcionario_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['funcionario_cargo'] = $user['cargo'];
            $_SESSION['foto'] = $user['foto'];  

            echo json_encode(['status' => 'success', 'message' => 'Login bem-sucedido']);
  
            $sql = "UPDATE funcionarios SET ultimo_acesso = NOW() WHERE email = :email";
            $stmt = $pdo->prepare($sql);  
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

        } else {
            echo json_encode(['status' => 'error', 'message' => 'Email ou senha inválidos']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Funcionario não encontrado']);
        die();
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
}

exit;
?>
