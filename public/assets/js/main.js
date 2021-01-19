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

$('body').on('submit', 'form[name="tag"][data-ajax="true"]', function(event) {
    event.preventDefault();

    let form        = $(this);
    let btnSubmit   = form.find('button[type="submit"]');
    let container   = form.closest('.modal-body');
    let selectedTag = $('#bookmarkTag');
    let url         = form.attr('action');

    btnSubmit.addClass('disabled');

    let data = {};

    $.each(form.serializeArray(), function() {
        data[this.name] = this.value;
    });

    $.post(url, data)
    .done(function(response) {
        container.html('');
        container.append(response.form.content);
        btnSubmit.removeClass('disabled');
    })
    .fail(function() {
        btnSubmit.removeClass('disabled');
    });
})
