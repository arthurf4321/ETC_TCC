<div class="card">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title" style="color: white;">Novo Pedido</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="registrarPedido.php" id="formPedido">
            <div class="form-group">
                <label for="nomeCliente">Nome do Cliente:</label>
                <input type="text" name="nome_cliente" id="nomeCliente" class="form-control" autocomplete="off" required>
                <div id="listaClientes" class="list-group mt-1" style="position: absolute; z-index: 1000;"></div>
            </div>

            <div id="produtosContainer">
                <div class="produtoItem mb-3">
                    <label>Produto:</label>
                    <input type="text" class="form-control produto" autocomplete="off" data-toggle="modal" data-target="#modalProdutos" readonly required>
                    <input type="hidden" name="produto_id[]" class="produto_id">

                    <label class="mt-2">Quantidade:</label>
                    <input type="number" name="quantidade[]" class="form-control quantidade" min="1" required>
                </div>
            </div>

            <button type="button" class="btn btn-success mb-3" id="adicionarProduto">Adicionar Produto</button>

            <div class="form-group">
                <label for="formaPagamento">Forma de Pagamento:</label>
                <select name="forma_pagamento" id="formaPagamento" class="form-control" required>
                    <option value="">Selecione...</option>
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartão">Cartão</option>
                    <option value="pix">Pix</option>
                </select>
            </div>

            <button type="submit" name="fazer_pedido" class="btn" style="background-color: #7b1fa2; color: white;">Confirmar Pedido</button>
            <a href="pagCaixa.php"> <button type="button" class="btn btn-secondary" style="background-color: #E53935; color: white;">Cancelar</button></a>
        </form>
    </div>
</div>

<div class="modal fade" id="modalProdutos" tabindex="-1" role="dialog" aria-labelledby="modalProdutosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalProdutosLabel">Selecione um Produto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="listaProdutosModal">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    
    label {
        font-weight: 500;
        color: #495057;
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
        height: 42px;
    }
    
    .form-control:focus {
        border-color: #7b1fa2;
        box-shadow: 0 0 0 0.2rem rgba(123, 31, 162, 0.15);
    }
 
    .produto {
        background-color: #ffffff !important;
        cursor: pointer;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%237b1fa2" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 35px;
    }
 
    .produtoItem {
        background-color: #ffffff;
        padding: 1.25rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        margin-bottom: 1.25rem;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }
    
    .produtoItem:hover {
        border-color: #d1d1d1;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    .btn {
        padding: 0.5rem 1rem; 
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s;
        border: none;
        font-size: 0.9rem; 
    }
    
    
    .btn-success {
        background-color: #28a745;
    }

    .btn-success:hover {
        background-color: #218838;
        transform: translateY(-1px); 
    }

    .modal-content {
        border: none;
        border-radius: 10px;
        overflow: hidden;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1.25rem 1.5rem;
    }
    
    .modal-title {
        font-weight: 600;
        color: #212529;
    }
    
    .modal-body {
        padding: 1.5rem;
        max-height: 70vh;
        overflow-y: auto;
    }
    
    .modal-footer {
        border-top: 1px solid #e9ecef;
        padding: 1rem 1.5rem;
    }
 
    .produto-card {
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    
    .produto-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(123, 31, 162, 0.15);
        border-color: #7b1fa2;
    }
    
    .card-img-top {
        height: 160px;
        object-fit: cover;
        border-bottom: 1px solid #e9ecef;
    }
    
    .card-body {
        padding: 1.25rem;
    }
    
    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #212529;
        margin-bottom: 0.5rem;
    }
    
    .card-text {
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.75rem;
    }
    
    .card-text strong {
        color: #7b1fa2;
        font-size: 1.1rem;
    }
    
    /* Lista de clientes */
    #listaClientes {
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        display: none;
    }
    
    .list-group-item {
        padding: 0.75rem 1.25rem;
        border: none;
        border-bottom: 1px solid #f1f1f1;
        transition: all 0.2s;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
        color: #7b1fa2;
    }
    
    @media (max-width: 768px) {
        .btn {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }
    }
</style>

<script>
$(document).ready(function(){
    $('#nomeCliente').keyup(function(){
        var query = $(this).val();
        if (query != '') {
            $.ajax({
                url: "buscarClientes.php",
                method: "GET",
                data: {query: query},
                success: function(data) {
                    $('#listaClientes').fadeIn();
                    $('#listaClientes').html(data);
                }
            });
        } else {
            $('#listaClientes').fadeOut();
        }
    });

    window.selecionarCliente = function(nome) {
        $('#nomeCliente').val(nome);
        $('#listaClientes').fadeOut();
    }

    $('#modalProdutos').on('show.bs.modal', function () {
        $.ajax({
            url: "buscarTodosProdutos.php",
            method: "GET",
            success: function(data) {
                $('#listaProdutosModal').html(data);
            }
        });
    });

    window.selecionarProduto = function(id, nome, preco, foto) {
        var produtoInput = $('.produto').filter(function() {
            return $(this).is(':focus') || $(this).data('last-focused') === true;
        });
        
        if (produtoInput.length === 0) {
            produtoInput = $('.produto').last();
        }
        
        produtoInput.val(nome);
        produtoInput.closest('.produtoItem').find('.produto_id').val(id);
        $('#modalProdutos').modal('hide');
    }

    $(document).on('click', '.produto', function() {
        $('.produto').removeData('last-focused');
        $(this).data('last-focused', true);
    });

    $('#adicionarProduto').click(function(){
        var novoProduto = `
            <div class="produtoItem mb-3">
                <label>Produto:</label>
                <input type="text" class="form-control produto" autocomplete="off" data-toggle="modal" data-target="#modalProdutos" readonly required>
                <input type="hidden" name="produto_id[]" class="produto_id">

                <label class="mt-2">Quantidade:</label>
                <input type="number" name="quantidade[]" class="form-control quantidade" min="1" required>
            </div>
        `;
        $('#produtosContainer').append(novoProduto);
    });
});
</script>