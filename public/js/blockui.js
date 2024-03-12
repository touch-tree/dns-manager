$(document).ready(function () {
    const data = {
        message: '<div class="spinner-border custom-spinner" role="status"></div>',
        css: {
            border: 'none',
            backgroundColor: 'none',
        },
        overlayCSS: {
            backgroundColor: '#fafafa'
        }
    };

    $('[data-blockui]').click(function (event) {
        $.blockUI(data)
    });

    $('[data-blockui-form]').submit(function (event) {
        $.blockUI(data)
    });
});