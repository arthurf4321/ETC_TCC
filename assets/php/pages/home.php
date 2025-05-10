<?php
session_start();

if (!isset($_SESSION['funcionario_id'])) {
    header("Location: login.html");
    exit;
}

$funcionarioCargo = $_SESSION['funcionario_cargo'] ?? null;

$cargosPermitidos = [
    'gerente' => [
        'página' => '../pages/Gerente/pagGerente.php',
        'permissao' => 'total',
        'descricao' => 'Gerenciamento completo do sistema',
        'icone' => 'fas fa-user-shield'
    ],
    'preparador' => [
        'página' => '../pages/Preparador/pagPreparador.php',
        'permissao' => 'preparo',
        'descricao' => 'Gerencia pedidos em andamento',
        'icone' => 'fas fa-utensils'
    ],
    'caixa' => [
        'página' => '../pages/Caixa/pagCaixa.php',
        'permissao' => 'vendas',
        'descricao' => 'Gerencia pagamentos e pedidos',
        'icone' => 'fas fa-cash-register'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include '../../includes/head.php'; ?>
    <link rel="stylesheet" href="../../CSS/home.css?v=1.1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" href="../../../assets/imgs/favicon.ico" sizes="16x16 32x32 48x48" type="image/x-icon">
    <title>Dashboard - <?= ucfirst($funcionarioCargo) ?></title>
</head>
<body class="hold-transition sidebar-mini">

    <div class="content-wrapper full-center">
        <div class="welcome-container">
            <div class="welcome-header">
                <h1><i class="fas fa-hand-sparkles"></i> Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</h1>
                <p class="role-badge"><?= ucfirst($funcionarioCargo) ?></p>
            </div>

            <div class="dashboard-card">
                <div class="card-body">
                    <div class="access-buttons">
                        <?php if ($funcionarioCargo && isset($cargosPermitidos[$funcionarioCargo])): ?>
                            <a href="<?= $cargosPermitidos[$funcionarioCargo]['página'] ?>" class="btn-main-access">
                                <i class="<?= $cargosPermitidos[$funcionarioCargo]['icone'] ?>"></i>
                                <span>Acessar Área do <?= ucfirst($funcionarioCargo) ?></span>
                                <small><?= $cargosPermitidos[$funcionarioCargo]['descricao'] ?></small>
                            </a>
                        <?php endif; ?>

                        <?php if ($funcionarioCargo === 'gerente'): ?>
                            <div class="secondary-actions">
                                <?php foreach ($cargosPermitidos as $cargo => $dados): ?>
                                    <?php if ($cargo !== 'gerente'): ?>
                                        <a href="<?= $dados['página'] ?>" class="btn-secondary-access">
                                            <i class="<?= $dados['icone'] ?>"></i>
                                            <?= ucfirst($cargo) ?>
                                        </a>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="logout-section">
                        <a href="../auth/logout.php" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Sair do Sistema
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>