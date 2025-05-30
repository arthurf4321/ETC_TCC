<?php
include('../../config/db.php');

header('Content-Type: application/json');

$data = [];
$labels = ['Dinheiro', 'Cartão', 'Pix'];
$valores = [0, 0, 0];

$stmt = $pdo->query("SELECT metodo, COUNT(*) as total FROM pagamentos GROUP BY metodo");
while ($row = $stmt->fetch()) {
    switch ($row['metodo']) {
        case 'dinheiro':
            $valores[0] = $row['total'];
            break;
        case 'cartão':
            $valores[1] = $row['total'];
            break;
        case 'pix':
            $valores[2] = $row['total'];
            break;
    }
}

$data = [
    'labels' => $labels,
    'valores' => $valores
];

echo json_encode($data);
?>