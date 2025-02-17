$(document).ready(function() {
    $('.eliminar').click(function() {
        var monto = $('#retirar_efectivo').val();
        var cliente_id = new URLSearchParams(window.location.search).get('id');

        $.ajax({
            url: '../funciones/retira_dinero.php',
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