document.addEventListener("DOMContentLoaded", function() {
    function validarFormularioLogin(event) {
        event.preventDefault();

        var email = document.getElementById('email').value.trim();
        var senha = document.getElementById('senha').value.trim();

        if (email === "" || senha === "") {
            alert("Por favor, preencha todos os campos.");
            return false;
        }

        fetch('../php/auth/login.php', {
            method: 'POST',
            body: new URLSearchParams({
                email: email,
                senha: senha
            })
        })
        .then(response => response.json()) 
        .then(data => {
            if (data.status === 'success') {
                window.location.href = '../php/pages/home.php';
            } else {
                alert(data.message); 
            }
        })
        .catch(error => console.error('Erro ao fazer login:', error));
    }

    var form = document.getElementById('formLogin');
    if (form) {
        form.addEventListener('submit', validarFormularioLogin);
    } else {
        console.error('Formulário não encontrado!');
    }
});