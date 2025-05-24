<?php
include('../../config/db.php');

if (!isset($_GET['id'])) {
    echo '<p class="text-danger">Pedido inválido.</p>';
    exit;
}

$id = $_GET['id'];

$sql = "SELECT p.*, c.nome AS cliente_nome, c.telefone
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    echo '<p class="text-danger">Pedido não encontrado.</p>';
    exit;
}

$sqlItens = "SELECT ip.*, pr.nome AS produto_nome, pr.categoria
             FROM itens_pedido ip
             JOIN produtos pr ON ip.produto_id = pr.id
             WHERE ip.pedido_id = ?";
$stmtItens = $pdo->prepare($sqlItens);
$stmtItens->execute([$id]);
$itens = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .pedido-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #333;
        max-width: 600px;
        margin: 0 auto;
    }
    .pedido-header {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px 5px 0 0;
        border-bottom: 1px solid #dee2e6;
    }
    .pedido-body {
        padding: 20px;
        background-color: white;
    }
    .pedido-title {
        color: #343a40;
        margin-bottom: 20px;
    }
    .pedido-info {
        margin-bottom: 15px;
    }
    .pedido-info strong {
        color: #495057;
        width: 100px;
        display: inline-block;
    }
    .itens-list {
        list-style-type: none;
        padding: 0;
        margin-top: 20px;
    }
    .item {
        padding: 10px 15px;
        margin-bottom: 8px;
        background-color: #f8f9fa;
        border-left: 4px solid #6c757d;
        border-radius: 3px;
    }
    .divider {
        height: 1px;
        background-color: #e9ecef;
        margin: 20px 0;
    }
    .status-badge {
        display: inline-block;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 0.85em;
        font-weight: 600;
    }
    .status-pendente { background-color: #fff3cd; color: #856404; }
    .status-processando { background-color: #cce5ff; color: #004085; }
    .status-concluido { background-color: #d4edda; color: #155724; }
    .status-cancelado { background-color: #f8d7da; color: #721c24; }
</style>

<div class="pedido-container">
    <div class="pedido-header">
        <h4 class="pedido-title">Detalhes do Pedido #<?= htmlspecialchars($id); ?></h4>
    </div>
    
    <div class="pedido-body">
        <div class="pedido-info">
            <strong>Cliente:</strong> <?= htmlspecialchars($pedido['cliente_nome']); ?>
        </div>
        <div class="pedido-info">
            <strong>Status:</strong> 
            <span class="status-badge status-<?= strtolower(htmlspecialchars($pedido['status'])); ?>">
                <?= ucfirst(htmlspecialchars($pedido['status'])); ?>
            </span>
        </div>
        <div class="pedido-info">
            <strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
        </div>
        
        <div class="divider"></div>
        
        <h5><strong>Itens do Pedido</strong></h5>
        <ul class="itens-list">
            <?php foreach ($itens as $item): ?>
                <li class="item">
                    <?= htmlspecialchars($item['produto_nome']); ?> 
                    <span class="float-right"><?= $item['quantidade']; ?>x</span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>