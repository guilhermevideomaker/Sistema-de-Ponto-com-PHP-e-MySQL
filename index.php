<?php
session_start();
include_once "./conexao.php";

// Limpar o buffer para não apresentar erro de direcionamento

date_default_timezone_set('America/Sao_Paulo');
$id = 1;
$query_ponto = "SELECT *
        FROM pontos
        WHERE usuario_id =:usuario_id
        ORDER BY id DESC
        LIMIT 1";

//Prepara a query com o banco de dados

$result_ponto = $conn->prepare($query_ponto);

// Substituir o link da query pelo valor
$result_ponto->bindParam(':usuario_id', $id);

// Executar a Query

$result_ponto->execute();
$row_ponto = $result_ponto->fetch(PDO::FETCH_ASSOC);
extract($row_ponto);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponto</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link href="https://fonts.googleapis.com/css2?family=Merriweather:ital,wght@1,900&family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="box">
            <p class="data"><?= date('d/m/Y ')?></p>
        <p id="horario"><?php echo date("H:i:s") ?> </p>
        <?php
            if (isset($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                
                unset($_SESSION['msg']);
            }
            ?>
            <h1>Registrar Ponto</h1>
            <ul class="lista-pontos">
                <li><p>Entrada: </p> <span><?= $entrada ?></span></li>
                <li><p>Saida Intervalo: </p> <span><?= $saida_intervalo ?></span></li>
                <li><p>Retorno Intervalo: </p> <span><?= $retorno_intervalo ?></span></li>
                <li><p>Saida: </p> <span><?= $saida?></span></li>

            </ul>
            


            <a class="registrar-btn" href="./registrar_ponto.php">Registrar Ponto</a>
          </div>

        </div>


    <script>
        var apHorario = document.getElementById('horario');


        function atualizarHorario() {
            var data = new Date().toLocaleTimeString("pt-br", {
                timeZone: "America/Sao_Paulo"
            });
            var formatarData = data.replace(", ", " - ");
            apHorario.innerHTML = formatarData;
        }

        setInterval(atualizarHorario, 1000);
    </script>
</body>

</html>