<?php
$hostname = "localhost";
$usuario_db = "root";
$senha_db = "";
$bancodedados = "formulario_cliente";


$mysqli = new mysqli($hostname, $usuario_db, $senha_db, $bancodedados);


if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}


$id = $_POST['id'];
$email = $_POST['email'];
$nome = $_POST['nome'];

$sql = "UPDATE formulario_php SET email = ?, nome_completo = ? WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ssi", $email, $nome, $id);
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
$mysqli->close();
?>
