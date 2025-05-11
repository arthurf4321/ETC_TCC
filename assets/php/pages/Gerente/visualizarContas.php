<?php
include('../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];

    $sql = "UPDATE funcionarios SET nome = ?, email = ?, cargo = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nome, $email, $cargo, $id])) {
        echo json_encode(['status' => 'success', 'message' => 'Funcionário atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar funcionário.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM funcionarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id])) {
        echo json_encode(['status' => 'success', 'message' => 'Funcionário excluído com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir funcionário.']);
    }
    exit;
}

$sql = "SELECT id, nome, email, cargo FROM funcionarios ORDER BY nome";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0" style="color: #7b1fa2;">
        <i class="fas fa-users-cog mr-2"></i>Funcionários Cadastrados
    </h3>
</div>

<div class="search-box">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control search-input" placeholder="Pesquisar funcionários...">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="" style="background-color: #7b1fa2">
            <tr>
                <th width="70" style="color: white;">ID</th>
                <th style="color: white;">Nome</th>
                <th style="color: white;">Email</th>
                <th width="150" style="color: white;">Cargo</th>
                <th width="130" class="text-center" style="color: white;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($funcionarios)): ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="fas fa-user-slash fa-2x mb-2"></i><br>
                        Nenhum funcionário cadastrado
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($funcionarios as $funcionario): ?>
                    <tr>
                        <td><?= htmlspecialchars($funcionario['id']); ?></td>
                        <td><?= htmlspecialchars($funcionario['nome']); ?></td>
                        <td><?= htmlspecialchars($funcionario['email']); ?></td>
                        <td>
                            <?php 
                            $badgeColor = 'secondary';
                            if ($funcionario['cargo'] === 'gerente') $badgeColor = 'primary';
                            elseif ($funcionario['cargo'] === 'preparador') $badgeColor = 'info';
                            elseif ($funcionario['cargo'] === 'caixa') $badgeColor = 'warning';
                            ?>
                            <span class="badge badge-<?= $badgeColor ?>">
                                <?= ucfirst(htmlspecialchars($funcionario['cargo'])); ?>
                            </span>
                        </td>
                        <td class="table-actions text-center">
                            <button class="btn btn-sm btn-editar" 
                                style="background-color: #388E3C; color: white;"
                                data-id="<?= $funcionario['id']; ?>"
                                data-nome="<?= htmlspecialchars($funcionario['nome']); ?>"
                                data-email="<?= htmlspecialchars($funcionario['email']); ?>"
                                data-cargo="<?= htmlspecialchars($funcionario['cargo']); ?>"
                                data-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <a href="visualizarContas.php?excluir=<?= $funcionario['id']; ?>"
                               class="btn btn-sm btn-excluir"
                               style="background-color: #E53935; color: white;"
                               data-toggle="tooltip" title="Excluir">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Modal de Edição -->
<div class="modal fade" id="editarFuncionarioModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="editarFuncionarioModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #7b1fa2;">
                <h5 class="modal-title" id="editarFuncionarioModalLabel"><i class="fas fa-user-edit mr-2"></i>Editar Funcionário</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarFuncionario">
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
                        <label for="editCargo"><i class="fas fa-briefcase mr-1"></i> Cargo</label>
                        <select name="cargo" id="editCargo" class="form-control" required>
                            <option value="gerente">Gerente</option>
                            <option value="preparador">Preparador</option>
                            <option value="caixa">Caixa</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" name="editar" class="btn text-white" style="background-color: #7b1fa2;">
                        <i class="fas fa-save mr-1"></i> Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>