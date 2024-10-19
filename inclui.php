<?php
require_once 'inicia.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_func = isset($_POST['nome_func']) ? $_POST['nome_func'] : null;
    $foto_func_endereco = null; 

    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $nome = $_FILES['foto']['name'];
        $temp = $_FILES['foto']['tmp_name'];

        $uploaddir = 'upload/'; 
        $uploadfile = $uploaddir . basename($nome);

        
        if (move_uploaded_file($temp, $uploadfile)) {
            $foto_func_endereco = $uploadfile; 
        } else {
            echo "Upload falhou.";
            exit();
        }
    } else {
        echo "Nenhuma foto enviada.";
        exit();
    }

    echo "Caminho da foto: " . $foto_func_endereco; 

    $PDO = conecta_bd();
    $sql = "INSERT INTO pessoa (nome_func, foto) VALUES (:nome_func, :uploadfile)";
    $stmt = $PDO->prepare($sql);
    $stmt->bindParam(':nome_func', $nome_func);
    $stmt->bindParam(':uploadfile', $foto_func_endereco);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit();
    } else {
        echo "Ocorreu um erro na inclusão.";
        print_r($stmt->errorInfo());
    }
} else {
    echo "Método não suportado.";
}
?>
