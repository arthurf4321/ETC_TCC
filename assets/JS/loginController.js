document.addEventListener("DOMContentLoaded", function() {
    function validarFormularioLogin(event) {
        event.preventDefault();

        var email = document.getElementById('email').value.trim();
        var senha = document.getElementById('senha').value.trim();

        // Verifica se os campos estão preenchidos
        if (email === "" || senha === "") {
            alert("Por favor, preencha todos os campos.");
            return false;
        }

        // Envia os dados via AJAX
        fetch('../php/auth/login.php', {
            method: 'POST',
            body: new URLSearchParams({
                email: email,
                senha: senha
            })
        })
        .then(response => response.json())  // Espera a resposta como JSON
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '../php/pages/home.php';
            } else {
                alert(data.message); 
            }
        })
        .catch(error => console.error('Erro ao fazer login:', error));
    }

    // Adiciona o evento de submit no formulário
    var form = document.getElementById('formLogin');
    if (form) {
        form.addEventListener('submit', validarFormularioLogin);
    } else {
        console.error('Formulário não encontrado!');
    }
});