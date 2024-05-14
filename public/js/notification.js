$(document).ready(function () {
    let $notification = $('#notification-data');

    if ($notification.data('notification-header')) {
        const $n = $('<div class="notification ' + $notification.data('notification-type') + '">' + '<div class="notification__header">' + $notification.data('notification-header') + '</div>' + '<div class="notification__content">' + $notification.data('notification-content') + '</div>' + '</div>');

        $('body').append($n);

        setTimeout(() => $n.remove(), 5000);
    }
});
