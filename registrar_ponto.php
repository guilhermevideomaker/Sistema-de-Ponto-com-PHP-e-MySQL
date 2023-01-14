<?php
session_start();
// Limpar o buffer para não apresentar erro de direcionamento
ob_start();
date_default_timezone_set('America/Sao_Paulo');

$horario_atual = date("h:i:s");

$data_entrada = date('Y/m/d');
include_once "./conexao.php";

$id_usuario = 1;

//Recupera o ultimo ponto do usuário
$query_ponto = "SELECT id AS id_ponto, saida_intervalo, retorno_intervalo, saida
        FROM pontos
        WHERE usuario_id =:usuario_id
        ORDER BY id DESC
        LIMIT 1";

//Prepara a query com o banco de dados
$result_ponto = $conn->prepare($query_ponto);

// Substituir o link da query pelo valor
$result_ponto->bindParam(':usuario_id', $id_usuario);

// Executar a Query

$result_ponto->execute();

if (($result_ponto) and ($result_ponto->rowCount() != 0)) {
    $row_ponto = $result_ponto->fetch(PDO::FETCH_ASSOC);

    //Extrair para imprimir atraves do nome da chave no array
    extract($row_ponto);
    

    //Verifica se já bateu o ponto de saida para o intervalo
    if (($saida_intervalo == "") or ($saida_intervalo == null)) {
        // Coluna que deve receber o valor
        $col_tipo_registro = "saida_intervalo";
        // Tipo de registro
        $tipo_registro = "editar";
        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "saída intervalo";
    } elseif
    //Verifica se o usiario bateu o ponto de retorno do intervalo
    (($retorno_intervalo == "") or ($retorno_intervalo == null)) {
        // Coluna que deve receber o valor
        $col_tipo_registro = "retorno_intervalo";
        // Tipo de registro
        $tipo_registro = "editar";
        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "retorno intervalo";
    } elseif
    //Verifica se o usúario bateu a saida
    (($saida == "") or ($saida == null)) {
        // Coluna que deve receber o valor
        $col_tipo_registro = "saida";
        // Tipo de registro
        $tipo_registro = "editar";
        // Texto parcial que deve ser apresentado para o usuario
        $text_tipo_registro = "saida";
    } else {
        //Criar um novo registro no BD com o horario de entrada
        {

            // Tipo de registro
            $tipo_registro = "entrada";
            // Texto parcial que deve ser apresentado para o usuario
            $text_tipo_registro = "entrada";
        }
    }
}


// Verificar o tipo de registro, novo ponto ou editar registro existente
switch ($tipo_registro) {
        // Acessa o case quando deve editar o registro
    case "editar":
        //query para editar no banco de dados

        $query_horario = "UPDATE pontos SET $col_tipo_registro =:horario_atual
        WHERE id=:id
        LIMIT 1";

        // Substituir o link da query pelo valor
        $cad_horario = $conn->prepare($query_horario);
        $cad_horario->bindParam(':horario_atual', $horario_atual);
        $cad_horario->bindParam(':id', $id_ponto);

        break;

    default:
        // Query para cadastrar no banco de dados
        $query_horario = "INSERT INTO pontos (data_entrada, entrada, usuario_id ) VALUES (:data_entrada, :entrada, :usuario_id)";
        // Preparar a Query
        $cad_horario = $conn->prepare($query_horario);
        $cad_horario->bindParam(':data_entrada', $data_entrada);
        $cad_horario->bindParam(':entrada', $horario_atual);
        $cad_horario->bindParam(':usuario_id', $id_usuario);


        break;
}

//Executar a QUERY
$cad_horario->execute();
// Acessa o IF quando cadastrar com sucesso
if ($cad_horario->rowCount()) {

    $_SESSION['msg'] = "<p class='msg' style='color: #00c700;'> Horario de $text_tipo_registro cadastrado com sucesso!</p>";
    header("Location: index.php");
    


} else {
    $_SESSION['msg'] = "<p style='color: #f00;'> Horario de $text_tipo_registro Falhou.</p>";
    header("Location: index.php");
}
