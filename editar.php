<?php
$hostname = "localhost"; 
$usuario_db = "root";    
$senha_db = "";         
$bancodedados = "formulario_cliente"; 

$mysqli = new mysqli($hostname, $usuario_db, $senha_db, $bancodedados);

if ($mysqli->connect_error) {
    die('Erro de conexão: ' . $mysqli->connect_error);
}

$id = $_GET['id']; 

$sql = "SELECT email, nome_completo FROM formulario_php WHERE id = ?"; 
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $email = $row['email'] ?? ''; 
    $nome = $row['nome_completo'] ?? '';
} else {
    echo "Registro não encontrado.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="estilo.css"> 
</head>
<body>
 
    <form method="post" action="atualizar.php">
        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required><br>

        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <input type="submit" value="Atualizar">
    </form>
</body>
</html>
