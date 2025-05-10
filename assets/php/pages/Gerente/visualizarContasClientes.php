<?php
include('../../config/db.php');

// Verificar conexão
if (!$pdo) {
    die("Erro na conexão com o banco de dados");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nome, $email, $telefone, $id])) {
        echo json_encode(['status' => 'success', 'message' => 'Cliente atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar cliente: ' . implode(", ", $stmt->errorInfo())]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    
    // Adicione headers JSON antes de qualquer output
    header('Content-Type: application/json');
    
    if ($stmt->execute([$id])) {
        echo json_encode([
            'status' => 'success', 
            'message' => 'Cliente excluído com sucesso!',
            'id' => $id // Adicione o ID para referência
        ]);
    } else {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Erro ao excluir cliente: ' . implode(", ", $stmt->errorInfo())
        ]);
    }
    exit;
}

// Consulta para listar clientes
$sql = "SELECT id, nome, email, telefone FROM clientes ORDER BY nome";
$stmt = $pdo->prepare($sql);

if (!$stmt->execute()) {
    die("Erro ao executar a consulta: " . implode(", ", $stmt->errorInfo()));
}

$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0" style="color: #7b1fa2;">
        <i class="fas fa-users mr-2"></i>Clientes Cadastrados
    </h3>
</div>

<div class="search-box">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control search-input" placeholder="Pesquisar clientes...">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="" style="background-color: #7b1fa2">
            <tr>
                <th width="70" style="color: white;">ID</th>
                <th style="color: white;">Nome</th>
                <th style="color: white;">Email</th>
                <th width="150" style="color: white;">Telefone</th>
                <th width="130" class="text-center" style="color: white;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clientes)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                        Nenhum cliente cadastrado
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= htmlspecialchars($cliente['id']); ?></td>
                        <td><?= htmlspecialchars($cliente['nome']); ?></td>
                        <td><?= htmlspecialchars($cliente['email']); ?></td>
                        <td><?= htmlspecialchars($cliente['telefone']); ?></td>
                        <td class="table-actions text-center">
                            <button class="btn btn-sm btn-editar" 
                                style="background-color: #388E3C; color: white;"
                                data-id="<?= $cliente['id']; ?>"
                                data-nome="<?= htmlspecialchars($cliente['nome']); ?>"
                                data-email="<?= htmlspecialchars($cliente['email']); ?>"
                                data-telefone="<?= htmlspecialchars($cliente['telefone']); ?>"
                                data-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <a href="visualizarContasClientes.php?excluir=<?= $cliente['id']; ?>"
                                <button class="btn btn-sm btn-excluir"
                                    style="background-color: #E53935; color: white;"
                                    data-id="<?= $cliente['id']; ?>"
                                    data-toggle="tooltip" title="Excluir">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="editarClienteModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="editarClienteModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editarClienteModalLabel"><i class="fas fa-user-edit mr-2"></i>Editar Cliente</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarCliente">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    
                    <div class="form-group">
                        <label for="editNome"><i class="fas fa-user mr-1"></i> Nome Completo</label>
                        <input type="text" name="nome" id="editNome" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editEmail"><i class="fas fa-envelope mr-1"></i> Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editTelefone"><i class="fas fa-phone mr-1"></i> Telefone</label>
                        <input type="text" name="telefone" id="editTelefone" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" name="editar" class="btn btn-success">
                        <i class="fas fa-save mr-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>