<?php
$hostname = "localhost"; 
$usuario_db = "root";    
$senha_db = "";         
$bancodedados = "formulario_cliente"; 

$mysqli = new mysqli($hostname, $usuario_db, $senha_db, $bancodedados);


if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$id = $_GET['id'] ?? null; 


if ($id === null || !is_numeric($id)) {
    die("ID inválido.");
}

$sql = "SELECT email, nome_completo, data_nascimento, cep, sexo, categoria_interesse, endereco, bairro, cidade, numero, estado, foto FROM formulario_php WHERE id = ?"; 
$stmt = $mysqli->prepare($sql);


if ($stmt === false) {
    die('Erro na preparação da declaração: ' . $mysqli->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'] ?? ''; 
    $nome = $row['nome_completo'] ?? '';
    $cep = $row['cep'] ?? '';
    $interesses = $row['categoria_interesse'] ?? '';
    $sexo = $row['sexo'] ?? '';
    $endereco = $row['endereco'] ?? '';
    $bairro = $row['bairro'] ?? '';
    $cidade = $row['cidade'] ?? '';
    $numero = $row['numero'] ?? '';
    $estado = $row['estado'] ?? '';
    $fotoAtual = $row['foto'] ?? ''; 
} else {
    die("Registro não encontrado com ID: $id.");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="editar.css"> 
    <script>
        function buscarCEP() {
            let cep = document.getElementById('inputCEP').value.replace(/\D/g, ''); 

            if (cep.length !== 8) {
                alert("O CEP deve conter 8 dígitos.");
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => { 
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('estado').value = data.uf;
                    } else {
                        alert("CEP não encontrado.");
                    }
                })
                .catch(error => {
                    console.error("Erro ao buscar o CEP:", error);
                    alert("Erro ao buscar o CEP. Tente novamente.");
                });
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Editar Registro</h1>
        <form method="post" action="atualizar.php" enctype="multipart/form-data"> 
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>

            <label for="inputCEP">CEP:</label>
            <input type="text" id="inputCEP" name="cep" value="<?php echo htmlspecialchars($cep); ?>" onblur="buscarCEP()" maxlength="8" required><br>

            <label for="estado">Estado:</label>
            <input type="text" id="estado" name="estado" value="<?php echo htmlspecialchars($estado); ?>" required><br>

            <label for="endereco">Endereço:</label>
            <input type="text" id="endereco" name="endereco" value="<?php echo htmlspecialchars($endereco); ?>" required><br>

            <label for="bairro">Bairro:</label>
            <input type="text" id="bairro" name="bairro" value="<?php echo htmlspecialchars($bairro); ?>" required><br>

            <label for="cidade">Cidade:</label>
            <input type="text" id="cidade" name="cidade" value="<?php echo htmlspecialchars($cidade); ?>" required><br>

            <label for="numero">Número:</label>
            <input type="text" id="numero" name="numero" value="<?php echo htmlspecialchars($numero); ?>" required><br>

            <label>Interesses:</label><br>
            <input type="checkbox" name="interesses[]" value="Praia" <?php echo (strpos($interesses, 'Praia') !== false) ? 'checked' : ''; ?>> Praia<br>
            <input type="checkbox" name="interesses[]" value="Campo" <?php echo (strpos($interesses, 'Campo') !== false) ? 'checked' : ''; ?>> Campo<br>
            <input type="checkbox" name="interesses[]" value="Nacionais" <?php echo (strpos($interesses, 'Nacionais') !== false) ? 'checked' : ''; ?>> Nacionais<br>
            <input type="checkbox" name="interesses[]" value="Internacionais" <?php echo (strpos($interesses, 'Internacionais') !== false) ? 'checked' : ''; ?>> Internacionais<br>

            <fieldset>
                <legend>Sexo:</legend>
                <input type="radio" name="sexo" id="sexoMasculino" value="Masculino" <?php echo ($sexo === 'Masculino') ? 'checked' : ''; ?> required>
                <label for="sexoMasculino">Masculino</label>
                <input type="radio" name="sexo" id="sexoFeminino" value="Feminino" <?php echo ($sexo === 'Feminino') ? 'checked' : ''; ?> required>
                <label for="sexoFeminino">Feminino</label>
            </fieldset>

            <label for="foto">Foto:</label>
            <input type="file" id="foto" name="foto" accept="image/png, image/jpeg"><br>

            <?php if ($fotoAtual): ?>
                <p>Foto atual:</p>
                <img src="<?php echo htmlspecialchars($fotoAtual); ?>" alt="Foto atual" style="max-width: 200px; max-height: 200px;"><br>
            <?php endif; ?>

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" value="Atualizar">
        </form>
    </div>
</body>
</html>
