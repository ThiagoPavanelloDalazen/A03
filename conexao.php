<?php
$hostname = "localhost";
$bancodedados = "formulario_cliente";
$usuario_db = "root"; 
$senha_db = "";


$mysqli = new mysqli($hostname, $usuario_db, $senha_db, $bancodedados);

if ($mysqli->connect_errno) {
    die("Falha ao conectar: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error);
}

$mysqli->set_charset("utf8");
?>
