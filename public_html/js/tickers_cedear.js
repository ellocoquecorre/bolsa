$(document).ready(function() {
    function updateDropdownPosition() {
        var tickerInput = $('#ticker_cedear');
        var dropdown = $('#tickerDropdown_cedear');

        var inputWidth = tickerInput.outerWidth();
        var inputOffset = tickerInput.offset();

        dropdown.css({
            'width': inputWidth + 'px',
            'left': inputOffset.left + 'px',
            'top': inputOffset.top + tickerInput.outerHeight() + 'px'
        });
    }

    $('#ticker_cedear').on('input', function() {
        var query = $(this).val();
        var dropdown = $('#tickerDropdown_cedear');

        if (query.length > 0) {
            $.ajax({
                url: '../funciones/tickers_cedear.php',
                type: 'GET',
                data: { term: query },
                success: function(data) {
                    dropdown.empty().hide();
                    if (data.length > 0) {
                        var items = JSON.parse(data);
                        items.forEach(function(item) {
                            dropdown.append('<a class="dropdown-item" href="#">' + item + '</a>');
                        });

                        updateDropdownPosition();
                        dropdown.show();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la solicitud AJAX:', status, error);
                }
            });
        } else {
            dropdown.empty().hide();
        }
    });

    $(window).on('resize', function() {
        updateDropdownPosition();
    });

    $(document).on('click', '#tickerDropdown_cedear .dropdown-item', function(e) {
        e.preventDefault();
        var text = $(this).text().split(' - ')[0];
        $('#ticker_cedear').val(text);
        $('#tickerDropdown_cedear').empty().hide();
    });

    $('#ticker_cedear').on('keydown', function(e) {
        var dropdownItems = $('#tickerDropdown_cedear .dropdown-item');
        var activeItem = dropdownItems.filter('.active');
        if (e.key === 'ArrowDown') {
            if (activeItem.length && activeItem.next().length) {
                activeItem.removeClass('active').next().addClass('active');
            } else {
                dropdownItems.removeClass('active').first().addClass('active');
            }
        } else if (e.key === 'ArrowUp') {
            if (activeItem.length && activeItem.prev().length) {
                activeItem.removeClass('active').prev().addClass('active');
            } else {
                dropdownItems.removeClass('active').last().addClass('active');
            }
        } else if (e.key === 'Enter' || e.key === 'Tab') {
            if (activeItem.length) {
                e.preventDefault();
                activeItem.click();
            }
        }
    });
});