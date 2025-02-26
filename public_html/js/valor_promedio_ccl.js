$(document).ready(function() {
    $('#btnAccionesDolares').on('click', function() {
        $('#valorPromedioCCL').removeClass('d-none');
    });
    $('#btnAccionesPesos').on('click', function() {
        $('#valorPromedioCCL').addClass('d-none');
    });
});