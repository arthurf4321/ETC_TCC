<?php
include('../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['preparando'])) {
    $id = $_GET['preparando'];
    $sql = "UPDATE pedidos SET status = 'preparando' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pronto'])) {
    $id = $_GET['pronto'];
    $sql = "UPDATE pedidos SET status = 'pronto' WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    exit;
}

$sql = "SELECT p.id, p.cliente_id, p.status, p.total, p.data_pedido, c.nome AS cliente_nome 
        FROM pedidos p
        JOIN clientes c ON p.cliente_id = c.id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Pedidos do Sistema</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead style="background-color:#6A1B9A;">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Status</th>
                    <th>Total</th>
                    <th>Data do Pedido</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id']); ?></td>
                        <td><?= htmlspecialchars($pedido['cliente_nome']); ?></td>
                        <td><?= htmlspecialchars($pedido['status']); ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></td>
                        <td><?= htmlspecialchars($pedido['data_pedido']); ?></td>
                        <td>
                            <a href="pagGerente.php?preparando=<?= $pedido['id']; ?>"
                               class="btn btn-sm"
                               style="background-color: #7b1fa2; color: white;"
                               onclick="return confirm('Marcar como Preparando?');">
                                Preparando
                            </a>

                            <a href="pagGerente.php?pronto=<?= $pedido['id']; ?>"
                               class="btn btn-sm"
                               style="background-color: #66BB6A; color: white;"
                               onclick="return confirm('Marcar como Pronto?');">
                                Pronto
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
