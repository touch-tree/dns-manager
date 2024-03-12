$(document).on('input', '#pagerule_url', function () {
    const pageruleUrlValue = $(this).val();

    $('#pagerule_full_url').val('www.' + pageruleUrlValue);
});

$(document).ready(function () {
    $(document).on('input', '#domain', function () {
        let domainValue = $(this).val();

        $('#pagerule_url').val(domainValue);
        $('#pagerule_full_url').val('www.' + domainValue);
    });
});

$(document).on('click', 'input[data-enable-input]', function () {
    const targetInput = $($(this).data('enable-input'));
    targetInput.prop('readonly', !this.checked);
});