<?php
header('Content-Type: application/json');  // Define o tipo de resposta como JSON
session_start();  // Inicia a sessão para o login

// Conexão com o banco de dados
include('../config/db.php');

// Verifique se os dados foram enviados via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados do formulário (email e senha)
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Prepara a consulta para buscar o Funcionario no banco de dados
    $sql = "SELECT * FROM funcionarios WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    // Verifique se o Funcionario foi encontrado
    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica se a senha está correta
        if (password_verify($senha, $user['senha'])) {
            // Salva os dados do Funcionario na sessão
            $_SESSION['id'] = $user['id']; 
            $_SESSION['funcionario_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['funcionario_cargo'] = $user['cargo'];
            $_SESSION['foto'] = $user['foto'];       // Adiciona o cargo do Funcionario na sessão

            // Envie uma resposta JSON de sucesso
            echo json_encode(['status' => 'success', 'message' => 'Login bem-sucedido']);
            
            // Atualiza o último acesso da conta
            $sql = "UPDATE funcionarios SET ultimo_acesso = NOW() WHERE email = :email";
            $stmt = $pdo->prepare($sql);  // Use o mesmo objeto PDO para a atualização
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

        } else {
            // Envie uma resposta JSON de erro
            echo json_encode(['status' => 'error', 'message' => 'Email ou senha inválidos']);
        }
    } else {
        // Envie uma resposta JSON de erro se o Funcionario não for encontrado
        echo json_encode(['status' => 'error', 'message' => 'Funcionario não encontrado']);
        die();
    }
} else {
    // Se o método não for POST, envie uma resposta de erro
    echo json_encode(['status' => 'error', 'message' => 'Método inválido']);
}

exit;
?>
