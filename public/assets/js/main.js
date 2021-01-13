$('.favourite').on('click', function(event) {
    event.preventDefault();

    let clicked = $(this);
    let url     = clicked.data('url');

    $.post(url)
    .done(function(response) {
        if (false !== response.updated)
            clicked.toggleClass('active');
    })
})
