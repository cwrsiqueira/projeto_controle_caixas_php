<?php
include_once "header.php";
include_once "index_action.php";
?>
<div class="container border rounded mt-3 p-3 shadow bg-light">

    <div class="row">
        <h3><i class="fa-solid fa-cash-register"></i> Controle de Caixas</h3>
    </div>

    <div class="row mt-3">
        <div class="col">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#add-new-caixa">
                <i class="fa-solid fa-plus"></i> Criar Novo Caixa
            </button>
        </div>

        <div class="col">
            <form method="get" id="form-search">
                <div class="input-group">
                    <span title="Limpar Busca" class="input-group-text" id="btn-clean"><i class="fa-solid fa-rotate-left"></i></span>
                    <span title="Buscar" class="input-group-text" id="btn-search"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="text" class="form-control" placeholder="Busca..." name="busca" value="<?= $busca ?? ''; ?>" id="input-search">
                </div>
            </form>
        </div>

        <div class="col">
            <form method="get" class="d-flex w-full justify-content-end" id="form-select-rpp">
                <input type="hidden" name="p" value="<?= '1'; ?>">
                <input type="hidden" name="busca" value="<?= $busca ?? ''; ?>">
                <div class="input-group">
                    <span class="input-group-text">Mostrar</span>
                    <select name="rpp" id="rpp" class="border btn btn-outline-secondary">
                        <option <?= (($_GET['rpp'] ?? 10) == 10) ? 'selected' : ''; ?> value="10">10</option>
                        <?php if ($qt_registros >= 20) : ?>
                            <option <?= (($_GET['rpp'] ?? 10) == 20) ? 'selected' : ''; ?> value="20">20</option>
                            <?php if ($qt_registros >= 40) : ?>
                                <option <?= (($_GET['rpp'] ?? 10) == 40) ? 'selected' : ''; ?> value="40">40</option>
                            <?php endif; ?>
                        <?php endif; ?>
                        <option <?= (($_GET['rpp'] ?? 10) == 'todos') ? 'selected' : ''; ?> value="todos">Todos</option>
                    </select>
                    <span class="input-group-text">resultados por página</span>
                </div>
            </form>
        </div>
    </div>

    <div class="row mt-3">
        <div style="width:100%;" class="busca-result d-flex justify-content-between">
            <p>Mostrando registros <?= $offset + 1; ?> a <?= (($offset + $limit) > $qt_registros ? $qt_registros : ($offset + $limit)); ?> de <?= $qt_registros; ?></p>
            <p>Página <?= $p; ?> de <?= $qt_paginas; ?></p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col">
            <?php
            if (!empty($_SESSION['msg'])) {
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        </div>
        <div class="col" style="height: 74px;">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-end">
                    <li class="page-item">
                        <a title="Primeira Página" class="page-link <?= ($p <= 2) ? 'hidden' : ''; ?>" href="?p=1&busca=<?= $_GET['busca'] ?? ''; ?>&rpp=<?= $_GET['rpp'] ?? ''; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item <?= ($p <= 1) ? 'hidden' : ''; ?>"><a class="page-link <?= ($p <= 2) ? 'rounded-left' : ''; ?>" href="?p=<?= $p - 1; ?>&busca=<?= $_GET['busca'] ?? ''; ?>&rpp=<?= $_GET['rpp'] ?? ''; ?>"><?= $p - 1; ?></a></li>
                    <li class="page-item active"><span class="page-link <?= ($p <= 1) ? 'rounded-left' : ''; ?> <?= ($p >= $qt_paginas) ? 'rounded-right' : ''; ?>"><?= $p; ?></span></li>
                    <li class="page-item <?= ($p >= $qt_paginas) ? 'hidden' : ''; ?>"><a class="page-link <?= ($p >= $qt_paginas - 1) ? 'rounded-right' : ''; ?>" href="?p=<?= $p + 1; ?>&busca=<?= $_GET['busca'] ?? ''; ?>&rpp=<?= $_GET['rpp'] ?? ''; ?>"><?= $p + 1; ?></a></li>
                    <li class="page-item">
                        <a title="Última Página" class="page-link <?= ($p >= $qt_paginas - 1) ? 'hidden' : ''; ?>" href="?p=<?= $qt_paginas; ?>&busca=<?= $_GET['busca'] ?? ''; ?>&rpp=<?= $_GET['rpp'] ?? ''; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Caixa</th>
                        <th>Saldo Atual</th>
                        <th colspan="2" class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($caixas as $item) : ?>
                        <tr>
                            <td>#<?= $item['id'] ?></td>
                            <td><?= $item['nome'] ?></td>
                            <td>R$ <?= number_format($item['saldo_inicial'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <a title="Detalhes do Caixa" href="show-caixa.php?id=<?= $item['id'] ?>">
                                    <i class="fa-solid fa-circle-info action-icon text-warning"></i>
                                </a>
                            </td>
                            <td class="text-center">
                                <i title="Excluir Caixa" class="fa-solid fa-trash-can action-icon icon-delete text-danger" data-bs-toggle="modal" data-bs-target="#delete-caixa" data-id="<?= $item['id'] ?>"></i>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Criar Novo Caixa -->
    <div class="modal fade" id="add-new-caixa" tabindex="-1" aria-labelledby="add-new-caixa" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add-caixa.php" method="post" class="needs-validation" novalidate>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Criar Novo Caixa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nome" class="form-label">Nome do Caixa</label>
                            <input type="text" name="nome" class="form-control" id="nome" required>
                            <div class="invalid-feedback">O campo é obrigatório.</div>
                        </div>
                        <div class="form-group mt-3">
                            <label for="saldo_inicial" class="form-label">Saldo Inicial</label>
                            <input type="text" name="saldo_inicial" id="saldo_inicial" class="form-control mask_value">
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

    <!-- Modal Excluir Caixa -->
    <div class="modal fade" id="delete-caixa" tabindex="-1" aria-labelledby="add-new-caixa" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="index_action.php" method="get">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="">
                    <div class="modal-header bg-danger text-light">
                        <h5 class="modal-title" id="exampleModalLabel"><i class="fa-solid fa-triangle-exclamation"></i> ATENÇÃO!!!</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Confirma a exclusão do caixa e todos os lançamentos vinculados?</p>
                        <p>Esta ação não pode ser desfeita.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Confirmar</button>
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