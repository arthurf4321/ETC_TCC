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
        JOIN clientes c ON p.cliente_id = c.id
        WHERE p.status IN ('preparando', 'pronto', 'recebido')";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<div class="card acai-theme">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title"><i class="fas fa-clipboard-list mr-2"></i>Pedidos do Sistema</h3>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped m-0">
                <thead style="background-color: rgba(123, 31, 162, 0.9); color: white;">
                    <tr>
                        <th class="text-center">ID</th>
                        <th>Cliente</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Total</th>
                        <th class="text-center">Data do Pedido</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pedidos as $pedido): 
                        $statusClass = '';
                        if ($pedido['status'] == 'preparando') {
                            $statusClass = 'badge-warning';
                        } elseif ($pedido['status'] == 'pronto') {
                            $statusClass = 'badge-success';
                        } else {
                            $statusClass = 'badge-secondary';
                        }
                    ?>
                        <tr>
                            <td class="text-center align-middle"><?= htmlspecialchars($pedido['id']); ?></td>
                            <td class="align-middle">
                                <div class="font-weight-bold"><?= htmlspecialchars($pedido['cliente_nome']); ?></div>
                            </td>
                            <td class="text-center align-middle">
                                <span class="badge <?= $statusClass ?> p-2" style="min-width: 90px;">
                                    <?= ucfirst(htmlspecialchars($pedido['status'])); ?>
                                </span>
                            </td>
                            <td class="text-right align-middle">
                                <span class="font-weight-bold">R$ <?= number_format($pedido['total'], 2, ',', '.'); ?></span>
                            </td>
                            <td class="text-center align-middle">
                                <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])); ?>
                            </td>
                            <td class="text-center align-middle">
                                <div class="btn-group" role="group">
                                    <button onclick="verDetalhesPedido(<?= $pedido['id']; ?>)" 
                                        class="btn btn-sm btn-info"
                                        style="border-radius: 4px 0 0 4px;"
                                        data-toggle="tooltip" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <button onclick="atualizarStatus(<?= $pedido['id']; ?>, 'preparando')" 
                                        class="btn btn-sm btn-status"
                                        style="background-color: #7b1fa2; color: white; border-radius: 0;"
                                        data-toggle="tooltip" title="Marcar como Preparando">
                                        <i class="fas fa-blender"></i>
                                    </button>
                                    
                                    <button onclick="atualizarStatus(<?= $pedido['id']; ?>, 'pronto')" 
                                        class="btn btn-sm btn-status"
                                        style="background-color: #66BB6A; color: white; border-radius: 0 4px 4px 0;"
                                        data-toggle="tooltip" title="Marcar como Pronto">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div class="modal fade" id="modalDetalhesPedido" tabindex="-1" aria-labelledby="modalDetalhesLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #7b1fa2; color: white;">
        <h5 class="modal-title" id="modalDetalhesLabel">Detalhes do Pedido</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="detalhesPedidoBody">
        <p class="text-center">Carregando...</p>
      </div>
    </div>
  </div>
</div>

<script>
function atualizarStatus(id, status) {
    const action = status === 'preparando' ? 'Marcar como Preparando' : 'Marcar como Pronto';
    
    Swal.fire({
        title: 'Confirmar',
        text: `${action} o pedido #${id}?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7b1fa2',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.get(`pedidos.php?${status}=${id}`, function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Status atualizado!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    carregarPagina('pedidos.php');
                });
            }).fail(function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Não foi possível atualizar o status'
                });
            });
        }
    });
}

function verDetalhesPedido(id) {
    $('#detalhesPedidoBody').html('<p class="text-center">Carregando...</p>');
    $('#modalDetalhesPedido').modal('show');

    $.get(`/TCC/assets/php/pages/preparador/detalhesPedido.php?id=${id}`, function(data) {
        $('#detalhesPedidoBody').html(data);
    }).fail(function() {
        $('#detalhesPedidoBody').html('<p class="text-danger">Erro ao carregar os detalhes do pedido.</p>');
    });
}

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

<style>
.card.acai-theme {
    border: none;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(123, 31, 162, 0.15);
}
.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.8rem;
    letter-spacing: 0.5px;
}
.table td {
    vertical-align: middle;
    border-top: 1px solid rgba(0,0,0,0.03);
}
.btn-status {
    transition: all 0.3s ease;
    padding: 0.35rem 0.75rem;
}
.btn-status:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.badge {
    font-size: 0.8rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
</style>
