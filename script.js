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
        console.log('antes de abrir o modal');
        myInput.focus();
    });
}