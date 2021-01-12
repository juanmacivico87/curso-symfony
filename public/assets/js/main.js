$('.favourite').on('click', function(event) {
    event.preventDefault();

    let url = $(this).data('url');

    $(this).addClass('disabled')

    $.post(url)
    .done(function(response) {
        if (false !== response.updated)
            $(this).toggleClass('active');

        $(this).removeClass('disabled');
    })
    .fail(function() {
        $(this).removeClass('disabled');
    })
})
