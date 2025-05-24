<?php
session_start();
require_once '../config/conexao.php';

if (!isset($_SESSION['id']) || !isset($_SESSION['cargo'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Acesso não autorizado.']);
    exit;
}

if ($_SESSION['cargo'] !== 'preparador') {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Permissão negada.']);
    exit;
}

if (!isset($_POST['id_pedido']) || !isset($_POST['novo_status'])) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Dados incompletos.']);
    exit;
}

$id_pedido = intval($_POST['id_pedido']);
$novo_status = $_POST['novo_status'];

$sql = "UPDATE pedidos SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("si", $novo_status, $id_pedido);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'sucesso', 'mensagem' => 'Status atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Erro ao atualizar o status.']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Erro na preparação da query.']);
}

$conn->close();
?>