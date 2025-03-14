$(document).ready(function() {
    function updateDropdownPosition() {
        var tickerInput = $('#ticker');
        var dropdown = $('#tickerDropdown_bonos');

        var inputWidth = tickerInput.outerWidth();
        var inputOffset = tickerInput.offset();

        dropdown.css({
            'width': inputWidth + 'px',
            'left': inputOffset.left + 'px',
            'top': inputOffset.top + tickerInput.outerHeight() + 'px'
        });
    }

    $('#ticker').on('input', function() {
        var query = $(this).val();
        var dropdown = $('#tickerDropdown_bonos');

        if (query.length > 0) {
            $.ajax({
                url: '../funciones/tickers_bonos.php',
                type: 'GET',
                data: { term: query },
                success: function(data) {
                    dropdown.empty().hide();
                    if (data.length > 0) {
                        var items = JSON.parse(data);
                        items.forEach(function(item) {
                            dropdown.append('<a class="dropdown-item" href="#">' +
                                item.ticker + ' - ' + item.company_name + '</a>');
                        });

                        updateDropdownPosition();
                        dropdown.show();
                    }
                }
            });
        } else {
            dropdown.empty().hide();
        }
    });

    $(window).on('resize', function() {
        updateDropdownPosition();
    });

    $(document).on('click', '#tickerDropdown_bonos .dropdown-item', function(e) {
        e.preventDefault();
        var text = $(this).text().split(' - ')[0];
        $('#ticker').val(text);
        $('#tickerDropdown_bonos').empty().hide();
    });

    $('#ticker').on('keydown', function(e) {
        var dropdownItems = $('#tickerDropdown_bonos .dropdown-item');
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