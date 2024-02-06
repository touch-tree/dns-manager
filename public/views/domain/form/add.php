<form action="<?php echo route('domain.create'); ?>" method="post" class="form js-form">
    <label for="domain" class="text-input-column">
        Domain:
        <input type="text" name="domain" id="domain" placeholder="For example: domain.sub.com" required>
    </label>

    <div class="text-input-row">
        <label for="root_cname_target" class="text-input-column">
            Root CNAME target:
            <input type="text" name="root_cname_target" id="root_cname_target" required>
        </label>

        <label for="sub_cname_target" class="text-input-column">
            Sub CNAME target:
            <input type="text" name="sub_cname_target" id="sub_cname_target" required>
        </label>
    </div>

    <div class="text-input-row">
        <label for="pagerule_url" class="text-input-column">
            PAGERULE URL:
            <input type="text" name="pagerule_url" id="pagerule_url"
                   placeholder="For example: domain.sub.com" required>
        </label>

        <label for="pagerule_full_url" class="text-input-column">
            PAGERULE FULL URL:
            <input type="text" name="pagerule_full_url" id="pagerule_full_url"
                   placeholder="For example: www.domain.sub.com" required>
        </label>
    </div>

    <label for="pagerule_destination_url" class="text-input-column">
        PAGERULE destination URL:
        <input type="text" name="pagerule_destination_url" id="pagerule_destination_url"
               placeholder="For example: https://domain.sub.com/" required>
    </label>

    <button type="submit" class="btn btn-primary form-submit">
        <i class="fa fa-plus" aria-hidden="true"></i>
        Add site
    </button>
</form>