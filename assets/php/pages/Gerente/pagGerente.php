<?php
include('../../auth/verificarPermissão.php');
include('../../config/db.php');
verificarAcesso(['gerente']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<?php include '../../../includes/head.php'; ?>
<head>
    <link rel="icon" href="../../../../assets/imgs/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../../CSS/styles.css?v=3">
    <style>
        :root {
            --primary-color: #7b1fa2;
            --secondary-color: #388E3C;
            --danger-color: #E53935;
            --accent-color: #FFD700;
        }
        
        .card.acai-theme {
            background-color: rgb(234, 217, 238);
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(106, 27, 154, 0.2);
            border: none;
        }
        
        .btn-option {
            background-color: var(--primary-color);
            font-weight: bold;
            transition: all 0.3s ease;
            padding: 12px 0;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .btn-option:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(123, 31, 162, 0.3);
            background-color: #6a1b9a;
        }
        
        #conteudoDinamico {
            transition: all 0.4s ease;
            min-height: 500px;
            background-color: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            position: relative;
        }
        
        .loader {
            display: none;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--primary-color);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 40px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .placeholder-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 400px;
            color: var(--primary-color);
            text-align: center;
        }
        
        .placeholder-content i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.7;
        }
        
        .search-box {
            max-width: 400px;
            margin-bottom: 20px;
        }
        
        .table-actions {
            white-space: nowrap;
        }

        .modal-backdrop.fade.show {
            z-index: 1040;
        }

        .modal.fade.show {
            z-index: 1050;
        }

        body.modal-open {
            overflow: auto;
            padding-right: 0 !important;
        }
        
        #conteudoDinamico.loading {
            pointer-events: none;
        }

        #conteudoDinamico.loading::after {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255,255,255,0.7);
            z-index: 10;
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
                    <div class="col-sm-6">
                        <h1 style="color: var(--primary-color);"></h1>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="card acai-theme">
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <button class="btn btn-block text-white btn-option" onclick="carregarPagina('visualizarContas.php')">
                                    <i class="fas fa-users fa-lg mr-2"></i> Contas Funcionários
                                </button>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <button class="btn btn-block text-white btn-option" onclick="carregarPagina('visualizarContasClientes.php')">
                                    <i class="fas fa-user-friends fa-lg mr-2"></i> Contas Clientes
                                </button>
                            </div>
                            <div class="col-md-4">
                                <button class="btn btn-block text-white btn-option" onclick="carregarPagina('produtos.php')">
                                    <i class="fas fa-box-open fa-lg mr-2"></i> Gerenciar Produtos
                                </button>
                            </div>
                        </div>

                        <div id="loader" class="loader" style="display: none;"></div>
                        
                        <div id="conteudoDinamico">
                            <div class="placeholder-content">
                                <h3 style="color: var(--primary-color);">Selecione uma opção acima</h3>
                                <p class="text-muted">Gerencie todas as áreas do sistema</p>
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