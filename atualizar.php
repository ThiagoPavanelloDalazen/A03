<?php
$hostname = "localhost";
$usuario_db = "root";
$senha_db = "";
$bancodedados = "formulario_cliente";

$mysqli = new mysqli($hostname, $usuario_db, $senha_db, $bancodedados);


if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $id = $_POST['id'];
    $email = $_POST['email'];
    $nome = $_POST['nome'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $endereco = $_POST['endereco'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $numero = $_POST['numero'];
    $sexo = $_POST['sexo'];
    $interesses = isset($_POST['interesses']) ? implode(", ", $_POST['interesses']) : '';

 
    $foto_func_endereco = null; 
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = strtolower(str_replace(" ", "_", $_FILES['foto']['name']));
        $uploaddir = 'upload/'; 
        $uploadfile = $uploaddir . basename($nomeArquivo);

       
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadfile)) {
            $foto_func_endereco = $uploadfile; 
        } else {
            echo "Upload da foto falhou.";
        }
    }

    
    $sql = "UPDATE formulario_php SET email = ?, nome_completo = ?, cep = ?, estado = ?, endereco = ?, bairro = ?, cidade = ?, numero = ?, sexo = ?, categoria_interesse = ? " .
           ($foto_func_endereco ? ", foto = ? " : "") . " WHERE id = ?";
    $stmt = $mysqli->prepare($sql);

    if ($foto_func_endereco) {
        $stmt->bind_param("ssissssssssi", $email, $nome, $cep, $estado, $endereco, $bairro, $cidade, $numero, $sexo, $interesses, $foto_func_endereco, $id);
    } else {
        $stmt->bind_param("ssisssssssi", $email, $nome, $cep, $estado, $endereco, $bairro, $cidade, $numero, $sexo, $interesses, $id);
    }

    ?>

    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Atualizar Registro</title>
        <link rel="stylesheet" href="estilo.css">
    </head>
    <body>
        <div class="container">
            <h1>Atualizar Registro</h1>
            <?php
            if ($stmt->execute()) {
                echo "<p class='success'>Registro atualizado com sucesso!</p>";
            } else {
                echo "<p class='error'>Erro ao atualizar registro: " . $stmt->error . "</p>";
            }
            ?>
            <a href="index.php"><button class="btn">Voltar para Registros</button></a>
        </div>
    </body>
    </html>

    <?php
    $stmt->close();
} else {
    echo "<p class='error'>ID inválido.</p>";
}
$mysqli->close();
?>
