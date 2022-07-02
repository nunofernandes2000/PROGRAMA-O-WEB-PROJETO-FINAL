<?php
include './mysql/mysqlConnect.php';
?>
<html>

    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <script src="bootstrap/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script src="angular/angular.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">

    <!-- Inicio da div principal para o ng-repeat -->
    <div id="postsApp" class="container" ng-app="postsApp" ng-controller="postsController">

        <!-- Faz o scope dos posts e dos likes -->
        <script>
            var app = angular.module('postsApp', []);
            app.controller('postsController', function ($scope) {
                $scope.posts = [];

                $scope.Likes = function (post) {
                    var POSTID = post;
                    $.post(
                            "AddPostLikesRest.php",
                            {
                                "POSTID": POSTID
                            },
                            );

                }
            });
        </script>

        <?php
        session_start();
        $id = $_SESSION["id"];
        $perfilID = $id;
        error_reporting(0); //desativa o reporte de erros
        ?>



       
        <?php
        $getfriend = $_GET['friend'];

        if ($getfriend != null) {
            if ($getfriend == $id) {
                $perfilID = $id;
            } else {
                $perfilID = $getfriend;
            }
        }


        //Faz um select na tabela amigos na base de dados do facebook
        $utilizadorSQL = "SELECT * FROM amigos where IDUser1='$id' OR IDUser2='$id' ";
        $friends = $GLOBALS["db.connection"]->query($utilizadorSQL);

        $friendverify = false;

        //Faz select na privacidade na tabela utilizador na base de dados do facebook
        $privatesql = "SELECT privacidade FROM utilizador where id='$perfilID'";
        $userPrivacy = $GLOBALS["db.connection"]->query($privatesql);

        //Verificar amizade
        if ($perfilID != $_SESSION["id"]) {
            while ($row = $friends->fetch_assoc()) {
                if (($row["IDUser1"] == $id && $row["IDUser2"] == $perfilID) || ($row["IDUser2"] == $id && $row["IDUser1"] == $perfilID)) {
                    $friendverify = true;
                    break;
                }
            }
        }

        //se o id da sessão for igual ao do perfil , isto quer dizer que estamos no perfil da pessoa e dps vê verificação for falsa, quer dizer como o perfil é privado não pode ver posts
        if ($perfilID == $id) {
            $PerfilPessoal = true;
        } else if ($friendverify == false) {
            while ($row = $userPrivacy->fetch_assoc()) {
                if ($row['privacidade'] == 1) {
                    $privateverify = true;
                } else {
                    $privateverify = false;
                }
            }
        }
        ?>

        <!-- Mostra o perfil do dono, ou seja o perfil pessoal onde este pode enviar posts, tanto publicos como privados -->

        <?php if ($PerfilPessoal == true) {
            ?>
            <div class="panel panel-default panel-primary">
                <div class="panel-heading">
                    <legend>Facebook Post Section</legend>
                </div>
                <div class="panel-body">
                    <style>
                        .postcont{
                            border: 1px solid lightgray;
                            padding:30px;
                        }
                        .panel-heading{
                            font-weight:bold;
                        }

                        .fa-heart{
                            color: red;
                        }
                    </style>
                    <div class='row' ng-repeat="p in posts" >
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <p>{{p.Texto}}</p>
                            </div>
                            <div class="panel-footer">
                                <span> posted {{p.Data}} by {{p.username}}  with {{p.numeroLikes}}</span>
                                <button id="BotãoLikes" class="btn btn-light " ng-click="Likes(p.IDPost)" type="button"><i class="fa-solid fa-heart"></i></button>   
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- botão de enviar posts-->
            <div>
                <input id="posts" placeholder="Coloque aqui o post a enviar" class="form-control" type="text" name="posts"/>
                <button id="enviar" class="btn btn-primary btn-ms" type="button">Send Post</button>
            </div>

            <!-- Server para criar, as opçoes de privado ou publico -->
            <div class="form-group col-sm-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="check0" name="checkpriv" value="0">
                    <label class="form-check-label" for="private">Post Publico</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" id="private" name="checkpriv" value="1">
                    <label class="form-check-label" for="private">Post Privado</label>
                </div>
            </div>
            <?php
        } else if ($friendverify == true) {
            ?>
            <div class='row' ng-repeat="p in posts">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <p>{{p.Texto}}</p>
                    </div>
                    <div class="panel-footer">
                        <span> posted {{p.Data}} by {{p.username}}  with {{p.numeroLikes}}</span>
                        <button id="BotãoLikes" class="btn btn-light " ng-click="Likes(p.IDPost)" type="button"><i class="fa-solid fa-heart"></i></button>      
                    </div>
                </div>
            </div>
            <?php
        } else {
            if ($privateverify == false) {
                ?>
                <div class='row' ng-repeat="p in posts" ng-if="p.Privacidade == '0'">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <p>{{p.Texto}}</p>
                        </div>
                        <div class="panel-footer">
                            <span> posted {{p.Data}} by {{p.username}}  with {{p.numeroLikes}}</span>
                            <button id="BotãoLikes" class="btn btn-light " ng-click="Likes(p.IDPost)" type="button"><i class="fa-solid fa-heart"></i></button>   
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        <!--primeiro script--Servico de leitura-->
        <script>
            // SERVIÇO DE Leitura de Posts
            //Aparece no ecrã todos os posts com data correta na altura do post
            function chamaServicoLeitura()
            {
                var idUsername = <?php echo $perfilID; ?>; //id do utilizador logado

                $.getJSON(
                        "servicoLeituraPosts.php",
                        {
                            "idUsername": idUsername
                        },
                        function (jsonData)
                        {
                            angular.element($("#postsApp")).scope().posts = jsonData;
                            angular.element($("#postsApp")).scope().$apply();
                        });
            }
        </script>

        <!--             segundo script--Serviço que envia posts-->
        <script>
            // Serviço de Envio de Posts
            //Envia atraves do botão enviar, informação para Base de Dados
            $(document).ready(function () {
                setInterval(chamaServicoLeitura, 1500); // depois de 1,5 segundos, mostra no ecrã os posts enviados
                $("#enviar").click(
                        function () {
                            var posts = $("#posts").val();
                            var private = $("[name=checkpriv]:checked").val();
                            $("#posts").val("");
                            $.post(
                                    "addPostRest.php",
                                    {
                                        "posts": posts,
                                        "private": private

                                    },
                                    function (dados)
                                    {
                                        //Alerta Dados
                                        console.log(dados); //Imprime na consola, ajuda a corrigir Bugs!
                                    }
                            );
                        });
            });
        </script>               

        <div>
            <br>
            <br>
        </div>
    </div>
</html>