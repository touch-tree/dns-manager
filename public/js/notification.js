$(document).ready(function () {
    let $notification = $('#notification-data');

    console.log($('#notification-data'))

    if ($notification.data('message-header')) {
        const $n = $('<div class="notification ' + $notification.data('message-type') + '">' + '<div class="notification__header">' + $notification.data('message-header') + '</div>' + '<div class="notification__content">' + $notification.data('message-content') + '</div>' + '</div>');

        $('body').append($n);

        setTimeout(() => $n.remove(), 10000);
    }
});
