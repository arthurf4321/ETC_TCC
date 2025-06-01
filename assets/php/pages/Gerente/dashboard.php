<?php
include('../../auth/verificarPermissão.php');
include('../../config/db.php');
verificarAcesso(['gerente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PéDeAçai</title>
    <link rel="icon" href="../../../../assets/imgs/favicon.ico" type="image/x-icon">
    <?php include '../../../includes/head.php'; ?>
    <link rel="stylesheet" href="../../../CSS/styles.css?v=3">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary-color: #7b1fa2;
            --secondary-color: #388E3C;
            --danger-color: #E53935;
            --accent-color: #FFD700;
            --info-color: #1976D2;
        }
        
        .dashboard-card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            margin-bottom: 20px;
            color: white;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.15);
        }
        
        .card-vendas {
            background: linear-gradient(135deg, var(--primary-color), #9C27B0);
        }
        
        .card-clientes {
            background: linear-gradient(135deg, var(--info-color), #2196F3);
        }
        
        .card-produtos {
            background: linear-gradient(135deg, var(--secondary-color), #4CAF50);
        }
        
        .card-receita {
            background: linear-gradient(135deg, var(--accent-color), #FFC107);
            color: #333 !important;
        }
        
        .card-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .card-value {
            font-size: 2rem;
            font-weight: bold;
        }
        
        .card-title {
            font-size: 1rem;
            opacity: 0.9;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 30px;
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .recent-orders {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .order-badge {
            font-size: 0.75rem;
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: bold;
        }
        
        .badge-recebido {
            background-color: #BBDEFB;
            color: #0D47A1;
        }
        
        .badge-preparando {
            background-color: #FFE0B2;
            color: #E65100;
        }
        
        .badge-pronto {
            background-color: #C8E6C9;
            color: #1B5E20;
        }
        
        .badge-finalizado {
            background-color: #E0E0E0;
            color: #424242;
        }
        
        .top-products {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .product-img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        @media (max-width: 768px) {
            .card-value {
                font-size: 1.5rem;
            }
            
            .card-icon {
                font-size: 2rem;
            }
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
                <div class="row mb-2">
                               
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="dashboard-card card-vendas p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="card-title">Vendas Hoje</div>
                                    <div class="card-value">
                                        <?php
                                        $hoje = date('Y-m-d');
                                        $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM pedidos WHERE DATE(data_pedido) = ?");
                                        $stmt->execute([$hoje]);
                                        $result = $stmt->fetch();
                                        echo $result['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="card-icon align-self-center">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="dashboard-card card-clientes p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="card-title">Clientes Cadastrados</div>
                                    <div class="card-value">
                                        <?php
                                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM clientes");
                                        $result = $stmt->fetch();
                                        echo $result['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="card-icon align-self-center">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="dashboard-card card-produtos p-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <div class="card-title">Produtos</div>
                                    <div class="card-value">
                                        <?php
                                        $stmt = $pdo->query("SELECT COUNT(*) as total FROM produtos");
                                        $result = $stmt->fetch();
                                        echo $result['total'];
                                        ?>
                                    </div>
                                </div>
                                <div class="card-icon align-self-center">
                                    <i class="fas fa-box-open"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gráficos e Tabelas -->
                <div class="row mt-4">
                    <div class="col-lg-8">
                        <div class="chart-container">
                            <h5>Vendas nos últimos 7 dias</h5>
                            <canvas id="vendasChart"></canvas>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="chart-container">
                            <h5>Métodos de Pagamento</h5>
                            <canvas id="pagamentosChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-2">
                    <div class="col-lg-6">
                        <div class="recent-orders">
                            <h5>Pedidos Recentes</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th>Data</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->query("
                                            SELECT p.id, c.nome as cliente, p.total, p.status, p.data_pedido 
                                            FROM pedidos p
                                            JOIN clientes c ON p.cliente_id = c.id
                                            ORDER BY p.data_pedido DESC
                                            LIMIT 5
                                        ");
                                        
                                        while ($row = $stmt->fetch()):
                                            $statusClass = '';
                                            switch ($row['status']) {
                                                case 'recebido': $statusClass = 'badge-recebido'; break;
                                                case 'preparando': $statusClass = 'badge-preparando'; break;
                                                case 'pronto': $statusClass = 'badge-pronto'; break;
                                                case 'finalizado': $statusClass = 'badge-finalizado'; break;
                                            }
                                        ?>
                                        <tr>
                                            <td>#<?= $row['id'] ?></td>
                                            <td><?= substr($row['cliente'], 0, 15) ?></td>
                                            <td>R$ <?= number_format($row['total'], 2, ',', '.') ?></td>
                                            <td><span class="order-badge <?= $statusClass ?>"><?= ucfirst($row['status']) ?></span></td>
                                            <td><?= date('d/m H:i', strtotime($row['data_pedido'])) ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="top-products">
                            <h5>Produtos Mais Vendidos</h5>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produto</th>
                                            <th>Vendas</th>
                                            <th>Receita</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $stmt = $pdo->query("
                                            SELECT p.nome, COUNT(i.id) as vendas, SUM(i.subtotal) as receita
                                            FROM itens_pedido i
                                            JOIN produtos p ON i.produto_id = p.id
                                            GROUP BY p.id
                                            ORDER BY vendas DESC
                                            LIMIT 5
                                        ");
                                        
                                        while ($row = $stmt->fetch()):
                                        ?>
                                        <tr>
                                            <td>
                                                <?= $row['nome'] ?>
                                            </td>
                                            <td><?= $row['vendas'] ?></td>
                                            <td>R$ <?= number_format($row['receita'], 2, ',', '.') ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</div>

<?php include '../../../includes/footer.php'; ?>

<script>
$(document).ready(function() {
    const vendasCtx = document.getElementById('vendasChart').getContext('2d');
 
    $.ajax({
        url: 'getVendasData.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const vendasChart = new Chart(vendasCtx, {
                type: 'bar',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Vendas',
                        data: data.vendas,
                        backgroundColor: 'rgba(123, 31, 162, 0.7)',
                        borderColor: 'rgba(123, 31, 162, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Receita (R$)',
                        data: data.receita,
                        backgroundColor: 'rgba(255, 215, 0, 0.7)',
                        borderColor: 'rgba(255, 215, 0, 1)',
                        borderWidth: 1,
                        type: 'line',
                        yAxisID: 'y1'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Número de Vendas'
                            }
                        },
                        y1: {
                            position: 'right',
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Receita (R$)'
                            },
                            grid: {
                                drawOnChartArea: false
                            }
                        }
                    }
                }
            });
        }
    });

    const pagamentosCtx = document.getElementById('pagamentosChart').getContext('2d');
    
    $.ajax({
        url: 'getPagamentosData.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            const pagamentosChart = new Chart(pagamentosCtx, {
                type: 'doughnut',
                data: {
                    labels: data.labels,
                    datasets: [{
                        data: data.valores,
                        backgroundColor: [
                            'rgba(56, 142, 60, 0.7)',
                            'rgba(25, 118, 210, 0.7)',
                            'rgba(123, 31, 162, 0.7)'
                        ],
                        borderColor: [
                            'rgba(56, 142, 60, 1)',
                            'rgba(25, 118, 210, 1)',
                            'rgba(123, 31, 162, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>