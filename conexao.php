<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cartao";


try {
    $conn = new PDO("mysql:host=$host;dbname=" . $dbname, $user, $pass);
    // echo "conectou!";
} catch (PDOException $err) {
    echo "Erro de conexão" . $err->getMessage();
}
