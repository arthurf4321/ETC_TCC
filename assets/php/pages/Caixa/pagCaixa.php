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

<script>
// Função para carregar páginas dinamicamente
function carregarPagina(pagina) {
    const conteudoDinamico = $('#conteudoDinamico');
    const loader = $('#loader');
    
    // Prevenir múltiplos carregamentos simultâneos
    if (conteudoDinamico.hasClass('loading')) return;
    conteudoDinamico.addClass('loading');
    
    // Fechar todos os modais
    $('.modal').modal('hide');
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    
    loader.show();
    conteudoDinamico.css('opacity', '0.5');
    
    $.ajax({
        url: pagina,
        method: 'GET',
        success: function(html) {
            // Extrair apenas o conteúdo interno para evitar duplicação
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

// Função para inicializar componentes dinâmicos
function initDynamicContent() {
    console.log("Inicializando conteúdo dinâmico...");
    
    // Configurar eventos de edição para todos os botões usando delegação
    $(document).off('click', '.btn-editar').on('click', '.btn-editar', function(e) {
        e.preventDefault();
        console.log("Botão editar clicado", $(this).data());
        
        // Determinar qual modal abrir baseado nos dados disponíveis
        if ($(this).data('cargo')) {
            // Edição de funcionário
            $('#editarFuncionarioModal #editId').val($(this).data('id'));
            $('#editarFuncionarioModal #editNome').val($(this).data('nome'));
            $('#editarFuncionarioModal #editEmail').val($(this).data('email'));
            $('#editarFuncionarioModal #editCargo').val($(this).data('cargo'));
            $('#editarFuncionarioModal').modal('show');
        } 
        else if ($(this).data('telefone')) {
            // Edição de cliente
            $('#editarClienteModal #editId').val($(this).data('id'));
            $('#editarClienteModal #editNome').val($(this).data('nome'));
            $('#editarClienteModal #editEmail').val($(this).data('email'));
            $('#editarClienteModal #editTelefone').val($(this).data('telefone'));
            $('#editarClienteModal').modal('show');
        } 
        else if ($(this).data('categoria')) {
            // Edição de produto
            $('#editarProdutoModal #editId').val($(this).data('id'));
            $('#editarProdutoModal #editNome').val($(this).data('nome'));
            $('#editarProdutoModal #editDescricao').val($(this).data('descricao'));
            $('#editarProdutoModal #editPreco').val($(this).data('preco'));
            $('#editarProdutoModal #editCategoria').val($(this).data('categoria'));
            $('#editarProdutoModal').modal('show');
        }
    });

    // Configurar confirmação para exclusões - VERSÃO FINAL CORRIGIDA
    $(document).off('click', '.btn-excluir').on('click', '.btn-excluir', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        const btn = $(this);
        const currentPage = window.location.pathname.split('/').pop() || 'visualizarContasClientes.php';
        
        // Mostrar loading no botão clicado
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
                                // Recarregar usando o método específico da página
                                if (window.carregarPagina) {
                                    carregarPagina(currentPage);
                                } else {
                                    // Fallback seguro que não causa duplicação
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
                // Restaurar botão se cancelar
                btn.html('<i class="fas fa-trash-alt"></i>');
                btn.prop('disabled', false);
            }
        });
    });
    
    // Configurar formulários AJAX
    configurarFormulariosAJAX();
}

// Configurar todos os formulários AJAX
function configurarFormulariosAJAX() {
    // Formulário de edição de funcionário
    $('#formEditarFuncionario').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'visualizarContas.php', 'editar');
    });
    
    // Formulário de edição de cliente
    $('#formEditarCliente').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'visualizarContasClientes.php', 'editar');
    });
    
    // Formulário de edição de produto
    $('#formEditarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'produtos.php', 'editar');
    });
    
    // Formulário de cadastro de produto
    $('#formCadastrarProduto').off('submit').on('submit', function(e) {
        e.preventDefault();
        enviarFormularioAJAX($(this), 'produtos.php', 'cadastrar');
    });
}

// Função genérica para enviar formulários via AJAX
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
                // Fechar o modal antes de recarregar
                modal.modal('hide');
                
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso!',
                    text: response.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // Recarregar o conteúdo após o modal estar completamente fechado
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

// Inicializar quando o DOM estiver pronto
$(document).ready(function() {
    initDynamicContent();
});

// Re-inicializar quando conteúdo for carregado via AJAX
$(document).ajaxComplete(function() {
    initDynamicContent();
});

// Limpeza de modais quando fechados
$(document).on('hidden.bs.modal', '.modal', function() {
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
});
</script>
</body>
</html>
