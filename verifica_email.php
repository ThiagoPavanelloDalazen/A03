<?php
include 'conexao.php';

if (isset($_POST['email'])) {
    $email = $_POST['email'];

    $sql = "SELECT COUNT(*) as count FROM formulario_php WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    echo json_encode(['exists' => $row['count'] > 0]);

    $stmt->close();
}
?>
