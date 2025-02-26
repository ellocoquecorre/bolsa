document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("retirar_btn").addEventListener("click", function () {
        let monto = document.getElementById("retirar_efectivo").value;
        let cliente_id = new URLSearchParams(window.location.search).get("cliente_id") || 1;

        // Eliminar cualquier espacio en blanco y comprobar que el campo no esté vacío
        monto = monto.trim();

        // Validar que el monto sea un número válido
        let montoNumero = parseFloat(monto.replace(/\./g, '').replace(',', '.'));

        if (!monto || isNaN(montoNumero) || montoNumero <= 0) {
            alert("Ingrese un monto válido.");
            return;
        }

        // Convertir el monto a formato adecuado (con punto como separador decimal)
        let formData = new FormData();
        formData.append("cliente_id", cliente_id);
        formData.append("monto", monto);

        fetch("../funciones/retirar_efectivo.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
            } else {
                // Actualizar los saldos en la página
                document.querySelector("p.saldo-pesos").innerHTML = `Saldo en pesos: $ ${data.saldo_pesos}`;
                document.querySelector("p.saldo-dolares").innerHTML = `Saldo en dólares: u$s ${data.saldo_dolares}`;
                document.getElementById("retirar_efectivo").value = ""; // Limpiar el campo
            }
        })
        .catch(error => console.error("Error:", error));
    });
});