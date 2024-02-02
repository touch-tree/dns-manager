$(document).ready(function () {
    $('body').on('input blur', '.js-form input', function () {
        const input = $(this);
        const value = input.val().trim();
        const label = input.closest('.text-input-column');
        const errorText = $('<span>').addClass('validation-text');

        const existingError = label.find('.validation-text');
        if (existingError.length > 0) {
            existingError.remove();
        }

        input.removeClass('error-form-input');

        if (input.attr('required') && value === '') {
            errorText.text('This field is required.');
            errorText.addClass('error')
            label.append(errorText);
            input.addClass('error-form-input');
        }

        if (input.attr('name') === 'pagerule_full_url' && value !== '') {
            const pageruleUrlInput = $('[name="pagerule_url"]');
            const pageruleUrlValue = pageruleUrlInput.val().trim();
            const isMatching = value === `www.${pageruleUrlValue}`;

            if (!isMatching) {
                errorText.text('PAGERULE FULL URL does not match PAGERULE URL');
                errorText.addClass('error')
                label.append(errorText);
                input.addClass('error-form-input');
            }
        }
    });
});

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
