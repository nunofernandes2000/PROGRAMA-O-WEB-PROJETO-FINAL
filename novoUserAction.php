<?php


$nome = $_POST["nome"];
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$telefone = $_POST["telefone"];
$morada = $_POST["morada"];
$idade = $_POST["idade"];
$privacidade = $_POST["privacidade"];

include './mysql/mysqlConnect.php';

$sql = "insert into utilizador (username,nome, email,morada, telefone,password,idade,privacidade) values ('$username','$nome','$email','$morada','$telefone','$password','$idade','$privacidade');";
if ($GLOBALS["db.connection"]->query($sql) === TRUE) {
    echo "Registo criado com sucesso";
} else {
    echo "Erro: " . $sql . "<br>" . $GLOBALS["db.connection"]->error;
}

include './mysql/mysqlClose.php';

include("index.php");