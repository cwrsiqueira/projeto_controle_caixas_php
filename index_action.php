<?php
session_start();
require_once "db.php";

$rpp = filter_input(INPUT_GET, 'rpp', FILTER_DEFAULT);
$busca = filter_input(INPUT_GET, "busca", FILTER_SANITIZE_SPECIAL_CHARS);
$query = "";

if ($busca) {
    $query = "WHERE nome LIKE :busca";
}

$busca_todos = "SELECT id, nome, saldo_inicial FROM caixas $query";
$caixas = $db->prepare($busca_todos);
if ($busca) {
    $caixas->bindValue(":busca", "%" . $busca . "%");
}
$caixas->execute();

$todos = $caixas;
$limit = $todos->rowCount();

$p = 1;
$offset = 0;

if ($rpp != 'todos') {
    if (!$rpp) {
        $limit = 10;
    } else {
        $limit = $rpp;
    }

    $pagina = filter_input(INPUT_GET, 'p', FILTER_DEFAULT);
    if (!$pagina) {
        $p = 1;
    } else {
        $p = $pagina;
    }

    $offset = $p - 1;
    $offset = $offset * $limit;

    $caixas = $db->prepare("$busca_todos LIMIT $offset, $limit");
    if ($busca) {
        $caixas->bindValue(":busca", "%" . $busca . "%");
    }
    $caixas->execute();

    $todos = $db->prepare("$busca_todos");
    if ($busca) {
        $todos->bindValue(":busca", "%" . $busca . "%");
    }
    $todos->execute();
}

$qt_registros = $todos->rowCount();
$qt_paginas = $qt_registros > 0 ? ceil($qt_registros / $limit) : 0;

$action = filter_input(INPUT_GET, "action", FILTER_SANITIZE_SPECIAL_CHARS);
if ($action && $action === "delete") {
    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

    $sql = $db->prepare("DELETE FROM caixas WHERE id = :id");
    $sql->bindValue(":id", $id);
    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert alert-danger'><i class='fa-solid fa-circle-exclamation'></i> Erro! Caixa não excluído. Tente novamente.</p>";
        header("Location: index.php");
        exit;
    }
    $_SESSION['msg'] = "<p class='alert alert-success'><i class='fa-solid fa-thumbs-up'></i> Sucesso! Caixa excluído.</p>";
    header("Location: index.php");
    exit;
}
