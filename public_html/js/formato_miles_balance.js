function formatoBalance(input) {
    let value = input.value.replace(/\D/g, '');  // Eliminar cualquier carácter no numérico
    value = (value / 100).toFixed(2).replace('.', ',');  // Formatear el número con dos decimales y coma
    value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');  // Agregar puntos cada tres dígitos
    input.value = value;
}
