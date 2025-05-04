function validarFormularioCadastro(event) {
    event.preventDefault();

    let nome = document.getElementById('nome').value.trim();
    let email = document.getElementById('email').value.trim();
    let senha = document.getElementById('senha').value.trim();
    let cargo = document.getElementById('cargo').value;

    if (nome === "" || email === "" || senha === "" || cargo === "") {
        alert("Preencha todos os campos");
        return false;
    }

    let regexEmail = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    if (!regexEmail.test(email)) {
        alert("Por favor, insira um e-mail válido.");
        return false;
    }

    if (senha.length < 6) {
        alert("A senha deve ter pelo menos 6 caracteres.");
        return false;
    }

    document.getElementById("formCadastro").submit();
}

// Adiciona o evento ao formulário ao carregar a página
document.addEventListener("DOMContentLoaded", function () {
    let form = document.getElementById("formCadastro");
    if (form) {
        form.addEventListener("submit", validarFormularioCadastro);
    }
});