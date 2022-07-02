<?php

include './mysql/mysqlConnect.php';

$id = $_GET["id"];

$sql = "delete from utilizador where id=($id);";
if ($GLOBALS["db.connection"]->query($sql) === TRUE) {
    echo "Registo apagado com sucesso";
} else {
    echo "Erro: " . $sql . "<br>" . $GLOBALS["db.connection"]->error;
}
include './mysql/mysqlClose.php';

include("index.php");


