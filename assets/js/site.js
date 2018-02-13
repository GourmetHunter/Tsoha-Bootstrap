$(document).ready(function () {
    $('form.destroy-form').on('submit', function (submit) {
        var confirm_message = $(this).attr('data-confirm');
        if (!confirm(confirm_message)) {
            submit.preventDefault();
        }
    });
});

