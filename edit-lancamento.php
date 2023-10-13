<?php
session_start();
include_once "db.php";

$dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

$campos_vazios = false;
foreach ($dados as $item) {
    if ($item == '') {
        $campos_vazios = true;
    }
}
$dados['valor_movimento'] = str_replace(',', '.', str_replace('.', '', $dados['valor_movimento']));
$data_movimento = $dados['data_movimento'];
$dados['data_movimento'] = $dados['data_movimento'] . date(" H:i:s");

if (!$campos_vazios) {
    foreach ($dados as $key => $value) {
        $values[] = $key . " = :" . $key;
    }
    $values = implode(", ", $values);

    $query = "INSERT INTO caixas_lancamentos SET $values";

    $sql = $db->prepare($query);
    foreach ($dados as $key => $value) {
        $sql->bindValue(":" . $key, $value);
    }
    if (!$sql->execute()) {
        $_SESSION['msg'] = "<p class='alert alert-danger'>Erro! Lançamento não cadastrado.</p>";
        header("Location: show-caixa.php?id=" . $dados['id_caixa']);
        exit;
    }
    $id_lanc = $db->lastInsertId();
    $_SESSION['msg'] = "<p class='alert alert-success'>Sucesso! Lançamento efetuado com sucesso.</p>";
    header("Location: financeiro/caixa-editar/?id=$id&data_ini=$data_movimento&data_fin=$data_movimento&id_lanc=$id_lanc");
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
} else {
    $_SESSION['msg'] = "<p class='alert alert-danger'>Erro! Lançamento não adicionado. Todos os campos são obrigatórios.</p>";
    header("Location: show-caixa.php?id=" . $dados['id_caixa']);
    exit;
}
