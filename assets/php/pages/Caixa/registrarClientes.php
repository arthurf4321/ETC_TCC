<?php
include('../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registro_cliente'])) {
    $nome = $_POST['nome_cliente'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $aceite_lgpd = isset($_POST['aceite_lgpd']) ? 1 : 0;
    $data_aceite_lgpd = $aceite_lgpd ? date('Y-m-d H:i:s') : null;

    $sql = "INSERT INTO clientes (nome, telefone, email, aceite_lgpd, data_aceite_lgpd) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $telefone, $email, $aceite_lgpd, $data_aceite_lgpd]);

    header("Location: pagCaixa.php");
    exit;
}
?>