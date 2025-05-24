
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

$(document).ready(function() {
    initDynamicContent();
    
    console.log("Modais disponíveis:", {
        funcionario: $('#editarFuncionarioModal').length > 0,
        cliente: $('#editarClienteModal').length > 0,
        produto: $('#editarProdutoModal').length > 0
    });
});

$(document).ajaxComplete(function() {
    initDynamicContent();
});