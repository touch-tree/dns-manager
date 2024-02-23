$(document).on('click', '[data-toggle="modal"]', function () {
    $('#modal-content-placeholder').empty();

    const route = $(this).data('api-route');

    if (route) {
        $.ajax({
            url: route,
            method: 'POST',
            success: function (data) {
                console.log(data)

                $('#modal-content-placeholder').html(data);
            },
            error: function (error) {
                console.error('Error fetching data: ', error);
            }
        });
    }
});