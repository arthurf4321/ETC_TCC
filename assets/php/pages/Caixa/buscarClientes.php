<?php
include('../../config/db.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $sql = "SELECT nome FROM clientes WHERE nome LIKE ? LIMIT 5";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$query%"]);
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($clientes as $cliente) {
        echo '<a href="#" class="list-group-item list-group-item-action" onclick="selecionarCliente(\''.htmlspecialchars($cliente['nome'], ENT_QUOTES).'\')">' . htmlspecialchars($cliente['nome']) . '</a>';
    }
}
?>
