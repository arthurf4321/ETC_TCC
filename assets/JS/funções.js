// Função para inicializar todos os eventos
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

    // Configurar confirmação para exclusões
    $(document).off('click', '.btn-excluir').on('click', '.btn-excluir', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        
        Swal.fire({
            title: 'Confirmar Exclusão',
            text: "Esta ação não pode ser desfeita!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
}

// Inicializar quando o DOM estiver pronto
$(document).ready(function() {
    initDynamicContent();
    
    // Debug: Verificar se modais estão carregados
    console.log("Modais disponíveis:", {
        funcionario: $('#editarFuncionarioModal').length > 0,
        cliente: $('#editarClienteModal').length > 0,
        produto: $('#editarProdutoModal').length > 0
    });
});

// Re-inicializar quando conteúdo for carregado via AJAX
$(document).ajaxComplete(function() {
    initDynamicContent();
});