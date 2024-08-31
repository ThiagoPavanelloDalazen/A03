function buscarCEP() {
    let cep = document.getElementById('inputCEP').value;

    if (cep.length !== 8) {
        alert("O CEP deve conter 8 dígitos.");
        return;
    }

    fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then(response => response.json())
        .then(data => {
            if (!data.erro) {
                document.getElementById('inputEndereco').value = data.logradouro;
                document.getElementById('inputBairro').value = data.bairro;
                document.getElementById('inputCidade').value = data.localidade;
                document.getElementById('inputEstado').value = data.uf;

                document.getElementById('inputEndereco').setAttribute('readonly', true);
                document.getElementById('inputBairro').setAttribute('readonly', true);
                document.getElementById('inputCidade').setAttribute('readonly', true);
                document.getElementById('inputEstado').setAttribute('readonly', true);
            } else {
                alert("CEP não encontrado.");
            }
        })
        .catch(error => {
            console.error("Erro ao buscar o CEP:", error);
            alert("Erro ao buscar o CEP. Tente novamente.");
        });
}

function validarFormulario(event) {
    const nome = document.getElementById('inputNome').value.trim();
    const nascimento = new Date(document.getElementById('inputDataNascimento').value);
    const hoje = new Date();

    if (isNaN(nascimento.getTime())) {
        alert("Data de nascimento inválida.");
        event.preventDefault();
        return;
    }

    const idade = hoje.getFullYear() - nascimento.getFullYear();
    const idadeValida = hoje.getMonth() > nascimento.getMonth() || 
        (hoje.getMonth() === nascimento.getMonth() && hoje.getDate() >= nascimento.getDate());

    if (idade < 18 || !idadeValida) {
        alert("Você deve ser maior de idade para se registrar.");
        event.preventDefault();
        return;
    }

    const nomeCompleto = nome.split(' ').length >= 2;
    if (!nomeCompleto) {
        alert("Por favor, preencha o nome completo.");
        event.preventDefault();
        return;
    }

    const sexo = document.querySelector('input[name="sexo"]:checked');
    if (sexo) {
        alert(`Olá ${sexo.value === "Masculino" ? "Sr" : "Sra"}`);
    } else {
        alert("Por favor, selecione o sexo.");
        event.preventDefault();
        return;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', validarFormulario);
});
