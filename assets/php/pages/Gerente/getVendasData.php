<?php
include('../../config/db.php');

header('Content-Type: application/json');

$data = [];
$labels = [];
$vendas = [];
$receita = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = date('d/m', strtotime($date));
    
    $stmt = $pdo->prepare("SELECT COUNT(*) as total_vendas, COALESCE(SUM(total), 0) as total_receita 
                          FROM pedidos WHERE DATE(data_pedido) = ?");
    $stmt->execute([$date]);
    $result = $stmt->fetch();
    
    $vendas[] = $result['total_vendas'];
    $receita[] = (float)$result['total_receita'];
}

$data = [
    'labels' => $labels,
    'vendas' => $vendas,
    'receita' => $receita
];

echo json_encode($data);
?>