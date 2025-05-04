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
        'descricao' => 'Gerenciamento completo do sistema.'
    ],
    'preparador' => [
        'página' => '../pages/Preparador/pagPreparador.php',
        'permissao' => 'preparo',
        'descricao' => 'Gerencia pedidos em andamento.'
    ],
    'caixa' => [
        'página' => '../pages/Caixa/pagCaixa.php',
        'permissao' => 'vendas',
        'descricao' => 'Gerencia pagamentos e pedidos.'
    ]
];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <?php include '../../includes/head.php'; ?>
    <link rel="stylesheet" href="../../CSS/home.css">
    <link rel="icon" href="../../../assets/imgs/favicon.ico"  sizes="16x16 32x32 48x48" type="image/x-icon">
    <title></title>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <div class="content-wrapper full-center">
        <div class="central-container">
            <div class="login-logo">
                <b>Bem-vindo, <?php echo htmlspecialchars($_SESSION['nome']); ?>!</b>
            </div>

            <div class="card">
                <div class="card-body login-card-body text-center">
                    <p class="login-box-msg">
                        Você está logado como <strong><?= ucfirst($funcionarioCargo) ?></strong>
                    </p>

                    <?php
                    if ($funcionarioCargo && isset($cargosPermitidos[$funcionarioCargo])) {
                        echo "<a href=\"{$cargosPermitidos[$funcionarioCargo]['página']}\" 
                                style=\"background-color: #6A1B9A; border: 1px solid #6A1B9A;\" 
                                class=\"btn btn-primary btn-block mb-2\">
                                Ir para área de " . ucfirst($funcionarioCargo) . "
                              </a>";
                    }

                    if ($funcionarioCargo === 'gerente') {
                        foreach ($cargosPermitidos as $cargo => $dados) {
                            if ($cargo !== 'gerente') {
                                echo "<a href=\"{$dados['página']}\" 
                                        class=\"btn btn-secondary btn-block mb-2\">
                                        Área do " . ucfirst($cargo) . "
                                      </a>";
                            }
                        }
                    }
                    ?>

                    <a href="../auth/logout.php" class="btn btn-danger btn-block">Sair</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
