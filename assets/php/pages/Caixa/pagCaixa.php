<?php
include('../../auth/verificarPermissão.php');
include('../../config/db.php');
verificarAcesso(['caixa', 'gerente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<?php include '../../../includes/head.php'; ?>
<head>
    <link rel="icon" href="../../../../assets/imgs/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../../CSS/styles.css?v=2">

    <style>
        .card.acai-theme {
            background-color: rgb(234, 217, 238);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(106, 27, 154, 0.2);
        }

        .btn-fazer-pedido, .btn-registrar-cliente {
            background-color: #7b1fa2;
            font-weight: bold;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <?php include('../../../../assets/includes/navbar.php'); ?>
    <?php include('../../../../assets/includes/sidebar.php'); ?>

    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2"></div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card acai-theme">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <button class="btn btn-block text-white btn-fazer-pedido" onclick="carregarPagina('formPedidos.php')">
                                    <i class="fas fa-cash-register"></i> Fazer Pedido
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-block text-white btn-registrar-cliente" onclick="carregarPagina('formClientes.php')">
                                    <i class="fas fa-history"></i> Registrar Cliente
                                </button>
                            </div>
                        </div>

                        <div id="conteudoDinamico" class="p-3 bg-white rounded shadow-sm">
                            <p class="text-muted">Selecione uma das opções acima para visualizar os conteúdos.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="../../../JS/funções.js"></script>
</body>
</html>
