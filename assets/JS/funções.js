function carregarPagina(pagina) {
    $.ajax({
        url: pagina,
        method: 'GET',
        success: function(response) {
            $('#conteudoDinamico').html(response);
        },
        error: function() {
            $('#conteudoDinamico').html('<div class="alert alert-danger">Erro ao carregar conteúdo.</div>');
        }
    });
}

function adicionarEventosEdicao() {
    document.querySelectorAll(".btn-editar").forEach(botao => {
        botao.addEventListener("click", function () {
            let id = this.dataset.id;
            let nome = this.dataset.nome;
            let email = this.dataset.email;
            let cargo = this.dataset.cargo;

            document.getElementById("editForm").style.display = 'block';
            document.getElementById("editId").value = id;
            document.getElementById("editNome").value = nome;
            document.getElementById("editEmail").value = email;
            document.getElementById("editCargo").value = cargo;
        });
    });
}

function closeEditForm() {
    document.getElementById('editForm').style.display = 'none';
}