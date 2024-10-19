<?php

include 'conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nome = $mysqli->real_escape_string(trim($_POST['nome']));
    $email = $mysqli->real_escape_string(trim($_POST['email']));
    $dataNascimento = $mysqli->real_escape_string(trim($_POST['dataNascimento']));
    $cep = $mysqli->real_escape_string(trim($_POST['cep']));
    $endereco = $mysqli->real_escape_string(trim($_POST['endereco']));
    $bairro = $mysqli->real_escape_string(trim($_POST['bairro']));
    $cidade = $mysqli->real_escape_string(trim($_POST['cidade']));
    $estado = $mysqli->real_escape_string(trim($_POST['estado']));
    $numero = $mysqli->real_escape_string(trim($_POST['numero']));
    $sexo = $mysqli->real_escape_string(trim($_POST['sexo']));
    $interesses = isset($_POST['interesses']) ? $mysqli->real_escape_string(implode(", ", $_POST['interesses'])) : '';
    $login = $mysqli->real_escape_string(trim($_POST['login']));
    $senha = $mysqli->real_escape_string(trim($_POST['senha']));

    if (empty($login) || empty($senha)) {
        echo "Preencha seu login e senha.";
        exit();
    }

    
    $sql = "SELECT COUNT(*) as count FROM formulario_php WHERE email = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row['count'] > 0) {
        echo "Este email já está cadastrado.";
    } else {
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
        
       
        $uploadDir = 'upload/';
        $uploadFile = $uploadDir . basename($_FILES['foto']['name']);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        
        $check = getimagesize($_FILES['foto']['tmp_name']);
        if($check === false) {
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        
        if (file_exists($uploadFile)) {
            echo "Desculpe, já existe um arquivo com esse nome.";
            $uploadOk = 0;
        }

     
        if ($_FILES['foto']['size'] > 5000000) {
            echo "Desculpe, seu arquivo é muito grande.";
            $uploadOk = 0;
        }

      
        if(!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

     
        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES['foto']['tmp_name'], $uploadFile)) {
               
                $insert_sql = "INSERT INTO formulario_php (nome_completo, email, data_nascimento, cep, endereco, bairro, cidade, estado, numero, sexo, categoria_interesse, login, senha, foto) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";  

                $insert_stmt = $mysqli->prepare($insert_sql);
                if ($insert_stmt === false) {
                    die("Erro na preparação da consulta: " . $mysqli->error);
                }

                $insert_stmt->bind_param(
                    'ssssssssssssss', 
                    $nome, 
                    $email, 
                    $dataNascimento, 
                    $cep, 
                    $endereco, 
                    $bairro, 
                    $cidade, 
                    $estado, 
                    $numero, 
                    $sexo, 
                    $interesses, 
                    $login, 
                    $senhaHash,
                    $uploadFile 
                );

                if ($insert_stmt->execute()) {
                    echo "Cadastro realizado com sucesso!";
                } else {
                    echo "Erro ao cadastrar: " . $insert_stmt->error;
                }

                $insert_stmt->close();

                
                $stmt_auth = $mysqli->prepare("SELECT * FROM formulario_php WHERE login = ?");
                if ($stmt_auth === false) {
                    die("Erro na preparação da consulta de autenticação: " . $mysqli->error);
                }

                $stmt_auth->bind_param('s', $login);
                $stmt_auth->execute();
                $result = $stmt_auth->get_result();

                if($result->num_rows === 1){
                    $usuario = $result->fetch_assoc();

                    if(password_verify($senha, $usuario['senha'])){
                        $_SESSION['id'] = $usuario['id'];
                        $_SESSION['nome'] = $usuario['nome_completo'];
                        $_SESSION['login'] = $usuario['login'];

                        header("Location: painel.php");
                        exit();
                    } else {
                        echo "Falha ao logar! Login ou senha incorretos.";
                    }
                } else {
                    echo "Falha ao logar! Login ou senha incorretos.";
                }

                $stmt_auth->close();
            } else {
                echo "Desculpe, houve um erro ao fazer o upload do seu arquivo.";
            }
        } else {
            echo "Erro ao fazer upload da imagem. Verifique os requisitos.";
        }
    }

    $mysqli->close();
} else {
    echo "Nenhum dado recebido.";
}
?>
