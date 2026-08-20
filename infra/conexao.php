<?php

$host = "localhost";
$usuario = "root";
$senha = "root";
$banco = "crud_aula_erros";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("Erro de conexão com o banco: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

?>