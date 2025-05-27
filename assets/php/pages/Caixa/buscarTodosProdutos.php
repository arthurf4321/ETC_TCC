<?php
include('../../config/db.php');

$sql = "SELECT id, nome, descricao, preco, foto FROM produtos";
$stmt = $pdo->prepare($sql);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $foto = !empty($row['foto']) ? $row['foto'] : 'default.png';
        echo '
        <div class="col-md-4 mb-3">
            <div class="card produto-card" onclick="selecionarProduto('.$row['id'].', \''.htmlspecialchars($row['nome'], ENT_QUOTES).'\', '.$row['preco'].', \''.$foto.'\')">
                <img src="../../../uploads/produtos/'.$foto.'" class="card-img-top" alt="'.htmlspecialchars($row['nome']).'" style="height: 150px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title">'.htmlspecialchars($row['nome']).'</h5>
                    <p class="card-text">'.htmlspecialchars($row['descricao']).'</p>
                    <p class="card-text"><strong>R$ '.number_format($row['preco'], 2, ',', '.').'</strong></p>
                </div>
            </div>
        </div>';
    }
} else {
    echo '<div class="col-12"><p>Nenhum produto cadastrado.</p></div>';
}
?>