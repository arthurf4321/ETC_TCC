<div class="card">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Novo Pedido</h3>
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
                    <input type="text" class="form-control produto" autocomplete="off" required>
                    <input type="hidden" name="produto_id[]" class="produto_id">

                    <div class="list-group mt-1 listaProdutos" style="position: absolute; z-index: 1000;"></div>

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

    $(document).on('keyup', '.produto', function(){
        var query = $(this).val();
        var lista = $(this).siblings('.listaProdutos');
        if (query != '') {
            $.ajax({
                url: "buscarProdutos.php",
                method: "GET",
                data: {query: query},
                success: function(data) {
                    lista.fadeIn();
                    lista.html(data);
                }
            });
        } else {
            lista.fadeOut();
        }
    });

    window.selecionarCliente = function(nome) {
        $('#nomeCliente').val(nome);
        $('#listaClientes').fadeOut();
    }

    window.selecionarProduto = function(id, nome, elemento) {
        $(elemento).closest('.produtoItem').find('.produto').val(nome);
        $(elemento).closest('.produtoItem').find('.produto_id').val(id);
        $(elemento).closest('.produtoItem').find('.listaProdutos').fadeOut();
    }

    $('#adicionarProduto').click(function(){
        var novoProduto = `
            <div class="produtoItem mb-3">
                <label>Produto:</label>
                <input type="text" class="form-control produto" autocomplete="off" required>
                <input type="hidden" name="produto_id[]" class="produto_id">

                <div class="list-group mt-1 listaProdutos" style="position: absolute; z-index: 1000;"></div>

                <label class="mt-2">Quantidade:</label>
                <input type="number" name="quantidade[]" class="form-control quantidade" min="1" required>
            </div>
        `;
        $('#produtosContainer').append(novoProduto);
    });
});
</script>
