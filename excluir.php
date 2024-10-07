<?php
include 'conexao.php';

$id = $_GET['id'] ?? null; 

if (!$id) {
    echo "ID inválido.";
    exit();
}

$sql = "DELETE FROM formulario_php WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo "Registro excluído com sucesso!";
} else {
    echo "Erro ao excluir o registro: " . $stmt->error;
}

$stmt->close();
$mysqli->close();

header("Location: index.php");
exit();
?>
