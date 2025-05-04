<?php
include('../../config/db.php');

if (isset($_GET['query'])) {
    $query = trim($_GET['query']);

    $sql = "SELECT id, nome FROM produtos WHERE nome LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$query%"]);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<a href="javascript:void(0)" class="list-group-item list-group-item-action" onclick="selecionarProduto(' . $row['id'] . ', \'' . htmlspecialchars($row['nome'], ENT_QUOTES) . '\', this)">' . htmlspecialchars($row['nome']) . '</a>';
        }
    } else {
        echo '<div class="list-group-item">Nenhum produto encontrado</div>';
    }
}
?>