<!DOCTYPE html>
<html lang="en">

<head>
    <?= view('partials.header')->render(); ?>

    <title>Add</title>
</head>

<body>

<?= view('partials.addons')->render(); ?>

<div class="center-wrap">
    <div class="dashboard-container main-container">
        <div class="dashboard-header">
            <h1 class="dashboard-header__header">Add a site</h1>
            <div class="button-menu">
                <a href="<?= route('dashboard'); ?>">
                    <button class="btn btn-outline-primary">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        Return to dashboard
                    </button>
                </a>
            </div>
        </div>
        <div class="dashboard-content">
            <form action="<?= route('domain.create'); ?>" method="post" class="form js-form" data-blockui-form>
                <form action="<?= route('domain.create'); ?>" method="post" class="form js-form">
                    <label for="domain" class="text-input-column">
                        Domain:
                        <input type="text" name="domain" id="domain" placeholder="For example: domain.sub.com"
                               value="<?= old('domain') ?>">
                        <?php if (error('domain')) { ?>
                            <span class="validation-text error"><?= error('domain') ?></span>
                        <?php } ?>
                    </label>

                    <div class="text-input-row">
                        <label for="root_cname_target" class="text-input-column checkbox-enable">
                            Root CNAME target:
                            <input type="text" name="root_cname_target" id="root_cname_target" readonly
                                   value="<?= old('root_cname_target', config('api.root_cname_target', '')) ?>">
                            <label class="checkbox-enable__label">
                                <input type="checkbox" data-enable-input="#root_cname_target">
                                <span>Enable input field</span>
                            </label>
                            <?php if (error('root_cname_target')) { ?>
                                <span class="validation-text error"><?= error('root_cname_target') ?></span>
                            <?php } ?>
                        </label>

                        <label for="sub_cname_target" class="text-input-column checkbox-enable">
                            Sub CNAME target:
                            <input type="text" name="sub_cname_target" id="sub_cname_target" readonly
                                   value="<?= old('sub_cname_target', config('api.sub_cname_target', '')) ?>">
                            <label class="checkbox-enable__label">
                                <input type="checkbox" data-enable-input="#sub_cname_target">
                                <span>Enable input field</span>
                            </label>
                            <?php if (error('sub_cname_target')) { ?>
                                <span class="validation-text error"><?= error('sub_cname_target') ?></span>
                            <?php } ?>
                        </label>
                    </div>

                    <div class="text-input-row">
                        <label for="pagerule_url" class="text-input-column">
                            PAGERULE URL:
                            <input type="text" name="pagerule_url" id="pagerule_url"
                                   placeholder="For example: domain.sub.com" value="<?= old('pagerule_url') ?>">
                            <?php if (error('pagerule_url')) { ?>
                                <span class="validation-text error"><?= error('pagerule_url') ?></span>
                            <?php } ?>
                        </label>

                        <label for="pagerule_full_url" class="text-input-column">
                            PAGERULE FULL URL:
                            <input type="text" name="pagerule_full_url" id="pagerule_full_url"
                                   placeholder="For example: www.domain.sub.com"
                                   value="<?= old('pagerule_full_url') ?>">
                            <?php if (error('pagerule_full_url')) { ?>
                                <span class="validation-text error"><?= error('pagerule_full_url') ?></span>
                            <?php } ?>
                        </label>
                    </div>

                    <label for="pagerule_forwarding_url" class="text-input-column">
                        PAGERULE destination URL:
                        <input type="text" name="pagerule_forwarding_url" id="pagerule_forwarding_url"
                               placeholder="For example: https://other.domain.sub.com/"
                               value="<?= old('pagerule_forwarding_url') ?>">
                        <?php if (error('pagerule_forwarding_url')) { ?>
                            <span class="validation-text error"><?= error('pagerule_forwarding_url') ?></span>
                        <?php } ?>
                    </label>

                    <button type="submit" class="btn btn-primary form-submit">
                        <i class="fa fa-plus" aria-hidden="true"></i>
                        Add site
                    </button>
                </form>
            </form>
        </div>
    </div>
</div>

<?= view('partials.footer')->render(); ?>
</body>

</html>