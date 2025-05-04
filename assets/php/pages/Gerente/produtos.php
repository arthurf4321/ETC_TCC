<?php
include('../../config/db.php');
session_start();

// Atualizar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $descricao, $preco, $categoria, $id]);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Produto atualizado com sucesso!']);
    exit;
}

// Cadastrar novo produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];

    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria) VALUES (?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $descricao, $preco, $categoria]);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Produto cadastrado com sucesso!']);
    exit;
}

// Excluir produto
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    echo json_encode(['status' => 'sucesso', 'mensagem' => 'Produto excluído com sucesso!']);
    exit;
}

// Buscar produtos (modo normal para montar a página)
$sql = "SELECT id, nome, descricao, preco, categoria FROM produtos";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Nav Tabs -->
<ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active" id="listar-tab" data-bs-toggle="tab" data-bs-target="#listar" type="button" role="tab">Lista de Produtos</button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link" id="cadastrar-tab" data-bs-toggle="tab" data-bs-target="#cadastrar" type="button" role="tab">Cadastrar Produto</button>
  </li>
</ul>

<div class="tab-content" id="productTabContent">
  
  <!-- Aba de Lista de Produtos -->
  <div class="tab-pane fade show active" id="listar" role="tabpanel">
    <div class="card">
      <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Produtos Cadastrados</h3>
      </div>
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead style="background-color: #7b1fa2;">
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Descrição</th>
              <th>Preço</th>
              <th>Categoria</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($produtos as $produto): ?>
              <tr>
                <td><?= htmlspecialchars($produto['id']); ?></td>
                <td><?= htmlspecialchars($produto['nome']); ?></td>
                <td><?= htmlspecialchars($produto['descricao']); ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                <td><?= htmlspecialchars(ucfirst($produto['categoria'])); ?></td>
                <td>
                  <button class="btn btn-sm btn-editar" style="background-color: #388E3C; color: white;"
                    data-id="<?= $produto['id']; ?>"
                    data-nome="<?= htmlspecialchars($produto['nome']); ?>"
                    data-descricao="<?= htmlspecialchars($produto['descricao']); ?>"
                    data-preco="<?= htmlspecialchars($produto['preco']); ?>"
                    data-categoria="<?= htmlspecialchars($produto['categoria']); ?>">
                    Editar
                  </button>

                  <button class="btn btn-sm btn-excluir" style="background-color: #E53935; color: white;"
                    data-id="<?= $produto['id']; ?>">
                    Excluir
                  </button>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Formulário de Edição -->
        <div id="editForm" class="mt-4" style="display:none;">
          <h4>Editar Produto</h4>
          <form id="formEditarProduto">
            <input type="hidden" name="id" id="editId">

            <div class="form-group">
              <label for="editNome">Nome:</label>
              <input type="text" name="nome" id="editNome" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="editDescricao">Descrição:</label>
              <textarea name="descricao" id="editDescricao" class="form-control" rows="3"></textarea>
            </div>

            <div class="form-group">
              <label for="editPreco">Preço:</label>
              <input type="number" step="0.01" name="preco" id="editPreco" class="form-control" required>
            </div>

            <div class="form-group">
              <label for="editCategoria">Categoria:</label>
              <select name="categoria" id="editCategoria" class="form-control" required>
                <option value="açaí">Açaí</option>
                <option value="adicional">Adicional</option>
              </select>
            </div>

            <button type="submit" name="editar" class="btn" style="background-color: #388E3C; color: white;">Atualizar</button>
            <button type="button" class="btn btn-secondary" style="background-color: #E53935;" onclick="closeEditForm()">Cancelar</button>
          </form>
        </div>

      </div>
    </div>
  </div>

  <!-- Aba de Cadastro de Produto -->
  <div class="tab-pane fade" id="cadastrar" role="tabpanel">
    <div class="card">
      <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Cadastrar Novo Produto</h3>
      </div>
      <div class="card-body">
        <form id="formCadastrarProduto">
          <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" class="form-control" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label for="preco">Preço:</label>
            <input type="number" step="0.01" name="preco" id="preco" class="form-control" required>
          </div>

          <div class="form-group">
            <label for="categoria">Categoria:</label>
            <select name="categoria" id="categoria" class="form-control" required>
              <option value="açaí">Açaí</option>
              <option value="adicional">Adicional</option>
            </select>
          </div>

          <button type="submit" name="cadastrar" class="btn" style="background-color: #1976D2; color: white;">Cadastrar Produto</button>
        </form>
      </div>
    </div>
  </div>

</div>

<!-- JavaScript para o formulário -->
<script>
const editButtons = document.querySelectorAll('.btn-editar');
const excluirButtons = document.querySelectorAll('.btn-excluir');
const editForm = document.getElementById('editForm');
const editId = document.getElementById('editId');
const editNome = document.getElementById('editNome');
const editDescricao = document.getElementById('editDescricao');
const editPreco = document.getElementById('editPreco');
const editCategoria = document.getElementById('editCategoria');

// Abre o formulário de edição
editButtons.forEach(button => {
    button.addEventListener('click', function() {
        editId.value = this.getAttribute('data-id');
        editNome.value = this.getAttribute('data-nome');
        editDescricao.value = this.getAttribute('data-descricao');
        editPreco.value = this.getAttribute('data-preco');
        editCategoria.value = this.getAttribute('data-categoria');
        editForm.style.display = 'block';
        window.scrollTo(0, document.body.scrollHeight);
    });
});

// Exclusão de produto
excluirButtons.forEach(button => {
    button.addEventListener('click', function() {
        if (confirm('Tem certeza que deseja excluir este produto?')) {
            const id = this.getAttribute('data-id');
            fetch('produtos.php?excluir=' + id, { method: 'GET' })
            .then(response => response.json())
            .then(dados => {
                alert(dados.mensagem);
                location.href = 'pagGerente.php';
            })
            .catch(error => {
                alert('Erro ao excluir produto.');
                console.error(error);
            });
        }
    });
});

// Cadastro de produto via AJAX
document.getElementById('formCadastrarProduto').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('cadastrar', '1');

    fetch('produtos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(dados => {
        alert(dados.mensagem);
        location.href = 'pagGerente.php';
    })
    .catch(error => {
        alert('Erro ao cadastrar produto!');
        console.error(error);
    });
});

// Edição de produto via AJAX
document.getElementById('formEditarProduto').addEventListener('submit', function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    formData.append('editar', '1');

    fetch('produtos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(dados => {
        alert(dados.mensagem);
        location.href = 'pagGerente.php';
    })
    .catch(error => {
        alert('Erro ao editar produto!');
        console.error(error);
    });
});

// Fechar o formulário de edição
function closeEditForm() {
    editForm.style.display = 'none';
}
</script>