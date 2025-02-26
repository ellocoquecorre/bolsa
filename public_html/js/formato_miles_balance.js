function formatoBalance(input) {
    let value = input.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2).replace('.', ',');
    value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    input.value = value;
}