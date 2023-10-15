$(function () {
    $(".mask_value").mask("#.##0,00", { reverse: true });
});

let btn_search = document.querySelector('#btn-search');
if (btn_search !== null) {
    btn_search.addEventListener('click', function () {
        document.querySelector('#form-search').submit();
    });
}
let btn_clean = document.querySelector('#btn-clean');
if (btn_clean !== null) {
    btn_clean.addEventListener('click', function () {
        document.querySelector('#input-search').value = '';
        document.querySelector('#form-search').submit();
    });
}

let btn_filter_date = document.querySelector('#btn-filter-date');
if (btn_filter_date !== null) {
    btn_filter_date.addEventListener('click', function () {
        document.querySelector('#form-filter-date').submit();
    });
}
let btn_filter_date_clean = document.querySelector('#btn-filter-date-clean');
if (btn_filter_date_clean !== null) {
    btn_filter_date_clean.addEventListener('click', function () {
        document.querySelector('input[name=data_ini]').value = '';
        document.querySelector('input[name=data_fin]').value = '';
        document.querySelector('#form-filter-date').submit();
    });
}

let rpp = document.querySelector('#rpp');
if (rpp !== null) {
    rpp.addEventListener('change', function () {
        document.querySelector('#form-select-rpp').submit();
    });
}

let icon_delete = document.querySelectorAll('.icon-delete');
icon_delete.forEach(icon => {
    icon.addEventListener('click', function () {
        document.querySelector('input[name=id]').value = icon.dataset.id;
    });
});

var myModal = document.querySelector('#add-new-caixa');
var myInput = document.querySelector('input[name=nome]');
if (myModal !== null) {
    myModal.addEventListener('shown.bs.modal', function () {
        myInput.focus();
    });
}

function modal_edit(id) {
    if (id === undefined) {
        alert('ID inválido');
        return false;
    }

    var ajax = new XMLHttpRequest();
    ajax.open("GET", `ajax.php?id=${id}&action=edit_modal`, true);
    ajax.setRequestHeader("Content-type", "application/json");
    ajax.send();

    ajax.onreadystatechange = function () {
        if (ajax.readyState === 4) {
            if (ajax.status === 200) {
                let res = JSON.parse(ajax.responseText);
                if (res == 'erro') {
                    alert('Erro! Algo saiu errado, tente novamente.');
                    return false;
                }
                console.log(res.valor_movimento.toLocaleString('pt-BR'))
                document.querySelector('#edit_discriminacao_movimento').value = res.discriminacao_movimento;
                document.querySelector('#edit_data_movimento').value = res.data_movimento.split(' ')[0];
                document.querySelector('#edit_valor_movimento').value = parseFloat(res.valor_movimento).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.querySelector('#edit_movimento').value = res.movimento;

                $('#edit-lancamento').modal('show');
            } else {
                console.log(ajax.status);
            }
        }

    }
}