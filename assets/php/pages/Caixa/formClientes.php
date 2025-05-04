<div class="card">
    <div class="card-header" style="background-color: #7b1fa2; color: white;">
        <h3 class="card-title">Novo Cliente</h3>
    </div>
    <div class="card-body">
        <form method="POST" action="registrarClientes.php">
            <div class="form-group">
                <label for="nomeCliente">Nome:</label>
                <input type="text" name="nome_cliente" id="nomeCliente" class="form-control" placeholder="Digite o nome do cliente" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Digite o email do cliente" required>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" name="telefone" id="telefone" class="form-control" placeholder="(XX) 0 XXXX-XXXX" required>
            </div>

            <div class="form-group form-check mt-3">
                <input type="checkbox" class="form-check-input" id="lgpd" name="aceite_lgpd" required>
                <label class="form-check-label" for="lgpd">
                    Aceito os <a href="politica_privacidade.php" target="_blank">termos de uso e a política de privacidade (LGPD)</a>.
                </label>
            </div>

            <button type="submit" name="registro_cliente" class="btn" style="background-color: #7b1fa2; color: white;">Cadastrar</button>
            <a href="pagCaixa.php">
                <button type="button" class="btn btn-secondary" style="background-color: #E53935; color: white;">Cancelar</button>
            </a>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- jQuery Mask Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>

<script>
    $(document).ready(function(){
        $('#telefone').mask('(00) 0 0000-0000');
    });
</script>