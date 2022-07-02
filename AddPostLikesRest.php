<?php
include './mysql/mysqlConnect.php';

session_start(); //Sessão Inicia
$autorLike = $_SESSION["id"];
$postID = $_POST["POSTID"];

$sql_novo = "insert into likes (IDPost, IDUser) values"
        . "('$postID','$autorLike')";

$result = $GLOBALS["db.connection"]->query($sql_novo);

echo $sql_novo;

include './mysql/mysqlClose.php';

if ($result == TRUE)
    echo "OK";
else
    echo "FALSE";
