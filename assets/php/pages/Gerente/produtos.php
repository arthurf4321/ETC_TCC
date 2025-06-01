<?php
include('../../config/db.php');
session_start();

$uploadDir = '../../../uploads/produtos/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxSize = 2 * 1024 * 1024; // 2MB

function handleImageUpload($file, $uploadDir, $allowedTypes, $maxSize) {
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['status' => 'error', 'message' => 'Erro no upload da imagem'];
    }

    if (!in_array($file['type'], $allowedTypes)) {
        return ['status' => 'error', 'message' => 'Tipo de arquivo não permitido'];
    }

    if ($file['size'] > $maxSize) {
        return ['status' => 'error', 'message' => 'Arquivo muito grande (máx. 2MB)'];
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('prod_') . '.' . $extension;
    $destination = $uploadDir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        return ['status' => 'error', 'message' => 'Falha ao salvar a imagem'];
    }

    return ['status' => 'success', 'filename' => $filename];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $categoria = $_POST['categoria'];
    $fotoAtual = $_POST['foto_atual'] ?? 'default.png';

    if (!empty($_FILES['foto']['name'])) {
        $upload = handleImageUpload($_FILES['foto'], $uploadDir, $allowedTypes, $maxSize);
        
        if ($upload['status'] === 'success') {
            $foto = $upload['filename'];
            if ($fotoAtual !== 'default.png' && file_exists($uploadDir . $fotoAtual)) {
                unlink($uploadDir . $fotoAtual);
            }
        } else {
            echo json_encode($upload);
            exit;
        }
    } else {
        $foto = $fotoAtual;
    }

    $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, categoria = ?, foto = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$nome, $descricao, $preco, $categoria, $foto, $id])) {
        echo json_encode(['status' => 'success', 'message' => 'Produto atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao atualizar produto.']);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $preco = $_POST['preco'] ?? 0;
    $categoria = $_POST['categoria'] ?? '';
    $foto = 'default.png';

    if (!empty($_FILES['foto']['name'])) {
        $upload = handleImageUpload($_FILES['foto'], $uploadDir, $allowedTypes, $maxSize);
        
        if ($upload['status'] === 'success') {
            $foto = $upload['filename'];
        } else {
            echo json_encode($upload);
            exit;
        }
    }

    if (empty($nome) || empty($categoria) || $preco <= 0) {
        echo json_encode([
            'status' => 'error', 
            'message' => 'Preencha todos os campos obrigatórios corretamente'
        ]);
        exit;
    }

    try {
        $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, foto) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        
        if ($stmt->execute([$nome, $descricao, $preco, $categoria, $foto])) {
            echo json_encode([
                'status' => 'success', 
                'message' => 'Produto cadastrado com sucesso!',
                'id' => $pdo->lastInsertId() 
            ]);
        } else {
            echo json_encode([
                'status' => 'error', 
                'message' => 'Erro ao executar a query'
            ]);
        }
    } catch (PDOException $e) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Erro no banco de dados: ' . $e->getMessage()
        ]);
    }
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $sql = "SELECT foto FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $produto = $stmt->fetch();
    
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$id])) {
        if ($produto && $produto['foto'] !== 'default.png' && file_exists($uploadDir . $produto['foto'])) {
            unlink($uploadDir . $produto['foto']);
        }
        echo json_encode(['status' => 'success', 'message' => 'Produto excluído com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erro ao excluir produto.']);
    }
    exit;
}

$sql = "SELECT id, nome, descricao, preco, categoria, foto FROM produtos ORDER BY nome";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .produto-img {
        max-width: 90px;  
        max-height: 90px; 
        border-radius: 4px;
        object-fit: cover;
        transition: transform 0.3s ease; 
    }

    .produto-img:hover {
        transform: scale(1.2);
        z-index: 10;
        position: relative;
    }
    .img-preview {
        max-width: 100px;
        max-height: 100px;
        margin-top: 10px;
        display: none;
    }
    .custom-file-label::after {
        content: "Procurar";
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0" style="color: #7b1fa2;">
        <i class="fas fa-boxes mr-2"></i>Produtos Cadastrados
    </h3>
    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#novoProdutoModal" style="background-color: #7b1fa2; border: #7b1fa2;">
        <i class="fas fa-plus mr-1" style="background-color: #7b1fa2;"></i>Novo Produto
    </button>
</div>

<div class="search-box">
    <div class="input-group">
        <div class="input-group-prepend">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
        </div>
        <input type="text" class="form-control search-input" placeholder="Pesquisar produtos...">
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <thead class="" style="background-color: #7b1fa2">
            <tr>
                <th width="70" style="color: white;">ID</th>
                <th width="100" style="color: white;">Imagem</th>
                <th style="color: white;">Nome</th>
                <th style="color: white;">Descrição</th>
                <th width="120" style="color: white;">Preço</th>
                <th width="120" style="color: white;">Categoria</th>
                <th width="130" class="text-center" style="color: white;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-box-open fa-2x mb-2"></i><br>
                        Nenhum produto cadastrado
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td><?= htmlspecialchars($produto['id']); ?></td>
                        <td>
                            <img src="../../../uploads/produtos/<?= htmlspecialchars($produto['foto'] ?? 'default.png'); ?>" 
                                 class="produto-img" 
                                 alt="<?= htmlspecialchars($produto['nome']); ?>">
                        </td>
                        <td><?= htmlspecialchars($produto['nome']); ?></td>
                        <td><?= htmlspecialchars($produto['descricao']); ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td>
                            <span class="badge badge-pill" style="background-color: <?= $produto['categoria'] === 'açaí' ? '#7b1fa2' : '#388E3C' ?>;">
                                <?= ucfirst(htmlspecialchars($produto['categoria'])); ?>
                            </span>
                        </td>
                        <td class="table-actions text-center">
                            <button class="btn btn-sm btn-editar" 
                                style="background-color: #388E3C; color: white;"
                                data-id="<?= $produto['id']; ?>"
                                data-nome="<?= htmlspecialchars($produto['nome']); ?>"
                                data-descricao="<?= htmlspecialchars($produto['descricao']); ?>"
                                data-preco="<?= htmlspecialchars($produto['preco']); ?>"
                                data-categoria="<?= htmlspecialchars($produto['categoria']); ?>"
                                data-foto="<?= htmlspecialchars($produto['foto']); ?>"
                                data-toggle="tooltip" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>

                            <a href="produtos.php?excluir=<?= $produto['id']; ?>"
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
<div class="modal fade" id="editarProdutoModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="editarProdutoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #7b1fa2;">
                <h5 class="modal-title" id="editarProdutoModalLabel"><i class="fas fa-edit mr-2"></i>Editar Produto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formEditarProduto" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id" id="editId">
                    <input type="hidden" name="foto_atual" id="editFotoAtual">
                    
                    <div class="form-group">
                        <label><i class="fas fa-camera mr-1"></i> Foto do Produto</label>
                        <div>
                            <img id="editPreviewFoto" src="" style="max-width: 100px; display: none;" class="img-thumbnail mb-2">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="editFoto" name="foto" accept="image/*">
                            <label class="custom-file-label" for="editFoto">Alterar imagem...</label>
                        </div>
                        <small class="form-text text-muted">Deixe em branco para manter a imagem atual</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="editNome"><i class="fas fa-tag mr-1"></i> Nome do Produto</label>
                        <input type="text" name="nome" id="editNome" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="editDescricao"><i class="fas fa-align-left mr-1"></i> Descrição</label>
                        <textarea name="descricao" id="editDescricao" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editPreco"><i class="fas fa-dollar-sign mr-1"></i> Preço</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" name="preco" id="editPreco" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editCategoria"><i class="fas fa-list-ul mr-1"></i> Categoria</label>
                                <select name="categoria" id="editCategoria" class="form-control" required>
                                    <option value="açaí">Açaí</option>
                                    <option value="adicional">Adicional</option>
                                </select>
                            </div>
                        </div>
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

<!-- Modal de Novo Produto -->
<div class="modal fade" id="novoProdutoModal" tabindex="-1" role="dialog" aria-modal="true" aria-labelledby="novoProdutoModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header text-white" style="background-color: #7b1fa2;">
                <h5 class="modal-title" id="novoProdutoModalLabel"><i class="fas fa-plus-circle mr-2"></i>Novo Produto</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCadastrarProduto" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="foto"><i class="fas fa-camera mr-1"></i> Foto do Produto</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="foto" name="foto" accept="image/*">
                            <label class="custom-file-label" for="foto">Escolher arquivo...</label>
                        </div>
                        <small class="form-text text-muted">Formatos: JPG, PNG, GIF (máx. 2MB)</small>
                        <div class="mt-2">
                            <img id="previewFoto" src="" style="max-width: 100px; display: none;" class="img-thumbnail">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="nome"><i class="fas fa-tag mr-1"></i> Nome do Produto</label>
                        <input type="text" name="nome" id="nome" class="form-control" required
                               placeholder="Ex: Açaí Tradicional">
                        <small class="form-text text-muted">Nome completo do produto</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="descricao"><i class="fas fa-align-left mr-1"></i> Descrição</label>
                        <textarea name="descricao" id="descricao" class="form-control" rows="3"
                                  placeholder="Descreva o produto (opcional)"></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="preco"><i class="fas fa-dollar-sign mr-1"></i> Preço</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">R$</span>
                                    </div>
                                    <input type="number" step="0.01" min="0.01" name="preco" id="preco" 
                                           class="form-control" required placeholder="0,00">
                                </div>
                                <small class="form-text text-muted">Preço unitário do produto</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="categoria"><i class="fas fa-list-ul mr-1"></i> Categoria</label>
                                <select name="categoria" id="categoria" class="form-control" required>
                                    <option value="" disabled selected>Selecione...</option>
                                    <option value="açaí">Açaí</option>
                                    <option value="adicional">Adicional</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" name="cadastrar" class="btn text-white" style="background-color: #7b1fa2;">
                        <i class="fas fa-plus-circle mr-1"></i> Cadastrar Produto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function initProdutoModal() {
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
    
    $(document).off('click', '[data-target="#novoProdutoModal"]').on('click', '[data-target="#novoProdutoModal"]', function(e) {
        e.preventDefault();
        $('#novoProdutoModal').modal('show');
    });

    $('#foto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewFoto').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
            $(this).next('.custom-file-label').addClass("selected").html(file.name);
        } else {
            $('#previewFoto').hide();
            $(this).next('.custom-file-label').removeClass("selected").html('Escolher arquivo...');
        }
    });

    $('#editFoto').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#editPreviewFoto').attr('src', e.target.result).show();
            }
            reader.readAsDataURL(file);
            $(this).next('.custom-file-label').addClass("selected").html(file.name);
        } else {
            $(this).next('.custom-file-label').removeClass("selected").html('Alterar imagem...');
        }
    });

    $(document).on('click', '.btn-editar', function() {
        const foto = $(this).data('foto') || 'default.png';
        $('#editPreviewFoto').attr('src', '../../../assets/uploads/produtos/' + foto).show();
        $('#editFotoAtual').val(foto);
    });

    $('#formCadastrarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        const nome = $('#nome').val().trim();
        const preco = parseFloat($('#preco').val());
        const categoria = $('#categoria').val();
        
        let errors = [];
        
        if (!nome) {
            errors.push('O nome do produto é obrigatório');
            $('#nome').addClass('is-invalid');
        } else {
            $('#nome').removeClass('is-invalid');
        }
        
        if (isNaN(preco) || preco <= 0) {
            errors.push('O preço deve ser um valor maior que zero');
            $('#preco').addClass('is-invalid');
        } else {
            $('#preco').removeClass('is-invalid');
        }
        
        if (!categoria) {
            errors.push('Selecione uma categoria');
            $('#categoria').addClass('is-invalid');
        } else {
            $('#categoria').removeClass('is-invalid');
        }
        
        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro no formulário',
                html: errors.join('<br>')
            });
            return false;
        }
        
        const formData = new FormData(this);
        formData.append('cadastrar', '1');
       
        const submitBtn = $(this).find('[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Cadastrando...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: 'produtos.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Resposta do servidor:', response);
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#novoProdutoModal').modal('hide');
                        $('#formCadastrarProduto')[0].reset();
                        $('.custom-file-label').html('Escolher arquivo...');
                        $('#previewFoto').hide();
                        
                        if (typeof carregarPagina === 'function') {
                            carregarPagina('produtos.php');
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.message || 'Erro ao cadastrar produto'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de conexão',
                    text: 'Não foi possível conectar ao servidor. Tente novamente.'
                });
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });

    $('#formEditarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        
        const nome = $('#editNome').val().trim();
        const preco = parseFloat($('#editPreco').val());
        const categoria = $('#editCategoria').val();
        
        let errors = [];
        
        if (!nome) {
            errors.push('O nome do produto é obrigatório');
            $('#editNome').addClass('is-invalid');
        } else {
            $('#editNome').removeClass('is-invalid');
        }
        
        if (isNaN(preco) || preco <= 0) {
            errors.push('O preço deve ser um valor maior que zero');
            $('#editPreco').addClass('is-invalid');
        } else {
            $('#editPreco').removeClass('is-invalid');
        }
        
        if (!categoria) {
            errors.push('Selecione uma categoria');
            $('#editCategoria').addClass('is-invalid');
        } else {
            $('#editCategoria').removeClass('is-invalid');
        }
        
        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Erro no formulário',
                html: errors.join('<br>')
            });
            return false;
        }
        
        const formData = new FormData(this);
        formData.append('editar', '1');
       
        const submitBtn = $(this).find('[type="submit"]');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Salvando...');
        submitBtn.prop('disabled', true);
        
        $.ajax({
            url: 'produtos.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                console.log('Resposta do servidor:', response);
                
                if (response.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        $('#editarProdutoModal').modal('hide');
                        
                        if (typeof carregarPagina === 'function') {
                            carregarPagina('produtos.php');
                        } else {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro!',
                        text: response.message || 'Erro ao atualizar produto'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de conexão',
                    text: 'Não foi possível conectar ao servidor. Tente novamente.'
                });
            },
            complete: function() {
                submitBtn.html(originalText);
                submitBtn.prop('disabled', false);
            }
        });
    });
    
    $('#novoProdutoModal').on('hidden.bs.modal', function() {
        $('#formCadastrarProduto')[0].reset();
        $('.custom-file-label').html('Escolher arquivo...');
        $('#nome, #preco, #categoria').removeClass('is-invalid');
        $('#previewFoto').hide();
    });

    $('#editarProdutoModal').on('hidden.bs.modal', function() {
        $('#editNome, #editPreco, #editCategoria').removeClass('is-invalid');
    });
}

$(document).ready(function() {
    initProdutoModal();
});

if (typeof initDynamicContent === 'function') {
    initDynamicContent();
} else {
    $('[data-toggle="tooltip"]').tooltip();
}
</script>