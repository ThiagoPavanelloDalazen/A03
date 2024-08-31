<?php
if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $dataNascimento = htmlspecialchars($_POST['dataNascimento']);
    $cep = htmlspecialchars($_POST['cep']);
    $endereco = htmlspecialchars($_POST['endereco']);
    $bairro = htmlspecialchars($_POST['bairro']);
    $cidade = htmlspecialchars($_POST['cidade']);
    $estado = htmlspecialchars($_POST['estado']);
    $numero = htmlspecialchars($_POST['numero']);
    $sexo = htmlspecialchars($_POST['sexo']);
    $interesses = isset($_POST['interesses']) ? $_POST['interesses'] : [];

    $nascimento = new DateTime($dataNascimento);
    $hoje = new DateTime();
    $idade = $hoje->diff($nascimento)->y;

    $saudacao = $sexo == "Masculino" ? "Olá Sr" : "Olá Sra";

    if ($idade < 18) {
        echo "Você deve ser maior de idade para se registrar.";
    } else {
        echo "<h1>Dados do Formulário</h1>";
        echo "<p>$saudacao, $nome!</p>";
        echo "<p>Email: $email</p>";
        echo "<p>Data de Nascimento: $dataNascimento</p>";
        echo "<p>CEP: $cep</p>";
        echo "<p>Endereço: $endereco, $numero</p>";
        echo "<p>Bairro: $bairro</p>";
        echo "<p>Cidade: $cidade</p>";
        echo "<p>Estado: $estado</p>";
        echo "<p>Sexo: $sexo</p>";
        echo "<p>Interesses: " . implode(", ", $interesses) . "</p>";
    }
} else {
    echo "Nenhum dado recebido.";
}
?>
