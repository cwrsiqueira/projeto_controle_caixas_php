<?php
session_start();
require_once "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}

$data_ini = filter_input(INPUT_GET, "data_ini", FILTER_DEFAULT);
$data_fin = filter_input(INPUT_GET, "data_fin", FILTER_DEFAULT);
if (!$data_ini) {
    $data_ini = date("Y-m-01");
}
if (!$data_fin) {
    $data_fin = date("Y-m-t");
}

$sql = $db->prepare("SELECT id, nome, saldo_inicial FROM caixas WHERE id = :id");
$sql->bindValue(":id", $id);
$sql->execute();
if ($sql->rowCount() <= 0) {
    $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não não existe ou já foi excluído.</p>";
    header("Location: index.php");
    exit;
}

$caixa = $sql->fetch();

$lancamentos = [];
$sql = $db->prepare("SELECT id, id_caixa, movimento, data_movimento, discriminacao_movimento, valor_movimento FROM caixas_lancamentos WHERE id_caixa  = :id_caixa AND (data_movimento BETWEEN :data_ini AND :data_fin)");
$sql->bindValue(":id_caixa", $caixa['id']);
$sql->bindValue(":data_ini", $data_ini);
$sql->bindValue(":data_fin", $data_fin);
$sql->execute();
if ($sql->rowCount() > 0) {
    $lancamentos = $sql->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Atualiza o saldo até aquele lançamento e naquela data
 */
foreach ($lancamentos as $key => $item) {
    $entradas = 0;
    $saidas = 0;
    $sql = $db->prepare("SELECT SUM(valor_movimento) as entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada' AND data_movimento <= :data_movimento");
    $sql->bindValue(":id_caixa", $caixa['id']);
    $sql->bindValue(":data_movimento", $item['data_movimento']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
    }
    $sql = $db->prepare("SELECT SUM(valor_movimento) as saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida' AND data_movimento <= :data_movimento");
    $sql->bindValue("id_caixa", $caixa['id']);
    $sql->bindValue(":data_movimento", $item['data_movimento']);
    $sql->execute();
    if ($sql->rowCount() > 0) {
        $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
    }
    $lancamentos[$key]['saldo_atual'] = $caixa['saldo_inicial'] + $entradas - $saidas;
}

/**
 * Pega o saldo atual do caixa
 */
$entradas = 0;
$saidas = 0;
$sql = $db->prepare("SELECT SUM(valor_movimento) as entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada'");
$sql->bindValue("id_caixa", $caixa['id']);
$sql->execute();
if ($sql->rowCount() > 0) {
    $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
}
$sql = $db->prepare("SELECT SUM(valor_movimento) as saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida'");
$sql->bindValue("id_caixa", $caixa['id']);
$sql->execute();
if ($sql->rowCount() > 0) {
    $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
}
$saldo_atual = $caixa['saldo_inicial'] + $entradas - $saidas;

/**
 * Atualiza o saldo até a data do filtro data inicial
 */
$filtro_saldo_inicial = $caixa['saldo_inicial'];
$entradas = 0;
$saidas = 0;
$sql = $db->prepare("SELECT SUM(valor_movimento) as entradas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'entrada' AND data_movimento < :data_ini");
$sql->bindValue(":id_caixa", $caixa['id']);
$sql->bindValue(":data_ini", $data_ini . " 00:00:00");
$sql->execute();
if ($sql->rowCount() > 0) {
    $entradas = $sql->fetch(PDO::FETCH_ASSOC)['entradas'];
}
$sql = $db->prepare("SELECT SUM(valor_movimento) as saidas FROM caixas_lancamentos WHERE id_caixa = :id_caixa AND movimento = 'saida' AND data_movimento < :data_ini");
$sql->bindValue("id_caixa", $caixa['id']);
$sql->bindValue(":data_ini", $data_ini . " 00:00:00");
$sql->execute();
if ($sql->rowCount() > 0) {
    $saidas = $sql->fetch(PDO::FETCH_ASSOC)['saidas'];
}
$filtro_saldo_inicial = $filtro_saldo_inicial + $entradas - $saidas;
