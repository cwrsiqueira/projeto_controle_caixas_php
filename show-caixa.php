<?php
include_once "header.php";
include_once "show_action.php";
?>
<div class="container border rounded mt-3 p-3 shadow bg-light">

    <div class="row">
        <div class="col">
            <h3><i class="fa-solid fa-circle-exclamation"></i> Detalhes do Caixa</h3>
        </div>
        <div class="col d-flex justify-content-end align-items-center">
            <button class="btn btn-sm btn-light"><a href="index.php"><i class="fa-solid fa-chevron-left"></i></a></button>
            <small>Controle de Caixas</small>
        </div>
    </div>
    <hr>

    <div class="row mt-3">
        <div class="col">
            <h4><i class="fa-solid fa-pen-to-square"></i> Editar Caixa</h4>
        </div>
    </div>
    <div class="row mt-3">
        <form action="edit-caixa.php" method="post" class="needs-validation" novalidate>
            <input type="hidden" name="id" value="<?= $id; ?>">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="nome" class="form-label">Nome do Caixa</label>
                        <input type="text" name="nome" class="form-control" id="nome" value="<?= $caixa['nome']; ?>">
                        <div class="invalid-feedback">O campo é obrigatório.</div>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="saldo_inicial" class="form-label">Saldo Inicial</label>
                        <input type="text" name="saldo_inicial" id="saldo_inicial" class="form-control mask_value" value="<?= $caixa['saldo_inicial']; ?>">
                    </div>
                </div>
                <div class="col-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </form>
    </div>
    <hr>

    <div class="row mt-3">
        <div class="col">
            <h4><i class="fa-solid fa-pen-clip"></i> Lançamentos do Caixa</h4>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-new-lancamento">
                <i class="fa-solid fa-plus"></i> Adicionar lançamento
            </button>
            <div class="row mt-3">
                <?php
                if (!empty($_SESSION['msg'])) {
                    echo $_SESSION['msg'];
                    unset($_SESSION['msg']);
                }
                ?>
            </div>
            <div class="col d-flex justify-content-end align-items-center">
                <p class="font-weight-bold <?= ($saldo_atual >= 0) ? 'text-primary' : 'text-danger' ?>"><small class="text-info">Saldo Atual:</small> R$ <?= number_format($saldo_atual, 2, ',', '.') ?></p>
            </div>
        </div>
    </div>

    <div class="row mt-3 d-flex justify-content-end">
        <div class="col-4">
            <form method="get" id="form-filter-date">
                <input type="hidden" name="id" value="<?= $id; ?>">
                <input type="hidden" name="action" value="show">
                <div class="input-group">
                    <span title="Limpar Busca" class="input-group-text" id="btn-filter-date-clean"><i class="fa-solid fa-rotate-left"></i></span>
                    <span title="Buscar" class="input-group-text" id="btn-filter-date"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="date" class="form-control" name="data_ini" value="<?= date("Y-m-01") ?>">
                    <input type="date" class="form-control" name="data_fin" value="<?= date("Y-m-t") ?>">
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div class="table-responsive">
            <table class="table table-hover">
                <tr>
                    <th class="font-italic">Data Movimento</th>
                    <th class="font-italic">Discriminação</th>
                    <th class="text-right font-italic">Entrada</th>
                    <th class="text-right font-italic">Saida</th>
                    <th class="text-right font-italic">Saldo</th>
                    <th></th>
                </tr>
                <tr>
                    <td class="font-weight-bold"><?= date("d/m/Y", strtotime($data_ini)) ?></td>
                    <td class="font-weight-bold">Saldo Anterior</td>
                    <td class="font-weight-bold text-right">-</td>
                    <td class="font-weight-bold text-right">-</td>
                    <td class="text-right font-weight-bold <?= ($filtro_saldo_inicial >= 0) ? 'text-primary' : 'text-danger' ?>"><?= number_format($filtro_saldo_inicial, 2, ',', '.') ?></td>
                </tr>

                <?php foreach ($lancamentos as $item) : ?>

                    <?php if (!empty($id_lanc) && $id_lanc == $item['id'] && !empty($acao_lancamento) && $acao_lancamento == 'delete') : ?>
                        <tr style="background-color: var(--danger);color:var(--light)" class="deleted">
                        <?php elseif (!empty($id_lanc) && $id_lanc == $item['id']) : ?>
                        <tr style="background-color: var(--success-light);color:var(--dark)">
                        <?php else : ?>
                        <tr>
                        <?php endif; ?>
                        <td class="font-weight-bold">
                            <?= date("d/m/Y", strtotime($item['data_movimento'])) ?>
                            <?php if (!empty($id_lanc) && $id_lanc == $item['id']) : ?>
                                <?php if ($acao_lancamento == 'editado') : ?>
                                    <span class="badge badge-warning">Editado</span>
                                <?php elseif ($acao_lancamento == 'delete') : ?>
                                    <span class="badge badge-warning">Excluído</span>
                                <?php else : ?>
                                    <span class="badge badge-warning">Novo</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class="font-weight-bold"><?= $item['discriminacao_movimento'] ?></td>
                        <td class="text-right font-weight-bold"><?= ($item['movimento'] == 'entrada') ? number_format($item['valor_movimento'], 2, ',', '.') : '-' ?></td>
                        <td class="text-right font-weight-bold"><?= ($item['movimento'] == 'saida') ? number_format($item['valor_movimento'], 2, ',', '.') : '-' ?></td>
                        <td class="text-right font-weight-bold <?= ($item['saldo_atual'] >= 0) ? 'text-primary' : 'text-danger' ?>"><?= number_format($item['saldo_atual'], 2, ',', '.') ?></td>
                        <td title="Editar Lançamento" class="mx-2 my-1">
                            <i class="fa-solid fa-pen-to-square action-icon" onclick="modal_edit(<?= $item['id'] ?>)"></i>
                        </td>
                        </tr>
                    <?php endforeach; ?>
            </table>
        </div>
    </div>

    <!-- Modal Adicionar Lançamento -->
    <div class="modal fade" id="add-new-lancamento" tabindex="-1" aria-labelledby="add-new-lancamento" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="add-lancamento.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_caixa" value="<?= $id; ?>" required>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar lançamento</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="discriminacao_movimento" class="form-label">Discriminação do Lançamento</label>
                            <input type="text" class="form-control" name="discriminacao_movimento" id="discriminacao_movimento" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="row">
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="data_movimento">Data do Lançamento</label>
                                    <input type="date" class="form-control" name="data_movimento" id="data_movimento" value="<?= date("Y-m-d") ?>" required>
                                    <div class="invalid-feedback">O campo é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-group">
                                    <label for="valor_movimento">Valor do Lançamento</label>
                                    <input type="text" class="form-control mask_value" name="valor_movimento" id="valor_movimento" required>
                                    <div class="invalid-feedback">O campo é obrigatório.</div>
                                </div>
                            </div>
                            <div class="col-sm">
                                <label for="movimento">Tipo do Movimento</label>
                                <select name="movimento" id="movimento" class="form-control" required>
                                    <option value="entrada">Entrada</option>
                                    <option value="saida">Saida</option>
                                </select>
                                <div class="invalid-feedback">O campo é obrigatório.</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Lançamento -->
    <div class="modal fade" id="edit-lancamento" tabindex="-1" aria-labelledby="add-new-lancamento" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="edit-lancamento.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" name="id_caixa" required>
                    <input type="hidden" name="id_lancamento" required>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Adicionar lançamento</h5>
                        <h6><small>Caixa: <?= $caixa['nome'] ?></small></h6>
                        <h6><small>Saldo Disponível: R$ <?= number_format($saldo_disponivel, 2, ',', '.') ?></small></h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col">
                                <div class="form-group">
                                    <label for="discriminacao_movimento">Discriminação do Lançamento</label>
                                    <input type="text" class="form-control" name="discriminacao_movimento" id="discriminacao_movimento" required>
                                    <div class="invalid-feedback">O campo é obrigatório.</div>
                                </div>
                                <div class="row">
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="data_movimento">Data do Lançamento</label>
                                            <input type="date" class="form-control" name="data_movimento" id="data_movimento" required>
                                            <div class="invalid-feedback">O campo é obrigatório.</div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <div class="form-group">
                                            <label for="valor_movimento">Valor do Lançamento</label>
                                            <input type="text" class="form-control mask_value" name="valor_movimento" id="valor_movimento" required>
                                            <div class="invalid-feedback">O campo é obrigatório.</div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <label for="movimento">Tipo do Movimento</label>
                                        <select name="movimento" id="movimento" class="form-control" required>
                                            <option value="entrada">Entrada</option>
                                            <option value="saida">Saida</option>
                                        </select>
                                        <div class="invalid-feedback">O campo é obrigatório.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
<?php include_once "footer.php"; ?>