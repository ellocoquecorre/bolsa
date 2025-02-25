$(document).ready(function() {
    function updateDropdownPosition() {
        var tickerInput = $('#ticker');
        var dropdown = $('#tickerDropdown');

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
        var dropdown = $('#tickerDropdown');

        if (query.length > 0) {
            $.ajax({
                url: '../funciones/tickers_acciones.php',
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
                }
            });
        } else {
            dropdown.empty().hide();
        }
    });

    $(window).on('resize', function() {
        updateDropdownPosition();
    });

    $(document).on('click', '#tickerDropdown .dropdown-item', function(e) {
        e.preventDefault();
        var text = $(this).text().split(' - ')[0];
        $('#ticker').val(text);
        $('#tickerDropdown').empty().hide();
    });

    $('#ticker').on('keydown', function(e) {
        var dropdownItems = $('#tickerDropdown .dropdown-item');
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
