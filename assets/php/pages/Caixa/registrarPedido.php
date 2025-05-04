<?php
include('../../config/db.php');

if (isset($_POST['fazer_pedido'])) {
    $nome_cliente = trim($_POST['nome_cliente']);
    $produtos = $_POST['produto_id'];
    $quantidades = $_POST['quantidade'];
    $forma_pagamento = strtolower(trim($_POST['forma_pagamento']));
    $status_pagamento = 'pendente';

    if (empty($nome_cliente) || empty($produtos) || empty($quantidades) || empty($forma_pagamento)) {
        echo "Preencha todos os campos corretamente.";
        exit;
    }

    try {
        $pdo->beginTransaction();

        $sqlCliente = "SELECT id FROM clientes WHERE nome = ?";
        $stmtCliente = $pdo->prepare($sqlCliente);
        $stmtCliente->execute([$nome_cliente]);
        $cliente = $stmtCliente->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            echo "Cliente não encontrado.";
            exit;
        }

        $cliente_id = $cliente['id'];

        $totalPedido = 0;

        foreach ($produtos as $index => $produto_id) {
            $quantidade = (int)$quantidades[$index];

            $sqlProduto = "SELECT preco FROM produtos WHERE id = ?";
            $stmtProduto = $pdo->prepare($sqlProduto);
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            if (!$produto) {
                throw new Exception("Produto com ID $produto_id não encontrado.");
            }

            $preco_unitario = $produto['preco'];
            $subtotal = $quantidade * $preco_unitario;
            $totalPedido += $subtotal;
        }

        $sqlPedido = "INSERT INTO pedidos (cliente_id, status, total) VALUES (?, 'recebido', ?)";
        $stmtPedido = $pdo->prepare($sqlPedido);
        $stmtPedido->execute([$cliente_id, $totalPedido]);

        $pedido_id = $pdo->lastInsertId();

        $sqlPagamento = "INSERT INTO pagamentos (pedido_id, metodo, status) VALUES (?, ?, ?)";
        $stmtPagamento = $pdo->prepare($sqlPagamento);
        $stmtPagamento->execute([$pedido_id, $forma_pagamento, $status_pagamento]);

        foreach ($produtos as $index => $produto_id) {
            $quantidade = (int)$quantidades[$index];

            $sqlProduto = "SELECT preco FROM produtos WHERE id = ?";
            $stmtProduto = $pdo->prepare($sqlProduto);
            $stmtProduto->execute([$produto_id]);
            $produto = $stmtProduto->fetch(PDO::FETCH_ASSOC);

            $preco_unitario = $produto['preco'];
            $subtotal = $quantidade * $preco_unitario;

            $sqlItem = "INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, subtotal) VALUES (?, ?, ?, ?)";
            $stmtItem = $pdo->prepare($sqlItem);
            $stmtItem->execute([$pedido_id, $produto_id, $quantidade, $subtotal]);
        }

        $pdo->commit();

        header("Location: pagCaixa.php");
        exit;

    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro: " . $e->getMessage();
    }

} else {
    echo "Acesso inválido.";
}
?>
