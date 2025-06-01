<?php
include('../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedidoId = intval($_POST['pedido_id']);

    $stmt = $pdo->prepare("UPDATE pedidos SET status = 'Finalizado' WHERE id = ?");
    $success = $stmt->execute([$pedidoId]);

    echo $success ? 'sucesso' : 'erro';
    exit;
}

echo 'erro';
