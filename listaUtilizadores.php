<?php
include './mysql/mysqlConnect.php';
$sql = "SELECT * FROM utilizador";
$result = $GLOBALS["db.connection"]->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">Utilizados Registados na Base de Dados</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Idade</th>
                        <th>morada</th>
                        <th>password</th>
                        <th>privacidade</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
              <?php
              while($row = $result->fetch_assoc())           
              {
                ?>
                    <tr>
                        <td><?php echo $row["id"] ?></td>
                        <td><?php echo $row["username"] ?></td>
                        <td><?php echo $row["nome"] ?></td>
                        <td><?php echo $row["email"] ?></td>
                        <td><?php echo $row["idade"] ?></td>
                        <td><?php echo $row["morada"] ?></td>
                        <td><?php echo $row["password"] ?></td>
                        <td><?php if ($row["privacidade"] == "0"){
                        echo "publico";} else {echo "privado";}
 ?></td>
                        <td>
                            <a href="delete.php?id=<?php echo $row["id"] ?>"><span class="glyphicon glyphicon-remove"></span></a>
                        </td>

                    </tr>

                <?php
              }

              ?>
                </tbody>
            </table>
        </div>
    </div>
  <?php
} else {
    echo "0 resultados";
}

    include './mysql/mysqlClose.php';
?>