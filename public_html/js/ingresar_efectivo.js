function ingresarEfectivo(clienteId) {
    const monto = parseFloat(document.getElementById('ingresar_efectivo').value.replace(/\./g, '').replace(',', '.'));
    if (isNaN(monto) || monto <= 0) {
        alert('Por favor, ingrese un monto válido.');
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'backend/funciones/ingresar_efectivo.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                document.getElementById('saldo_efectivo').innerText = response.nuevo_saldo;
                document.getElementById('ingresar_efectivo').value = '0,00'; // Restablecer el campo a cero
                document.getElementById('saldo_dolares').innerText = response.nuevo_saldo_dolares; // Actualizar el saldo en dólares
            } else {
                alert(response.message);
            }
        }
    };
    xhr.send(`cliente_id=${clienteId}&monto=${monto}`);
}