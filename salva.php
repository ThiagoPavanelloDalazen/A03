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

        $insert_sql = "INSERT INTO formulario_php (nome_completo, email, data_nascimento, cep, endereco, bairro, cidade, estado, numero, sexo, categoria_interesse, login, senha) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";  

        $insert_stmt = $mysqli->prepare($insert_sql);
        if ($insert_stmt === false) {
            die("Erro na preparação da consulta: " . $mysqli->error);
        }

        $insert_stmt->bind_param(
            'sssssssssssss', 
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
            $senhaHash
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
    }

    $mysqli->close();
} else {
    echo "Nenhum dado recebido.";
}
?>
