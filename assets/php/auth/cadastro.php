<?php
include('../config/db.php');  // Inclui a configuração do banco de dados

// Verifica se a requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe e limpa os dados
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);
    $cargo = trim($_POST['cargo']);

    // Verifica se há campos vazios
    if (empty($nome) || empty($email) || empty($senha) || empty($cargo)) {
        die("Preencha todos os campos.");
    }

    // Verifica se o e-mail já existe
    $sql_check_email = "SELECT * FROM funcionarios WHERE email = :email";
    $stmt_check_email = $pdo->prepare($sql_check_email);
    $stmt_check_email->bindParam(':email', $email);
    $stmt_check_email->execute();

    if ($stmt_check_email->rowCount() > 0) {
        die("ERRO: Esse email já está cadastrado.");
    }

    // Verifica se o nome já existe
    $sql_check_nome = "SELECT COUNT(*) FROM funcionarios WHERE nome = :nome";
    $stmt_check_nome = $pdo->prepare($sql_check_nome);
    $stmt_check_nome->bindParam(':nome', $nome);
    $stmt_check_nome->execute();

    if ($stmt_check_nome->fetchColumn() > 0) {
        die("Nome de funcionario já está em uso. Tente outro nome.");
    }

    // Criptografa a senha
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    // Prepara a query de inserção
    $sql = "INSERT INTO funcionarios (nome, email, senha, cargo) VALUES (:nome, :email, :senha, :cargo)";
    $stmt = $pdo->prepare($sql);

    // Vincula os parâmetros
    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);
    $stmt->bindParam(':cargo', $cargo);

    // Executa a inserção e verifica se deu certo
    if ($stmt->execute()) {
    
        session_start();
        $_SESSION['funcionario_id'] = $pdo->lastInsertId(); 
        $_SESSION['user_id'] = $_SESSION['usuario_id']; // Ajuste para corresponder ao home.php
        $_SESSION['funcionario_cargo'] = $cargo; // Define o cargo para a home
        $_SESSION['nome'] = $nome; // Salva o nome para exibiçã
        
        // Redirecionamento correto
        header("Location: ../pages/home.php");
        exit();
    } else {
        // Exibe erro do banco de dados
        print_r($stmt->errorInfo());
        exit("Erro ao cadastrar funcionarios.");
    }
}
?>
