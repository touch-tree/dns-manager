$(document).ready(function () {
    if (messageHeader) {
        const $n = $('<div class="notification ' + messageType + '">' +
            '<div class="notification__header">' + messageHeader + '</div>' +
            '<div class="notification__content">' + messageContent + '</div>' +
            '</div>');

        $('body').append($n);

        setTimeout(function () {
            $n.remove();
        }, 10000);
    }
});
