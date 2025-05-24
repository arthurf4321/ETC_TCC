<?php
include('../../auth/verificarPermissão.php');
include('../../config/db.php');
verificarAcesso(['preparador', 'gerente']);
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

        .btn-Pedidos {
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
                                <button class="btn btn-block text-white btn-Pedidos" onclick="carregarPagina('pedidos.php')">
                                    <i class="fas fa-blender"></i> Pedidos
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../../../JS/funções.js"></script>

<script>
function carregarPagina(pagina) {
    const conteudoDinamico = $('#conteudoDinamico');
    const loader = $('#loader');
    
    if (conteudoDinamico.hasClass('loading')) return;
    conteudoDinamico.addClass('loading');

    $('.modal').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    
    loader.show();
    conteudoDinamico.css('opacity', '0.5');
    
    $.ajax({
        url: pagina,
        method: 'GET',
        success: function(html) {
            const novoConteudo = $(html).find('#conteudoDinamico').html();
            conteudoDinamico.html(novoConteudo || html);
            initDynamicContent();
        },
        error: function(xhr, status, error) {
            conteudoDinamico.html(`
                <div class="alert alert-danger">
                    Erro ao carregar o conteúdo. <button onclick="carregarPagina('${pagina}')" class="btn btn-sm btn-primary">Tentar novamente</button>
                </div>
            `);
        },
        complete: function() {
            loader.hide();
            conteudoDinamico.css('opacity', '1');
            conteudoDinamico.removeClass('loading');
            
            $('html, body').animate({
                scrollTop: conteudoDinamico.offset().top - 20
            }, 500);
        }
    });
}

function initDynamicContent() {
    console.log("Inicializando conteúdo dinâmico...");
    
    $(document).off('click', '.btn-editar').on('click', '.btn-editar', function(e) {
        e.preventDefault();
        console.log("Botão editar clicado", $(this).data());
        
        if ($(this).data('cargo')) {
            $('#editarFuncionarioModal #editId').val($(this).data('id'));
            $('#editarFuncionarioModal #editNome').val($(this).data('nome'));
            $('#editarFuncionarioModal #editEmail').val($(this).data('email'));
            $('#editarFuncionarioModal #editCargo').val($(this).data('cargo'));
            $('#editarFuncionarioModal').modal('show');
        } 
        else if ($(this).data('telefone')) {
            $('#editarClienteModal #editId').val($(this).data('id'));
            $('#editarClienteModal #editNome').val($(this).data('nome'));
            $('#editarClienteModal #editEmail').val($(this).data('email'));
            $('#editarClienteModal #editTelefone').val($(this).data('telefone'));
            $('#editarClienteModal').modal('show');
        } 
        else if ($(this).data('categoria')) {
            $('#editarProdutoModal #editId').val($(this).data('id'));
            $('#editarProdutoModal #editNome').val($(this).data('nome'));
            $('#editarProdutoModal #editDescricao').val($(this).data('descricao'));
            $('#editarProdutoModal #editPreco').val($(this).data('preco'));
            $('#editarProdutoModal #editCategoria').val($(this).data('categoria'));
            $('#editarProdutoModal').modal('show');
        }
    });

    $(document).off('click', '.btn-excluir').on('click', '.btn-excluir', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const btn = $(this);
        const currentPage = window.location.pathname.split('/').pop() || 'visualizarContasClientes.php';
        
        btn.html('<i class="fas fa-spinner fa-spin"></i>');
        btn.prop('disabled', true);
        
        Swal.fire({
            title: 'Confirmar Exclusão',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar',
            allowOutsideClick: false
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sucesso!',
                                text: response.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                if (window.carregarPagina) {
                                    carregarPagina(currentPage);
                                } else {
                                    $('#conteudoDinamico').load(currentPage + ' #conteudoDinamico > *', function() {
                                        initDynamicContent();
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro!',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro!',
                            text: 'Falha na comunicação com o servidor'
                        });
                    },
                    complete: function() {
                        btn.html('<i class="fas fa-trash-alt"></i>');
                        btn.prop('disabled', false);
                    }
                });
            } else {
                btn.html('<i class="fas fa-trash-alt"></i>');
                btn.prop('disabled', false);
            }
        });
    });
    
    configurarFormulariosAJAX();
}

function configurarFormulariosAJAX() {
    $('#formEditarFuncionario').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'visualizarContas.php', 'editar');
    });
    
    $('#formEditarCliente').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'visualizarContasClientes.php', 'editar');
    });
    
    $('#formEditarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'produtos.php', 'editar');
    });
    
    $('#formCadastrarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'produtos.php', 'cadastrar');
    });
}

function enviarFormularioAJAX(form, url, action) {
    const submitBtn = form.find('[type="submit"]');
    const originalText = submitBtn.html();
    const modal = form.closest('.modal');
    
    submitBtn.html('<i class="fas fa-spinner fa-spin mr-1"></i> Processando...');
    submitBtn.prop('disabled', true);
    
    $.ajax({
        url: url,
        method: 'POST',
        data: form.serialize() + '&' + action + '=1',
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                modal.modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    const currentPage = window.location.pathname.split('/').pop() || url.replace('.php', '') + '.php';
                    if (typeof carregarPagina === 'function') {
                        carregarPagina(currentPage);
                    } else {
                        location.reload();
                    }
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: response.message
                });
            }
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: 'Ocorreu um erro ao processar sua solicitação.'
            });
        },
        complete: function() {
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }
    });
}

$(document).ready(function() {
    initDynamicContent();
});

$(document).ajaxComplete(function() {
    initDynamicContent();
});

$(document).on('hidden.bs.modal', '.modal', function() {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
</script>
</body>
</html>
