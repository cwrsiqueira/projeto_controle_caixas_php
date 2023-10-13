<?php

/**
 * Fazer a conexão com o banco de dados
 */

$host = 'localhost';
$dbname = 'controle_caixas';
$user = 'root';
$pass = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    // echo 'Banco de dados conectado';
} catch (PDOException $e) {
    echo $e->getMessage();
}

/**
 * Gerar dados aleatórios de teste no DB
 */
// for ($i = 0; $i < 100; $i++) {
//     $sql = $db->prepare("INSERT INTO caixas SET nome = :nome");
//     $sql->bindValue(":nome", "Caixa $i");
//     $sql->execute();
// }
