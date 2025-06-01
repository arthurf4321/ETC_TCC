<?php
include('../../config/db.php'); // Aqui o arquivo que define $pdo

if (!isset($pdo)) {
    echo '<div class="alert alert-danger">Erro: conexão PDO não encontrada.</div>';
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT 
            p.id, 
            p.cliente_id, 
            c.nome AS cliente_nome, 
            p.status, 
            p.total,
            pg.metodo AS forma_pagamento
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id
        LEFT JOIN pagamentos pg ON p.id = pg.pedido_id
        WHERE p.status = 'Pronto'
        ORDER BY p.id DESC
    ");
    $stmt->execute();
    $pedidos = $stmt->fetchAll();

    if (empty($pedidos)) {
        echo '<div class="alert alert-cinza-claro">Nenhum pedido pronto no momento.</div>';
        exit;
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Erro na consulta: ' . htmlspecialchars($e->getMessage()) . '</div>';
    exit;
}
?>

<style>
    .alert-cinza-claro {
        background-color:rgb(200, 199, 199);
        color: #555;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
    }
    .table-pedidos-prontos {
        background-color: rgb(234, 217, 238);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(106, 27, 154, 0.2);
    }
    .table-pedidos-prontos thead th {
        background-color: #7b1fa2;
        color: white;
        border-color: #7b1fa2;
    }
    .table-pedidos-prontos tbody tr:hover {
        background-color: rgba(123, 31, 162, 0.1);
    }
    .btn-finalizar {
        background-color: #7b1fa2;
        color: white;
        font-weight: bold;
        border: none;
    }
    .btn-finalizar:hover {
        background-color: #6a1b9a;
        color: white;
    }
    .badge-pagamento {
        font-size: 0.85em;
        padding: 5px 8px;
        border-radius: 12px;
    }
    .badge-dinheiro {
        background-color: #28a745;
        color: white;
    }
    .badge-cartao {
        background-color: #17a2b8;
        color: white;
    }
    .badge-pix {
        background-color: #6f42c1;
        color: white;
    }
    .btn-danger {
    background-color: #dc3545;
    color: white;
    font-weight: bold;
    border: none;
    }
    .btn-danger:hover {
        background-color: #c82333;
        color: white;
    }
</style>

<div class="table-responsive table-pedidos-prontos">
    <table class="table table-bordered table-hover m-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Status</th>
                <th>Total</th>
                <th>Pagamento</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos as $pedido): ?>
                <tr>
                    <td><?= htmlspecialchars($pedido['id']) ?></td>
                    <td><?= htmlspecialchars($pedido['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($pedido['status']) ?></td>
                    <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                    <td>
                        <?php if (!empty($pedido['forma_pagamento'])): ?>
                            <?php 
                                $badgeClass = '';
                                switch($pedido['forma_pagamento']) {
                                    case 'dinheiro': $badgeClass = 'badge-dinheiro'; break;
                                    case 'cartão': $badgeClass = 'badge-cartao'; break;
                                    case 'pix': $badgeClass = 'badge-pix'; break;
                                }
                            ?>
                            <span class="badge badge-pagamento <?= $badgeClass ?>">
                                <?= ucfirst($pedido['forma_pagamento']) ?>
                            </span>
                        <?php else: ?>
                            <span class="text-muted">Não informado</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-finalizar btn-sm finalizar-pedido" data-id="<?= htmlspecialchars($pedido['id']) ?>">
                            <i class="fas fa-check-circle mr-1"></i> Finalizar
                        </button>
                        <button class="btn btn-danger btn-sm cancelar-pedido" data-id="<?= htmlspecialchars($pedido['id']) ?>">
                            <i class="fas fa-times-circle mr-1"></i> Cancelar
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>