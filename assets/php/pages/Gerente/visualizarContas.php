<?php
include('../../config/db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $cargo = $_POST['cargo'];

    $sql = "UPDATE funcionarios SET nome = ?, email = ?, cargo = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $email, $cargo, $id]);
    header('Location: pagGerente.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM funcionarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    header('Location: pagGerente.php');
    exit;
}

$sql = "SELECT id, nome, email, cargo FROM funcionarios";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$funcionarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Funcionários do Sistema</h3>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead style="background-color: #7b1fa2;">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($funcionarios as $funcionario): ?>
                    <tr>
                        <td><?= htmlspecialchars($funcionario['id']); ?></td>
                        <td><?= htmlspecialchars($funcionario['nome']); ?></td>
                        <td><?= htmlspecialchars($funcionario['email']); ?></td>
                        <td><?= htmlspecialchars($funcionario['cargo']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-editar"
                                style="background-color: #388E3C; color: white;"
                                data-id="<?= $funcionario['id']; ?>"
                                data-nome="<?= htmlspecialchars($funcionario['nome']); ?>"
                                data-email="<?= htmlspecialchars($funcionario['email']); ?>"
                                data-cargo="<?= htmlspecialchars($funcionario['cargo']); ?>">
                                Editar
                            </button>

                            <a href="visualizarContas.php?excluir=<?= $funcionario['id']; ?>"
                               class="btn btn-sm"
                               style="background-color: #E53935; color: white;"
                               onclick="return confirm('Tem certeza que deseja excluir este funcionário?');">
                                Excluir
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Formulário de Edição -->
        <div id="editForm" class="mt-4" style="display:none;">
            <h4>Editar Funcionário</h4>
            <form method="POST" action="visualizarContas.php">
                <input type="hidden" name="id" id="editId">
                
                <div class="form-group">
                    <label for="editNome">Nome:</label>
                    <input type="text" name="nome" id="editNome" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label for="editEmail">Email:</label>
                    <input type="email" name="email" id="editEmail" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="editCargo">Cargo:</label>
                    <input type="text" name="cargo" id="editCargo" class="form-control" required>
                </div>
                
                <button type="submit" name="editar" class="btn" style="background-color: #388E3C; color: white;">Atualizar</button>
                <button type="button" class="btn btn-secondary" style="background-color: #E53935;" onclick="closeEditForm()">Cancelar</button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript para o formulário de edição -->
<script>
    const editButtons = document.querySelectorAll('.btn-editar');
    const editForm = document.getElementById('editForm');
    const editId = document.getElementById('editId');
    const editNome = document.getElementById('editNome');
    const editEmail = document.getElementById('editEmail');
    const editCargo = document.getElementById('editCargo');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            editId.value = this.getAttribute('data-id');
            editNome.value = this.getAttribute('data-nome');
            editEmail.value = this.getAttribute('data-email');
            editCargo.value = this.getAttribute('data-cargo');
            editForm.style.display = 'block';
            window.scrollTo(0, document.body.scrollHeight);
        });
    });

    function closeEditForm() {
        editForm.style.display = 'none';
    }
</script>
