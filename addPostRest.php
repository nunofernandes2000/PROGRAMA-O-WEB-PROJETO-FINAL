<?php

include './mysql/mysqlConnect.php';

$posts = $_POST["posts"];

session_start();

$id = $_SESSION["id"]; //ID DO UTILIZADOR logado no momento
$qprivacidade = $_POST["private"];



$sql_novo = "insert into post (Data,Texto,IDAuthor,Privacidade) "
    . "VALUES (NOW(),'$posts','$id','$qprivacidade')";


$result = $GLOBALS["db.connection"]->query($sql_novo);

echo $sql_novo;

include './mysql/mysqlClose.php';

if($result == TRUE)
    echo "OK";

else
    echo "FALSE";