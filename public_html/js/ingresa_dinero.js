$(document).ready(function() {
    $('#ingresar_btn').click(function() {
        var monto = $('#ingresar_efectivo').val();
        var cliente_id = new URLSearchParams(window.location.search).get('cliente_id');

        $.ajax({
            url: '../funciones/ingresa_dinero.php',
            type: 'POST',
            data: {
                monto: monto,
                cliente_id: cliente_id
            },
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    $('#saldo_efectivo').text(result.nuevo_saldo_pesos);
                    $('#saldo_dolares').text(result.nuevo_saldo_dolares);
                } else {
                    alert('Error: ' + result.error);
                }
            }
        });
    });
});