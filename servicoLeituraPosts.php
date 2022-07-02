<?php


//necessário para dizer ao receptor que o conteudo é json
header("Content-type: application/json");

include './mysql/mysqlConnect.php';

session_start(); //inicia a sessão

$idUsername = $_GET["idUsername"];

//Faz select a base de dados
$result=$GLOBALS["db.connection"]->query(
     "select post.IDPost, post.Privacidade, post.IDAuthor,utilizador.id,post.Data, utilizador.username, post.Texto, count(likes.IDPost) as numeroLikes ".
    "from post ". 
    "join utilizador on utilizador.id = post.IDAuthor ".
    "left join likes on likes.IDPost = post.IDPost ".
    "where post.IDAuthor = '$idUsername' ".
    "group by post.IDPost ".
     "order by post.IDPost desc"   
);

if($result == false)
{
    echo $GLOBALS["db.connection"]->error; //imprime o erro
}

$todos = array();
while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
}

echo json_encode($todos); //atribui o array do user à ultima prosicao do array geral

include './mysql/mysqlClose.php'; //fecha a ligação à base de dados

?>



